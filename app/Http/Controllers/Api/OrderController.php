<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, Payment, ProductVariant, Cart};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};
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
            ->latest() // Add ordering
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
            'phone' => 'required|regex:/^[0-9]{9,12}$/', // Improved phone validation
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'payment_id' => 'required|integer|exists:payments,id',
            'total_price' => 'required|numeric|min:1000',
        ]);

        // Verify total price matches sum of products
        $calculatedTotal = collect($validated['products'])->sum(fn($product) => 
            $product['price'] * $product['quantity']
        );

        if (abs($calculatedTotal - $validated['total_price']) > 0.01) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Tổng giá không khớp với giá sản phẩm.',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->handleOrderCreation($validated, $user);
    }

    private function handleOrderCreation(array $validated, $user)
    {
        return DB::transaction(function () use ($validated, $user) {
            try {
                $variantIds = array_column($validated['products'], 'variant_id');
                $variants = ProductVariant::whereIn('id', $variantIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // Check stock availability
                foreach ($validated['products'] as $product) {
                    $variant = $variants->get($product['variant_id']);
                    if (!$variant || $variant->stock < $product['quantity']) {
                        throw new \Exception("Sản phẩm ID: {$product['variant_id']} không đủ hàng (còn: " . ($variant->stock ?? 0) . ").");
                    }
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'email' => $validated['email'],
                    'payment_id' => $validated['payment_id'],
                    'total_price' => $validated['total_price'],
                    'status' => 'pending',
                ]);

                $orderItems = array_map(fn($product) => [
                    'order_id' => $order->id,
                    'variant_id' => $product['variant_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $validated['products']);

                OrderItem::insert($orderItems);

                // Update variants
                foreach ($validated['products'] as $product) {
                    $variant = $variants->get($product['variant_id']);
                    $variant->update([
                        'stock' => $variant->stock - $product['quantity'],
                        'sold' => $variant->sold + $product['quantity'],
                    ]);
                }

                Cart::where('user_id', $user->id)->delete();

                Log::info('Tạo đơn hàng thành công', ['order_id' => $order->id]);
                $payment = Payment::findOrFail($validated['payment_id']);

                return $this->handlePayment($payment->name, $order);
            } catch (\Throwable $e) {
                Log::error("Lỗi tạo đơn hàng: {$e->getMessage()}", [
                    'user_id' => $user->id,
                    'request' => $validated,
                ]);

                return response()->json([
                    'status' => $e instanceof \Exception ? Response::HTTP_BAD_REQUEST : Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e instanceof \Exception ? $e->getMessage() : 'Lỗi hệ thống. Vui lòng thử lại sau.',
                ], $e instanceof \Exception ? Response::HTTP_BAD_REQUEST : Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }

    public function cancelPayment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Bạn không có quyền hủy đơn hàng này.',
            ], Response::HTTP_FORBIDDEN);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Chỉ có thể hủy đơn hàng khi đang chờ thanh toán.',
            ], Response::HTTP_BAD_REQUEST);
        }

        return DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                ProductVariant::where('id', $item->variant_id)->update([
                    'stock' => DB::raw("stock + {$item->quantity}"),
                    'sold' => DB::raw("sold - {$item->quantity}"),
                ]);
            }

            $order->delete();

            Log::info('Hủy đơn hàng thành công', ['order_id' => $order->id]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Đơn hàng đã được hủy thành công.',
            ], Response::HTTP_OK);
        });
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