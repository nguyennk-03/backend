<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\ProductVariant;

class MoMoController extends Controller
{
    private $phone;
    private $partner_code;
    private $access_key;
    private $secret_key;
    private $endpoint;
    private $return_url;
    private $notify_url;

    public function __construct()
    {
        $this->phone = config('services.momo.phone');
        $this->partner_code = config('services.momo.partner_code');
        $this->access_key = config('services.momo.access_key');
        $this->secret_key = config('services.momo.secret_key');
        $this->endpoint = config('services.momo.endpoint');
        $this->return_url = config('services.momo.return_url');
        $this->notify_url = config('services.momo.notify_url');

        if (
            empty($this->phone) || empty($this->partner_code) || empty($this->access_key) ||
            empty($this->secret_key) || empty($this->endpoint) || empty($this->return_url) ||
            empty($this->notify_url)
        ) {
            throw new \Exception('Cấu hình MoMo bị thiếu hoặc không hợp lệ.');
        }
    }

    public function createPayment($validated, $order)
    {
        try {
            $discountCode = $validated['discount_code'] ?? null;
            $totalPrice = $validated['total_price'];
            $totalVND = $this->applyDiscount($discountCode, $totalPrice);

            $orderId = $order->id . '_' . time();
            $requestId = time() . '';
            $requestType = "payWithATM"; // Thanh toán bằng thẻ ATM nội địa
            $extraData = base64_encode(json_encode(['email' => 'test@example.com'])); // Dữ liệu bổ sung

            $rawHash = "accessKey={$this->access_key}&amount={$totalVND}&extraData={$extraData}&ipnUrl={$this->notify_url}&orderId={$orderId}&orderInfo=Thanh toán đơn hàng #{$order->id}&partnerCode={$this->partner_code}&redirectUrl={$this->return_url}&requestId={$requestId}&requestType={$requestType}";
            $signature = hash_hmac("sha256", $rawHash, $this->secret_key);

            $data = [
                "partnerCode" => $this->partner_code,
                "partnerName" => "Your Partner Name",
                "storeId" => "MoMoTestStore",
                "requestId" => $requestId,
                "amount" => $totalVND,
                "orderId" => $orderId,
                "orderInfo" => "Thanh toán đơn hàng #{$order->id}",
                "redirectUrl" => $this->return_url,
                "ipnUrl" => $this->notify_url,
                "lang" => "vi",
                "extraData" => $extraData,
                "requestType" => $requestType,
                "signature" => $signature
            ];

            $response = \Illuminate\Support\Facades\Http::post($this->endpoint, $data)->json();

            if (isset($response['resultCode']) && $response['resultCode'] == 0) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Yêu cầu thanh toán MoMo đã được tạo',
                    'redirect_url' => $response['payUrl'],
                    'order_id' => $order->id,
                ]);
            }

            return response()->json([
                'status' => 400,
                'message' => 'Lỗi khi tạo yêu cầu thanh toán MoMo: ' . ($response['message'] ?? 'Lỗi không xác định.'),
                'error_code' => $response['resultCode'] ?? null,
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi xử lý thanh toán MoMo: ' . $e->getMessage(),
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

    public function MoMoSuccess(Request $request)
    {
        try {
            $data = $request->all();
            $rawHash = "accessKey={$this->access_key}&amount={$data['amount']}&extraData={$data['extraData']}&message={$data['message']}&orderId={$data['orderId']}&orderInfo={$data['orderInfo']}&orderType={$data['orderType']}&partnerCode={$data['partnerCode']}&payType={$data['payType']}&requestId={$data['requestId']}&responseTime={$data['responseTime']}&resultCode={$data['resultCode']}&transId={$data['transId']}";
            $signature = hash_hmac("sha256", $rawHash, $this->secret_key);

            if ($signature !== $data['signature']) {
                return response()->json(['status' => 400, 'message' => 'Invalid signature']);
            }

            $orderId = explode('_', $data['orderId'])[0];
            $order = Order::findOrFail($orderId);

            if ($order->payment_status === 'pending') {
                if ($data['resultCode'] == 0) {
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

    public function MoMoNotify(Request $request)
    {
        try {
            $data = $request->all();
            $rawHash = "accessKey={$this->access_key}&amount={$data['amount']}&extraData={$data['extraData']}&message={$data['message']}&orderId={$data['orderId']}&orderInfo={$data['orderInfo']}&orderType={$data['orderType']}&partnerCode={$data['partnerCode']}&payType={$data['payType']}&requestId={$data['requestId']}&responseTime={$data['responseTime']}&resultCode={$data['resultCode']}&transId={$data['transId']}";
            $signature = hash_hmac("sha256", $rawHash, $this->secret_key);

            if ($signature !== $data['signature']) {
                return response()->json(['status' => 400, 'message' => 'Invalid signature']);
            }

            $orderId = explode('_', $data['orderId'])[0];
            $order = Order::findOrFail($orderId);

            if ($order->payment_status === 'pending') {
                if ($data['resultCode'] == 0) {
                    $order->payment_status = 'paid';
                } else {
                    $order->payment_status = 'failed';
                }
                $order->save();
            }

            return response()->json(['status' => 200, 'message' => 'Xử lý thành công']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }
    public function MoMoCancel(Request $request)
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
                $product = ProductVariant::find($item->product_id);
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