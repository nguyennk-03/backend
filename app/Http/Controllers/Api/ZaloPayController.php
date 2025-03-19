<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\Discount;

class ZaloPayController extends Controller
{
    private $app_id;
    private $key1;
    private $key2;
    private $endpoint;
    private $callback_url;

    public function __construct()
    {
        $this->app_id = config('services.zalopay.app_id');
        $this->key1 = config('services.zalopay.key1');
        $this->key2 = config('services.zalopay.key2');
        $this->endpoint = config('services.zalopay.endpoint');
        $this->callback_url = config('services.zalopay.callback_url');

        if (
            empty($this->app_id) || empty($this->key1) || empty($this->key2) ||
            empty($this->endpoint) || empty($this->callback_url)
        ) {
            throw new \Exception('Cấu hình ZaloPay bị thiếu hoặc không hợp lệ.');
        }
    }

    public function createPayment($validated, $order)
    {
        try {
            $discountCode = $validated['discount_code'] ?? null;
            $totalPrice = $validated['total_price'];
            $totalVND = $this->applyDiscount($discountCode, $totalPrice);

            $embeddata = json_encode(['redirecturl' => 'http://localhost:3000/payment-check']);
            $item = json_encode($validated['products']);
            $order_id = date("ymd") . "_" . time();
            Cache::put("zalopay_order_{$order_id}", $order->id, now()->addHour());
            $apptime = round(microtime(true) * 1000);

            $data = [
                "appid" => $this->app_id,
                "appuser" => "user_" . $order->id,
                "apptime" => $apptime,
                "amount" => $totalVND,
                "apptransid" => $order_id,
                "embeddata" => $embeddata,
                "item" => $item,
                "bankcode" => "",
                "description" => "Thanh toán đơn hàng #{$order->id}",
                "callbackurl" => $this->callback_url
            ];

            $data["mac"] = hash_hmac("sha256", implode("|", [
                $this->app_id,
                $order_id,
                "user_" . $order->id,
                $totalVND,
                $apptime,
                $embeddata,
                $item
            ]), $this->key1);

            $response = Http::asForm()->post($this->endpoint, $data)->json();

            if (isset($response["returncode"]) && $response["returncode"] == 1) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Yêu cầu thanh toán ZaloPay đã được tạo',
                    'redirect_url' => $response['orderurl'],
                    'order_id' => $order->id,
                ]);
            }

            return response()->json([
                'status' => 400,
                'message' => 'Lỗi khi tạo yêu cầu thanh toán ZaloPay: ' . ($response['returnmessage'] ?? 'Lỗi không xác định.'),
                'error_code' => $response['returncode'] ?? null,
                'sub_error_code' => $response['subreturncode'] ?? null,
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi xử lý thanh toán ZaloPay: ' . $e->getMessage(),
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

    public function handleCallback(Request $request)
    {
        try {
            $data = $request->input('data');
            $mac = $request->input('mac');
            $calculatedMac = hash_hmac('sha256', $data, $this->key2);

            if ($calculatedMac !== $mac) {
                return response()->json(['returncode' => -1, 'returnmessage' => 'Invalid signature']);
            }

            $callbackData = json_decode($data, true);
            $apptransid = $callbackData['apptransid'];
            $orderId = Cache::get("zalopay_order_{$apptransid}");

            if (!$orderId || !($order = Order::find($orderId))) {
                return response()->json(['returncode' => -1, 'returnmessage' => 'Order not found']);
            }

            if ($order->payment_status === 'pending') {
                if ($callbackData['returncode'] == 1) {
                    $order->payment_status = 'paid';
                } elseif ($callbackData['returncode'] == 2) {
                    $order->payment_status = 'failed';
                }
                $order->save();
            }

            return response()->json(['returncode' => 1, 'returnmessage' => 'Success']);
        } catch (\Exception $e) {
            return response()->json(['returncode' => -1, 'returnmessage' => 'Error: ' . $e->getMessage()]);
        }
    }
}