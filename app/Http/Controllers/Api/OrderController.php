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

class OrderController extends Controller
{
    public function processPayment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 401, 'message' => 'Bạn chưa đăng nhập'], 401);
        }

        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.variant_id' => 'required|integer|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'phone' => 'required|numeric|digits_between:9,12',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'payment_id' => 'required|integer|exists:payments,id',
            'total_price' => 'required|numeric|min:1000',
        ]);

        try {
            $payment = Payment::findOrFail($validated['payment_id']);

            DB::beginTransaction();

            foreach ($validated['products'] as $product) {
                $variant = ProductVariant::find($product['variant_id']);
                if (!$variant || $variant->stock < $product['quantity']) {
                    return response()->json([
                        'status' => 400,
                        'message' => "Sản phẩm ID: {$product['variant_id']} không tồn tại hoặc không đủ hàng.",
                    ], 400);
                }
            }

            $order = Order::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'payment_id' => $payment->id,
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

                $variant = ProductVariant::find($product['variant_id']);
                $variant->decrement('stock', $product['quantity']);
                $variant->increment('sold', $product['quantity']);
            }

            DB::commit();
            Log::info('Đơn hàng đã được tạo thành công', ['order_id' => $order->id]);

            return $this->handlePayment($payment->name, $order, $request);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Lỗi xử lý đơn hàng: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi xử lý đơn hàng. Vui lòng thử lại.',
                'error' => config('app.debug') ? $e->getMessage() : 'Vui lòng thử lại sau.',
            ], 500);
        }
    }

    private function handlePayment($paymentMethod, $order, $request)
    {
        try {
            switch ($paymentMethod) {
                case 'COD':
                    return response()->json([
                        'status' => 200,
                        'message' => 'Đơn hàng đã được tạo thành công.',
                        'order_id' => $order->id,
                    ]);

                case 'Momo':
                    return app(MoMoController::class)->createPayment($request, $order);

                case 'VNPay':
                    return app(VNPayController::class)->createPayment($request, $order);

                case 'ZaloPay':
                    return app(ZaloPayController::class)->createPayment($request, $order);

                default:
                    return response()->json([
                        'status' => 400,
                        'message' => 'Phương thức thanh toán không hợp lệ.',
                    ], 400);
            }
        } catch (\Throwable $e) {
            Log::error("Lỗi khi xử lý thanh toán ({$paymentMethod}): " . $e->getMessage(), [
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi xử lý thanh toán. Vui lòng thử lại sau.',
            ], 500);
        }
    }
}
