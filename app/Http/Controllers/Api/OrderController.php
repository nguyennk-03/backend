<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function processPayment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 401, 'message' => 'Bạn chưa đăng nhập'], 401);
        }

        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.variant_id' => 'required|integer',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'email' => 'required|email',
            'payment_id' => ['required', Rule::in(['Cash', 'Momo', 'PayPal', 'ZaloPay'])],
            'total_price' => 'required|numeric|min:1000',
        ]);

        $payment = Payment::where('name', $validated['payment_id'])->first();
        if (!$payment) {
            return response()->json(['status' => 400, 'message' => 'Phương thức thanh toán không hợp lệ.'], 400);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'email' => $validated['email'],
            'payment_id' => $payment->id, 
            'total_price' => $validated['total_price'],
            'status' => 'pending',
        ]);

        foreach ($validated['products'] as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'variant_id' => $product['variant_id'] ,
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        switch ($validated['payment_id']) {
            case 'Cash':
                return response()->json([
                    'status' => 200,
                    'message' => 'Đơn hàng đã được tạo thành công.',
                    'order_id' => $order->id,
                ]);

            case 'Momo':
                return (new MoMoController())->createPayment($order);

            case 'PayPal':
                return (new PayPalController())->createPayment($order);

            case 'ZaloPay':
                return (new ZaloPayController())->createPayment($order);

            default:
                return response()->json(['status' => 400, 'message' => 'Phương thức thanh toán không hợp lệ.'], 400);
        }
    }
}
