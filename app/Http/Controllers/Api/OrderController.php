<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\MoMoController;

class OrderController extends Controller
{
    public function processPayment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 401, 'message' => 'Bạn chưa đăng nhập'], 401);
        }

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.variant_id' => 'required|integer',
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

            DB::beginTransaction(); // Bắt đầu Transaction

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'payment_id' => $payment->id,
                'total_price' => $validated['total_price'],
                'status' => 'pending',
            ]);

            // Thêm sản phẩm vào đơn hàng
            foreach ($validated['products'] as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $product['variant_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);
            }

            DB::commit(); // Lưu thay đổi

            Log::info('Đơn hàng đã được tạo thành công', ['order_id' => $order->id]);

            // Xử lý thanh toán
            return $this->handlePayment($payment->name, $order);
        } catch (\Throwable $e) { // Dùng \Throwable để bắt cả Exception và Error
            DB::rollBack(); // Hủy transaction nếu có lỗi
            Log::error("Lỗi xử lý đơn hàng: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi xử lý đơn hàng.',
                'error' => $e->getMessage(), // Trả về lỗi chi tiết (chỉ để debug, không nên bật trên production)
            ], 500);
        }
    }

    private function handlePayment($paymentMethod, $order)
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
                    return app(MoMoController::class)->createPayment($order);

                default:
                    return response()->json(['status' => 400, 'message' => 'Phương thức thanh toán không hợp lệ.'], 400);
            }
        } catch (\Throwable $e) {
            Log::error("Lỗi khi xử lý thanh toán ({$paymentMethod}): " . $e->getMessage(), [
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi xử lý thanh toán.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
