<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\Product;

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

        if (
            empty($this->tmn_code) || empty($this->hash_secret) ||
            empty($this->url) || empty($this->return_url)
        ) {
            throw new \Exception('Cấu hình VNPay bị thiếu hoặc không hợp lệ.');
        }
    }

    public function createPayment($validated, $order)
    {
        try {
            $discountCode = $validated['discount_code'] ?? null;
            $totalPrice = $validated['total_price'];
            $totalVND = $this->applyDiscount($discountCode, $totalPrice);
            $totalVNDForVNPay = $totalVND * 100;

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

            return response()->json([
                'status' => 200,
                'message' => 'Yêu cầu thanh toán VNPay đã được tạo',
                'redirect_url' => $vnp_Url,
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
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

        $now = new \DateTime();
        $discount = Discount::where('code', $discountCode)
            ->where('start_date', '<=', $now->format('Y-m-d H:i:s'))
            ->where('end_date', '>=', $now->format('Y-m-d H:i:s'))
            ->first();

        if (!$discount) {
            throw new \Exception('Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        if ($discount->discount_type === 'percentage') {
            $discountValue = $discount->value;
            if ($discountValue > 100 || $discountValue < 0) {
                throw new \Exception('Phần trăm giảm giá phải nằm trong khoảng từ 0 đến 100');
            }
            $discountAmount = $totalPrice * ($discountValue / 100);
        } else {
            $discountAmount = $discount->value;
        }

        $finalPrice = $totalPrice - $discountAmount;
        return (int) round(max(0, $finalPrice));
    }

    public function VNPaySuccess(Request $request)
    {
        try {
            $vnp_SecureHash = $request->input('vnp_SecureHash');
            $inputData = $request->except('vnp_SecureHash');
            ksort($inputData);
            $hashData = http_build_query($inputData);
            $calculatedHash = hash_hmac('sha512', $hashData, $this->hash_secret);

            if ($calculatedHash !== $vnp_SecureHash) {
                return response()->json(['status' => 400, 'message' => 'Invalid signature']);
            }

            $orderId = explode('_', $request->input('vnp_TxnRef'))[0];
            $order = Order::findOrFail($orderId);

            if ($order->payment_status === 'pending') {
                if ($request->input('vnp_ResponseCode') == '00') {
                    $order->payment_status = 'paid';
                } else {
                    $order->payment_status = 'failed';
                }
                $order->save();
            }

            return response()->json([
                'status' => $order->payment_status === 'paid' ? 200 : 400,
                'message' => $order->payment_status === 'paid' ? 'Thanh toán thành công' : 'Thanh toán thất bại hoặc đã được xử lý'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }
    public function VNPayCancel(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            if (!$orderId || !($order = Order::find($orderId))) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Đơn hàng không tồn tại'
                ], 404);
            }

            if ($order->payment_status !== 'pending') {
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

            foreach ($orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }

            $order->payment_status = 'cancelled';
            $order->save();

            return response()->json([
                'status' => 200,
                'message' => 'Đơn hàng đã được hủy và sản phẩm đã được hoàn lại kho'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi hủy đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}