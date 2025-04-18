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
        $orders = Order::with([
                'items:id,order_id,variant_id,quantity,price',
                'items.variant:id,product_id,stock,sold',
                'payment:id,name',
                'discount:id,code,value,discount_type',
            ])
            ->where('user_id', Auth::id())
            ->select([
                'id', 'code', 'user_id', 'discount_id', 'payment_id', 'status', 'payment_status',
                'total_price', 'total_after_discount', 'tracking_code',
                'recipient_name', 'recipient_phone', 'shipping_address', 'note'
            ])
            ->latest()
            ->get();

        return response()->json($orders, Response::HTTP_OK);
    }

    public function show($id)
    {
        $order = Order::with([
                'items:id,order_id,variant_id,quantity,price',
                'items.variant:id,product_id,stock,sold',
                'items.variant.product:id,name',
                'payment:id,name',
                'discount:id,code,value,discount_type',
            ])
            ->where('user_id', Auth::id())
            ->select([
                'id', 'code', 'user_id', 'discount_id', 'payment_id', 'status', 'payment_status',
                'total_price', 'total_after_discount', 'tracking_code',
                'recipient_name', 'recipient_phone', 'shipping_address', 'note'
            ])
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
            'phone' => 'required|regex:/^[0-9]{9,12}$/',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'payment_id' => 'required|integer|exists:payments,id',
            'total_price' => 'required|numeric|min:1000',
        ]);

        $calculatedTotal = collect($validated['products'])
            ->sum(fn($p) => $p['price'] * $p['quantity']);

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
                $variantIds = collect($validated['products'])->pluck('variant_id');
                $variants = ProductVariant::whereIn('id', $variantIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($validated['products'] as $product) {
                    $variant = $variants->get($product['variant_id']);
                    if (!$variant || $variant->stock < $product['quantity']) {
                        throw new \Exception("Sản phẩm ID {$product['variant_id']} không đủ hàng (còn: " . ($variant->stock ?? 0) . ").");
                    }
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'recipient_name' => $user->name,
                    'recipient_phone' => $validated['phone'],
                    'shipping_address' => $validated['address'],
                    'email' => $validated['email'],
                    'payment_id' => $validated['payment_id'],
                    'total_price' => $validated['total_price'],
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                ]);

                $orderItems = collect($validated['products'])->map(function ($product) use ($order) {
                    return [
                        'order_id' => $order->id,
                        'variant_id' => $product['variant_id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

                OrderItem::insert($orderItems);

                foreach ($validated['products'] as $product) {
                    $variant = $variants->get($product['variant_id']);
                    $variant->decrement('stock', $product['quantity']);
                    $variant->increment('sold', $product['quantity']);
                }

                Cart::where('user_id', $user->id)->delete();

                Log::info('Tạo đơn hàng thành công', ['order_id' => $order->id]);

                return $this->handlePayment($order->payment->name, $order);

            } catch (\Throwable $e) {
                Log::error("Lỗi tạo đơn hàng: {$e->getMessage()}", [
                    'user_id' => $user->id,
                    'request' => $validated,
                ]);

                return response()->json([
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
        $controllers = [
            'COD' => fn() => ['status' => 200, 'message' => 'Đơn hàng COD đã được tạo.', 'order_id' => $order->id],
            'Momo' => fn() => app(MoMoController::class)->createPayment(request(), $order),
            'VNPay' => fn() => app(VNPayController::class)->createPayment(request(), $order),
            'ZaloPay' => fn() => app(ZaloPayController::class)->createPayment(request(), $order),
        ];

        if (!isset($controllers[$paymentMethod])) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Phương thức thanh toán không được hỗ trợ.',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $response = $controllers[$paymentMethod]();
            return response()->json($response, Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error("Lỗi thanh toán ($paymentMethod): {$e->getMessage()}", ['order_id' => $order->id]);

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Lỗi xử lý thanh toán. Vui lòng thử lại.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
