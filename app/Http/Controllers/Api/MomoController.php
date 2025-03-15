<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class MoMoController extends Controller
{
    public function createPayment(Order $order)
    {
        $endpoint = env('MOMO_ENDPOINT');
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $orderId = $order->id . "_" . time();
        $amount = number_format($order->total_price, 0, '', '');
        $orderInfo = "Thanh toán đơn hàng #{$order->id}";
        $redirectUrl = env('MOMO_RETURN_URL');
        $ipnUrl = env('MOMO_NOTIFY_URL');
        $requestId = time();
        $extraData = "";

        // Tạo chữ ký bảo mật
        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=captureWallet";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // Gửi dữ liệu lên MoMo
        $response = Http::post($endpoint, [
            'partnerCode' => $partnerCode,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => $extraData,
            'requestType' => 'captureWallet',
            'signature' => $signature,
        ]);

        // Kiểm tra phản hồi từ MoMo
        if ($response->successful()) {
            $jsonResult = $response->json();
            return response()->json([
                'status' => 200,
                'message' => 'Tạo thanh toán MoMo thành công.',
                'pay_url' => $jsonResult['payUrl'] ?? '',
                'order_id' => $order->id,
            ]);
        } else {
            Log::error("Lỗi tạo thanh toán MoMo: " . $response->body());
            return response()->json(['status' => 500, 'message' => 'Lỗi khi tạo thanh toán MoMo.'], 500);
        }
    }

    public function paymentReturn(Request $request)
    {
        return response()->json($request->all());
    }

    public function MoMoSuccess(Request $request)
    {
        $orderId = explode("_", $request->input('orderId'))[0]; // Lấy ID đơn hàng gốc
        $status = $request->input('status');

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng.'], 404);
        }

        if ($status == "success") {
            $order->update(['payment_status' => 'paid']);
            return response()->json([
                'status' => 200,
                'message' => 'Thanh toán MoMo thành công!',
                'order_id' => $order->id,
            ]);
        }

        return response()->json(['status' => 400, 'message' => 'Thanh toán không hợp lệ.'], 400);
    }

    public function MoMoCancel(Request $request)
    {
        $orderId = explode("_", $request->input('orderId'))[0];

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng.'], 404);
        }

        $order->update(['payment_status' => 'failed']);

        return response()->json([
            'status' => 200,
            'message' => 'Giao dịch bị hủy.',
            'order_id' => $order->id,
        ]);
    }
}
