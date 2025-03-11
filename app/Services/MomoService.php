<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class MomoService
{
    protected $phone;
    protected $partnerCode;
    protected $accessKey;
    protected $secretKey;
    protected $apiEndpoint;
    protected $redirectUrl;
    protected $ipnUrl;

    public function __construct()
    {
        // 🔹 Cấu hình MoMo Cá Nhân (QR Code)
        $this->phone = env('MOMO_PHONE', '0397993624');

        // 🔹 Cấu hình MoMo Business (Payment Gateway)
        $this->partnerCode = env('MOMO_PARTNER_CODE');
        $this->accessKey = env('MOMO_ACCESS_KEY');
        $this->secretKey = env('MOMO_SECRET_KEY');
        $this->apiEndpoint = env('MOMO_API_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $this->redirectUrl = env('MOMO_REDIRECT_URL', 'https://your-redirect-url.com');
        $this->ipnUrl = env('MOMO_IPN_URL', 'https://your-ipn-url.com');

        // Kiểm tra nếu thiếu thông tin quan trọng
        if (!$this->partnerCode || !$this->accessKey || !$this->secretKey) {
            throw new Exception("⚠️ Missing MoMo API credentials. Please check your .env file.");
        }
    }

    /**
     * 🔹 Tạo link thanh toán MoMo cá nhân (QR Code)
     * @param int $amount Số tiền cần thanh toán
     * @return string Đường dẫn thanh toán MoMo
     */
    public function generatePersonalPaymentLink($amount)
    {
        return "https://nhantien.momo.vn/{$this->phone}/{$amount}";
    }

    /**
     * 🔹 Tạo mã QR từ link thanh toán MoMo cá nhân
     * @param int $amount Số tiền cần thanh toán
     * @return string URL hình ảnh QR Code
     */
    public function generatePersonalQRCode($amount)
    {
        $paymentLink = $this->generatePersonalPaymentLink($amount);
        return "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($paymentLink);
    }

    /**
     * 🔹 Tạo yêu cầu thanh toán qua MoMo Payment Gateway (Business)
     * @param string $orderId Mã đơn hàng
     * @param int $amount Số tiền thanh toán
     * @param string $orderInfo Mô tả đơn hàng
     * @return array|string Kết quả trả về từ MoMo API
     */
    public function createBusinessPayment($orderId, $amount, $orderInfo)
    {
        try {
            $requestId = Str::uuid()->toString();
            $extraData = ""; // Dữ liệu thêm (nếu có)

            // 🔹 Tạo chữ ký (signature)
            $rawData = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$this->ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}&redirectUrl={$this->redirectUrl}&requestId={$requestId}&requestType=captureWallet";
            $signature = hash_hmac("sha256", $rawData, $this->secretKey);

            // 🔹 Ghi log request gửi đến MoMo để debug nếu có lỗi
            Log::info('🔹 MoMo API Request:', [
                'partnerCode' => $this->partnerCode,
                'accessKey' => $this->accessKey,
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $this->redirectUrl,
                'ipnUrl' => $this->ipnUrl,
                'signature' => $signature,
            ]);

            // 🔹 Gửi request đến MoMo API
            $response = Http::post($this->apiEndpoint, [
                "partnerCode" => $this->partnerCode,
                "accessKey" => $this->accessKey,
                "requestId" => $requestId,
                "amount" => $amount,
                "orderId" => $orderId,
                "orderInfo" => $orderInfo,
                "redirectUrl" => $this->redirectUrl,
                "ipnUrl" => $this->ipnUrl,
                "extraData" => $extraData,
                "requestType" => "captureWallet",
                "signature" => $signature,
                "lang" => "vi"
            ]);

            // 🔹 Ghi log phản hồi từ MoMo
            Log::info('🔹 MoMo API Response:', $response->json());

            return $response->json();
        } catch (Exception $e) {
            Log::error("❌ MoMo Payment Error: " . $e->getMessage());
            return ['error' => 'Payment request failed. Please try again.'];
        }
    }
}
