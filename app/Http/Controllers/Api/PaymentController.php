<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentController extends Controller
{
    protected $momoService;

    public function __construct(MomoService $momoService)
    {
        $this->momoService = $momoService;
    }

    // 🔹 Lấy danh sách thanh toán
    public function index()
    {
        return PaymentResource::collection(Payment::latest()->get());
    }

    // 🔹 Xem chi tiết thanh toán
    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return new PaymentResource($payment);
    }

    // 🔹 Tạo thanh toán mới (sử dụng QR MoMo cá nhân)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1000',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        // Kiểm tra nếu đơn hàng đã thanh toán
        if ($order->payments()->where('status', 'completed')->exists()) {
            return response()->json([
                'message' => 'Đơn hàng đã được thanh toán.',
                'status_code' => 400
            ], 400);
        }

        try {
            // Tạo QR MoMo cá nhân
            $qrCode = $this->momoService->generatePersonalQRCode($validated['amount']);

            // Lưu thông tin thanh toán vào database
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'payment_method' => 'momo',
                'amount' => $validated['amount'],
                'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Tạo thanh toán thành công.',
                'qr_code' => $qrCode,
                'payment' => new PaymentResource($payment),
                'status_code' => 201
            ], 201);
        } catch (Exception $e) {
            Log::error("❌ Lỗi tạo thanh toán MoMo: " . $e->getMessage());
            return response()->json([
                'message' => 'Lỗi tạo thanh toán. Vui lòng thử lại sau.',
                'status_code' => 500
            ], 500);
        }
    }

    // 🔹 Cập nhật trạng thái thanh toán
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        if ($payment->status === 'completed' && $validated['status'] !== 'completed') {
            return response()->json([
                'message' => 'Không thể thay đổi trạng thái của thanh toán đã hoàn thành.',
                'status_code' => 400
            ], 400);
        }

        $payment->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Cập nhật trạng thái thành công.',
            'payment' => new PaymentResource($payment),
            'status_code' => 200
        ], 200);
    }

    // 🔹 Xóa thanh toán (chỉ khi chưa hoàn thành)
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'completed') {
            return response()->json([
                'message' => 'Không thể xóa thanh toán đã hoàn thành.',
                'status_code' => 400
            ], 400);
        }

        $payment->delete();
        return response()->json([
            'message' => 'Thanh toán đã được xóa.',
            'status_code' => 200
        ], 200);
    }
}
