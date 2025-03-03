<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function show($orderId)
    {
        $order = Order::findOrFail($orderId);

        $this->authorize('view', $order);

        $payment = $order->payments()->latest()->first();
        if (!$payment) {
            return response()->json(['message' => 'Không tìm thấy thông tin thanh toán.'], 404);
        }

        return response()->json($payment);
    }

    public function store(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $this->authorize('update', $order);

        if ($order->payments()->where('status', 'completed')->exists()) {
            return response()->json(['message' => 'Đơn hàng đã được thanh toán.'], 400);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:momo,vnpay,paypal,cod',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validated['amount'] != $order->total_price) {
            return response()->json(['message' => 'Số tiền thanh toán không đúng.'], 400);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'status' => 'pending', // Mặc định là pending
        ]);

        return response()->json($payment, 201);
    }

    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        $payment = $order->payments()->latest()->first();
        if (!$payment) {
            return response()->json(['message' => 'Không tìm thấy thông tin thanh toán.'], 404);
        }

        if ($payment->status === 'completed' && $validated['status'] !== 'completed') {
            return response()->json(['message' => 'Không thể thay đổi trạng thái của thanh toán đã hoàn thành.'], 400);
        }

        $payment->update(['status' => $validated['status']]);

        return response()->json($payment);
    }

    public function index()
    {
        $payments = Payment::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($payments);
    }

    public function destroy($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $this->authorize('delete', $payment); 

        // Không cho phép xóa thanh toán đã hoàn thành
        if ($payment->status === 'completed') {
            return response()->json(['message' => 'Không thể xóa thanh toán đã hoàn thành.'], 400);
        }

        $payment->delete();
        return response()->json(['message' => 'Thanh toán đã được xóa.'], 200);
    }
}
