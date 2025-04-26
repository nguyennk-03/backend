<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    private $tmn_code;
    private $hash_secret;
    private $url;
    private $return_url;

    public function __construct()
    {
        $this->tmn_code = config('services.vnpay.tmn_code');
        $this->hash_secret = config('services.vnpay.hash_secret');
        $this->url = config('services.vnpay.url');
        $this->return_url = config('services.vnpay.return_url');

        $missingConfigs = [];
        if (empty($this->tmn_code)) $missingConfigs[] = 'tmn_code';
        if (empty($this->hash_secret)) $missingConfigs[] = 'hash_secret';
        if (empty($this->url)) $missingConfigs[] = 'url';
        if (empty($this->return_url)) $missingConfigs[] = 'return_url';

        if ($missingConfigs) {
            throw new \Exception('Cấu hình VNPay bị thiếu hoặc không hợp lệ: ' . implode(', ', $missingConfigs));
        }
    }

    public function createPayment($validated, $order)
    {
        try {
            // Kiểm tra đầu vào
            if (!isset($validated['total_price']) || !is_numeric($validated['total_price']) || $validated['total_price'] < 0) {
                throw new \Exception('Tổng giá đơn hàng không hợp lệ.');
            }
            if (!$order instanceof Order || !$order->id) {
                throw new \Exception('Đơn hàng không hợp lệ.');
            }

            $discountCode = $validated['discount_code'] ?? null;
            $totalPrice = (float) $validated['total_price'];
            $totalVND = $this->applyDiscount($discountCode, $totalPrice);
            $totalVNDForVNPay = $totalVND * 100;

            // Cập nhật trạng thái thanh toán
            $order->payment_status = PaymentStatusEnum::PENDING;
            $order->save();

            $vnp_TxnRef = $order->id . '_' . time();
            $vnp_OrderInfo = "Thanh toán đơn hàng #{$order->id}";
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $totalVNDForVNPay;
            $vnp_Locale = 'vn';
            $vnp_IpAddr = request()->ip();
            $vnp_CreateDate = date('YmdHis');

            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $this->tmn_code,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => $vnp_CreateDate,
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $this->return_url,
                "vnp_TxnRef" => $vnp_TxnRef,
            ];

            ksort($inputData);
            $query = http_build_query($inputData);
            $vnp_SecureHash = hash_hmac('sha512', $query, $this->hash_secret);
            $vnp_Url = $this->url . "?" . $query . "&vnp_SecureHash=" . $vnp_SecureHash;

            // Ghi log
            Log::info('VNPay payment request created:', [
                'order_id' => $order->id,
                'vnp_TxnRef' => $vnp_TxnRef,
                'amount' => $totalVND,
                'url' => $vnp_Url,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Yêu cầu thanh toán VNPay đã được tạo',
                'redirect_url' => $vnp_Url,
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            Log::error('VNPay payment creation failed:', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi xử lý thanh toán VNPay: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function applyDiscount(?string $discountCode, float $totalPrice): int
    {
        if (!$discountCode) {
            return (int) round($totalPrice);
        }

        $now = now();
        $discount = Discount::where('code', $discountCode)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();

        if (!$discount) {
            throw new \Exception('Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        $discountAmount = 0;
        if ($discount->discount_type === 'percentage') {
            $discountValue = $discount->value;
            if ($discountValue < 0 || $discountValue > 100) {
                throw new \Exception('Phần trăm giảm giá phải nằm trong khoảng từ 0 đến 100.');
            }
            $discountAmount = $totalPrice * ($discountValue / 100);
        } elseif ($discount->discount_type === 'fixed') {
            $discountValue = $discount->value;
            if ($discountValue < 0) {
                throw new \Exception('Giá trị giảm giá cố định không được âm.');
            }
            $discountAmount = $discountValue;
        } else {
            throw new \Exception('Loại mã giảm giá không hợp lệ.');
        }

        $finalPrice = $totalPrice - $discountAmount;
        $finalPrice = (int) round(max(0, $finalPrice));

        Log::info('Discount applied:', [
            'code' => $discountCode,
            'type' => $discount->discount_type,
            'value' => $discount->value,
            'original_price' => $totalPrice,
            'final_price' => $finalPrice,
        ]);

        return $finalPrice;
    }

    public function handleVnpayIpn(Request $request)
    {
        try {
            Log::info('VNPay IPN received:', $request->all());

            // Kiểm tra tham số bắt buộc
            if (!$request->has('vnp_SecureHash') || !$request->has('vnp_TxnRef') || !$request->has('vnp_ResponseCode')) {
                return response()->json(['RspCode' => '99', 'Message' => 'Thiếu tham số bắt buộc']);
            }

            $vnp_SecureHash = $request->input('vnp_SecureHash');
            $inputData = $request->except('vnp_SecureHash', 'vnp_SecureHashType');
            ksort($inputData);

            $hashData = http_build_query($inputData);
            $calculatedHash = hash_hmac('sha512', $hashData, $this->hash_secret);
            if ($calculatedHash !== $vnp_SecureHash) {
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
            }

            $orderCode = $request->input('vnp_TxnRef');
            $orderIdParts = explode('_', $orderCode);
            if (count($orderIdParts) < 2 || !is_numeric($orderIdParts[0])) {
                return response()->json(['RspCode' => '99', 'Message' => 'Mã giao dịch không hợp lệ']);
            }
            $orderId = $orderIdParts[0];

            $order = Order::find($orderId);
            if (!$order) {
                return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
            }

            // So sánh số tiền
            $vnpAmount = (int) ($request->input('vnp_Amount') / 100);
            if ($vnpAmount !== $order->total_price) {
                return response()->json(['RspCode' => '04', 'Message' => 'Số tiền không khớp']);
            }

            // Chỉ cập nhật nếu trạng thái là PENDING
            if ($order->payment_status === PaymentStatusEnum::PENDING) {
                $order->payment_status = $request->input('vnp_ResponseCode') === '00'
                    ? PaymentStatusEnum::PAID
                    : PaymentStatusEnum::FAILED;
                $order->save();

                Log::info('VNPay IPN processed:', [
                    'order_id' => $order->id,
                    'payment_status' => $order->payment_status->label(),
                ]);
            }

            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        } catch (\Exception $e) {
            Log::error('VNPay IPN Exception:', ['message' => $e->getMessage()]);
            return response()->json([
                'RspCode' => '99',
                'Message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    public function handleVnpayReturn(Request $request)
    {
        try {
            // Kiểm tra tham số bắt buộc
            if (!$request->has('vnp_SecureHash') || !$request->has('vnp_TxnRef') || !$request->has('vnp_ResponseCode')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Thiếu tham số bắt buộc'
                ], 400);
            }

            $vnp_SecureHash = $request->input('vnp_SecureHash');
            $inputData = $request->except('vnp_SecureHash', 'vnp_SecureHashType');
            ksort($inputData);

            $hashData = http_build_query($inputData);
            $calculatedHash = hash_hmac('sha512', $hashData, $this->hash_secret);

            if ($calculatedHash !== $vnp_SecureHash) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chữ ký không hợp lệ!'
                ], 400);
            }

            $orderCode = $request->input('vnp_TxnRef');
            $orderIdParts = explode('_', $orderCode);
            if (count($orderIdParts) < 2 || !is_numeric($orderIdParts[0])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mã giao dịch không hợp lệ'
                ], 400);
            }
            $orderId = $orderIdParts[0];

            $order = Order::find($orderId);
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy đơn hàng'
                ], 404);
            }

            // Không cập nhật trạng thái, dựa vào IPN
            $message = $request->input('vnp_ResponseCode') === '00'
                ? 'Thanh toán thành công'
                : 'Thanh toán thất bại';

            Log::info('VNPay return processed:', [
                'order_id' => $order->id,
                'response_code' => $request->input('vnp_ResponseCode'),
                'message' => $message,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            Log::error('VNPay return Exception:', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý phản hồi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handleVnpayCancel(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            if (!$orderId || !($order = Order::find($orderId))) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Đơn hàng không tồn tại'
                ], 404);
            }

            if ($order->payment_status !== PaymentStatusEnum::PENDING) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Đơn hàng không thể hủy vì không ở trạng thái chờ thanh toán'
                ], 400);
            }

            $orderItems = OrderItem::where('order_id', $order->id)->get();
            if ($orderItems->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Không tìm thấy sản phẩm trong đơn hàng'
                ], 400);
            }

            // Sử dụng transaction
            DB::transaction(function () use ($order, $orderItems) {
                foreach ($orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if (!$product) {
                        Log::warning('Product not found for order item:', [
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,
                        ]);
                        continue;
                    }
                    $product->stock += $item->quantity;
                    $product->save();
                }

                $order->payment_status = PaymentStatusEnum::CANCELED;
                $order->save();
            });

            Log::info('Order cancelled:', [
                'order_id' => $order->id,
                'payment_status' => $order->payment_status->label(),
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Đơn hàng đã được hủy và sản phẩm đã được hoàn lại kho'
            ]);
        } catch (\Exception $e) {
            Log::error('Order cancellation failed:', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi hủy đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}
