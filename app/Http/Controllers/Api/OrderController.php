<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    // Lấy danh sách tất cả đơn hàng (hỗ trợ lọc theo user và trạng thái)
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    // Lấy thông tin chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with('items')->find($id);
        return $order ? response()->json($order) : response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
    }

    // Tạo đơn hàng mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'discount_id' => 'nullable|exists:discounts,id',
            'payment_method' => ['required', Rule::in(['momo', 'vnpay', 'paypal', 'cod'])],
            'total_price' => 'required|numeric|min:0',
        ]);

        $validated['status'] = 'pending'; // Mặc định trạng thái là 'pending'

        $order = Order::create($validated);
        return response()->json($order, 201);
    }

    // Cập nhật thông tin đơn hàng
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order)
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);

        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'processing', 'completed', 'canceled'])],
            'total_price' => 'nullable|numeric|min:0',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    // Xóa đơn hàng
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order)
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);

        $order->delete();
        return response()->json(['message' => 'Đơn hàng đã được xóa']);
    }

    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'completed', 'failed'])],
        ]);

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Không tìm thấy thông tin thanh toán'], 404);
        }

        $payment->update(['status' => $validated['status']]);

        // Nếu thanh toán hoàn thành, cập nhật trạng thái đơn hàng
        if ($validated['status'] === 'completed') {
            $order->update(['status' => 'completed']);
        }

        return response()->json(['message' => 'Cập nhật trạng thái thanh toán thành công']);
    }
}
