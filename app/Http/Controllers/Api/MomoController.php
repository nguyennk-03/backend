<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class MoMoController extends Controller
{
    public function createPayment($order)
    {
        $phone = env('MOMO_PHONE');
        $total_price = number_format($order->total_price, 0, '', '');
        $momoQR = "https://nhantien.momo.vn/{$phone}/{$total_price}";

        return response()->json([
            'status' => 200,
            'message' => 'Tạo thanh toán MoMo thành công.',
            'order_id' => $order->id,
            'pay_link' => $momoQR,
        ]);
    }
    public function MoMoSuccess(Request $request)
    {
        $orderId = $request->input('orderId');
        $amount = $request->input('amount');
        $status = $request->input('status');

        $order = Order::where('id', $orderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng.'], 404);
        }

        if ($status == "success") {
            $order->update([
                'payment_status' => 'paid'
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Thanh toán MoMo thành công!',
                'order_id' => $order->id,
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Thanh toán không hợp lệ.',
        ]);
    }

    public function MoMoCancel(Request $request)
    {
        $orderId = $request->input('orderId');

        $order = Order::where('id', $orderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng.'], 404);
        }

        $order->update([
            'payment_status' => 'failed'
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Giao dịch bị hủy.',
            'order_id' => $order->id,
        ]);
    }
}
