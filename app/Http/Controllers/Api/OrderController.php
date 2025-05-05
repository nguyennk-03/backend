<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, Payment, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'items:id,order_id,product_id,quantity,price',
            'items.product:id,name,stock_quantity,sold',
            'payment:id,name',
            'discount:id,code,value,discount_type',
        ])
            ->where('user_id', Auth::id())
            ->select([
                'id',
                'code',
                'user_id',
                'discount_id',
                'payment_id',
                'status',
                'payment_status',
                'total_price',
                'total_after_discount',
                'tracking_code',
                'recipient_name',
                'recipient_phone',
                'shipping_address',
                'note'
            ])
            ->latest()
            ->get();

        return response()->json($orders, Response::HTTP_OK);
    }

    public function show($id)
    {
        $order = Order::with([
            'items:id,order_id,product_id,quantity,price',
            'items.product:id,name,stock_quantity,sold',
            'payment:id,name',
            'discount:id,code,value,discount_type',
        ])
            ->where('user_id', Auth::id())
            ->select([
                'id',
                'code',
                'user_id',
                'discount_id',
                'payment_id',
                'status',
                'payment_status',
                'total_price',
                'total_after_discount',
                'tracking_code',
                'recipient_name',
                'recipient_phone',
                'shipping_address',
                'note'
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
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1|max:1000',
            'products.*.price' => 'required|numeric|min:0',
            'phone' => 'required|regex:/^[0-9]{9,12}$/',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'payment_id' => 'required|integer|exists:payments,id',
            'total_price' => 'required|numeric|min:1000',
        ]);

        $calculatedProductTotal = collect($validated['products'])
            ->sum(fn($p) => $p['price'] * $p['quantity']);
        $shippingFee = $validated['shipping_fee'] ?? 0; // frontend truyền lên

        $calculatedTotal = $calculatedProductTotal + $shippingFee;
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
                $productIds = collect($validated['products'])->pluck('product_id');
                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($validated['products'] as $product) {
                    $item = $products->get($product['product_id']);
                    if (!$item || $item->stock_quantity < $product['quantity']) {
                        throw new \Exception("Sản phẩm ID {$product['product_id']} không đủ hàng (còn: " . ($item->stock_quantity ?? 0) . ").");
                    }
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'recipient_name' => $user->name,
                    'recipient_phone' => $validated['phone'],
                    'shipping_address' => $validated['address'],
                    'email' => $validated['email'],
                    'payment_id' => $validated['payment_id'],
                    'total_price' => $validated['total_price'] / 100, // Chia cho 100 để lưu dưới dạng decimal(10, 2)
                    'total_after_discount' => isset($validated['total_after_discount']) ? $validated['total_after_discount'] / 100 : null,
                    'status' => OrderStatusEnum::PENDING,
                    'payment_status' => PaymentStatusEnum::PENDING,
                    'code' => 'StepViet' . time() . $user->id,
                    'note' => $validated['note'] ?? null,
                    'tracking_code' => null,
                    'discount_id' => $validated['discount_id'] ?? null,
                ]);

                $orderItems = collect($validated['products'])->map(function ($product) use ($order) {
                    return [
                        'order_id' => $order->id,
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price'] / 100, // Chia cho 100 để lưu dưới dạng decimal(10, 2)
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

                OrderItem::insert($orderItems);

                foreach ($validated['products'] as $product) {
                    $item = $products->get($product['product_id']);
                    $item->decrement('stock_quantity', $product['quantity']);
                    $item->increment('sold', $product['quantity']);
                }

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

        if ($order->status !== OrderStatusEnum::PENDING) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Chỉ có thể hủy đơn hàng khi đang chờ xử lý.',
            ], Response::HTTP_BAD_REQUEST);
        }

        return DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->update([
                    'stock_quantity' => DB::raw("stock_quantity + {$item->quantity}"),
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
