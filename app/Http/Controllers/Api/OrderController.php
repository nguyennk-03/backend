<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with([
                'items' => fn($query) => $query->select('id', 'order_id', 'variant_id', 'quantity', 'price'),
                'items.variant' => fn($query) => $query->select('id', 'product_id', 'stock', 'sold'),
                'payment' => fn($query) => $query->select('id', 'name')
            ])
            ->select('id', 'user_id', 'total_price', 'status', 'payment_id')
            ->get();

        return response()->json($orders, Response::HTTP_OK);
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with([
                'items' => fn($query) => $query->select('id', 'order_id', 'variant_id', 'quantity', 'price'),
                'items.variant' => fn($query) => $query->select('id', 'product_id', 'stock', 'sold'),
                'items.variant.product' => fn($query) => $query->select('id', 'name'),
                'payment' => fn($query) => $query->select('id', 'name')
            ])
            ->select('id', 'user_id', 'total_price', 'status', 'payment_id')
            ->findOrFail($id);

        return response()->json($order, Response::HTTP_OK);
    }

    public function processPayment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Bạn cần đăng nhập để thực hiện thanh toán.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.variant_id' => 'required|integer|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1|max:1000', 
            'products.*.price' => 'required|numeric|min:0',
            'phone' => 'required|numeric|digits_between:9,12',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'payment_id' => 'required|integer|exists:payments,id',
            'total_price' => 'required|numeric|min:1000',
        ], [
            'products.required' => 'Vui lòng chọn ít nhất một sản phẩm.',
            'products.*.quantity.min' => 'Số lượng phải lớn hơn 0.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'address.required' => 'Địa chỉ là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'payment_id.exists' => 'Phương thức thanh toán không hợp lệ.',
            'total_price.min' => 'Tổng giá trị đơn hàng phải ít nhất 1,000.',
        ]);

        return $this->handleOrderCreation($validated, $user);
    }

    private function handleOrderCreation(array $validated, $user)
    {
        try {
            // Tải trước tất cả variants để kiểm tra stock
            $variantIds = array_column($validated['products'], 'variant_id');
            $variants = ProductVariant::whereIn('id', $variantIds)->lockForUpdate()->get();

            // Kiểm tra stock
            foreach ($validated['products'] as $product) {
                $variant = $variants->firstWhere('id', $product['variant_id']);
                if (!$variant || $variant->stock < $product['quantity']) {
                    return response()->json([
                        'status' => Response::HTTP_BAD_REQUEST,
                        'message' => "Sản phẩm ID: {$product['variant_id']} không đủ hàng (còn: " . ($variant->stock ?? 0) . ").",
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            return DB::transaction(function () use ($validated, $user, $variants) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'email' => $validated['email'],
                    'payment_id' => $validated['payment_id'],
                    'total_price' => $validated['total_price'],
                    'status' => 'pending',
                ]);

                foreach ($validated['products'] as $product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'variant_id' => $product['variant_id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                    ]);

                    $variant = $variants->firstWhere('id', $product['variant_id']);
                    $variant->stock -= $product['quantity'];
                    $variant->sold += $product['quantity'];
                    $variant->save();
                }

                Log::info('Tạo đơn hàng thành công', ['order_id' => $order->id]);
                $payment = Payment::find($validated['payment_id']);

                return $this->handlePayment($payment->name, $order);
            });
        } catch (\Throwable $e) {
            Log::error("Lỗi tạo đơn hàng: {$e->getMessage()}", [
                'user_id' => $user->id,
                'request' => $validated,
            ]);

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Lỗi hệ thống. Vui lòng thử lại sau.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function handlePayment(string $paymentMethod, Order $order)
    {
        $paymentControllers = [
            'COD' => fn() => ['status' => 200, 'message' => 'Đơn hàng COD đã được tạo.', 'order_id' => $order->id],
            'Momo' => fn() => app(MoMoController::class)->createPayment(request(), $order),
            'VNPay' => fn() => app(VNPayController::class)->createPayment(request(), $order),
            'ZaloPay' => fn() => app(ZaloPayController::class)->createPayment(request(), $order),
        ];

        if (!isset($paymentControllers[$paymentMethod])) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Phương thức thanh toán không được hỗ trợ.',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $response = $paymentControllers[$paymentMethod]();
            return response()->json($response, Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error("Lỗi thanh toán ({$paymentMethod}): {$e->getMessage()}", ['order_id' => $order->id]);
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Lỗi xử lý thanh toán. Vui lòng thử lại.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}