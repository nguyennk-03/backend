<?php

namespace Database\Seeders;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Discount;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderItem;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');

        $userIds = User::pluck('id')->toArray();
        $discounts = Discount::all()->keyBy('id');
        $paymentIds = Payment::pluck('id')->toArray();
        $products = Product::with('brand')->get(); // Tải trước sản phẩm và brand

        $brandPriceRanges = [
            'Adidas' => ['min' => 1000000, 'max' => 8000000],
            'Asics'  => ['min' => 1200000, 'max' => 5000000],
            'Bata'   => ['min' => 500000,  'max' => 1500000],
            'Nike'   => ['min' => 1200000, 'max' => 10000000],
            'Puma'   => ['min' => 900000,  'max' => 4000000],
        ];

        // Tính từ tháng hiện tại trở về 12 tháng trước
        $start = new \DateTime();
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $months[] = $start->format('Y-m');
            $start->modify('-1 month');
        }

        foreach ($userIds as $userId) {
            foreach ($months as $month) {
                $orderCount = rand(1, 10);

                for ($i = 0; $i < $orderCount; $i++) {
                    $endOfMonth = (new \DateTime("{$month}-01"))->modify('last day of this month');
                    $createdAt = $faker->dateTimeBetween("{$month}-01", $endOfMonth->format('Y-m-d'));

                    $paymentId = $paymentIds ? $faker->optional(0.8, null)->randomElement($paymentIds) : null;
                    $statusEnum = $faker->randomElement(OrderStatusEnum::cases());

                    // Xử lý trạng thái thanh toán dựa trên trạng thái đơn hàng
                    switch ($statusEnum) {
                        case OrderStatusEnum::PENDING:
                        case OrderStatusEnum::AWAITING_CONFIRMATION:
                            $paymentStatusEnum = PaymentStatusEnum::PENDING; // Chưa xác nhận, chờ thanh toán
                            break;
                        case OrderStatusEnum::PROCESSING:
                        case OrderStatusEnum::PACKING:
                            $paymentStatusEnum = PaymentStatusEnum::PAID; // Đã xử lý hoặc đóng gói, giả định đã thanh toán
                            break;
                        case OrderStatusEnum::SHIPPED:
                            $paymentStatusEnum = PaymentStatusEnum::PAID; // Đã gửi, giả định đã thanh toán
                            break;
                        case OrderStatusEnum::DELIVERED:
                            $paymentStatusEnum = PaymentStatusEnum::PAID; // Giao thành công, đã thanh toán
                            break;
                        case OrderStatusEnum::CANCELED:
                            $paymentStatusEnum = PaymentStatusEnum::CANCELED; // Đơn hủy, thanh toán hủy
                            break;
                        case OrderStatusEnum::RETURN_REQUESTED:
                        case OrderStatusEnum::RETURN_PROCESSING:
                            $paymentStatusEnum = PaymentStatusEnum::PAID; // Yêu cầu trả, đang xử lý, chưa hoàn tiền
                            break;
                        case OrderStatusEnum::RETURNED:
                            $paymentStatusEnum = PaymentStatusEnum::REFUNDED; // Đã trả hàng, đã hoàn tiền
                            break;
                        default:
                            $paymentStatusEnum = PaymentStatusEnum::PENDING; // Mặc định, chờ thanh toán
                            break;
                    }

                    $status = $statusEnum->value;
                    $paymentStatus = $paymentStatusEnum->value;

                    // Tạo đơn hàng tạm thời với giá = 0
                    $order = Order::create([
                        'code' => 'STEPVIET' . strtoupper(uniqid()),
                        'user_id' => $userId,
                        'discount_id' => null,
                        'payment_id' => $paymentId,
                        'payment_status' => $paymentStatus,
                        'status' => $status,
                        'total_price' => 0,
                        'total_after_discount' => null,
                        'recipient_name' => $faker->name(),
                        'recipient_phone' => $faker->phoneNumber(),
                        'shipping_address' => $faker->address(),
                        'note' => $faker->optional(0.5)->text(100),
                        'tracking_code' => $faker->optional(0.3)->regexify('[A-Z]{2}[0-9]{8}VN'),
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt, // Đồng bộ với created_at
                    ]);

                    // Chọn ngẫu nhiên 1-2 sản phẩm
                    $selectedProducts = $products->random(min(1, $products->count(), rand(1, 2)));
                    $realTotal = 0;

                    foreach ($selectedProducts as $product) {
                        // Lấy thương hiệu của sản phẩm
                        $brandName = $product->brand ? $product->brand->name : null;

                        // Gán giá dựa trên thương hiệu
                        if ($brandName && isset($brandPriceRanges[$brandName])) {
                            $minPrice = $brandPriceRanges[$brandName]['min'];
                            $maxPrice = $brandPriceRanges[$brandName]['max'];
                            $price = $faker->numberBetween($minPrice, $maxPrice);
                        } else {
                            $price = $product->price ?? $faker->numberBetween(500000, 5000000);
                        }

                        $quantity = rand(1, 3);
                        $realTotal += $price * $quantity;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'price' => $price,
                        ]);
                    }

                    // Áp dụng giảm giá nếu có
                    $discountId = null;
                    $discountAmount = 0;
                    if (!empty($discounts) && rand(0, 1)) {
                        $discount = $faker->randomElement($discounts->values()->all());
                        $discountId = $discount->id;

                        if ($discount->discount_type === 'percentage') {
                            $discountValue = min(max($discount->value, 0), 100);
                            $discountAmount = $realTotal * ($discountValue / 100);
                        } else {
                            $discountAmount = max($discount->value, 0);
                        }
                    }

                    $finalPrice = max(0, $realTotal - $discountAmount);

                    // Cập nhật tổng tiền đơn hàng
                    $order->update([
                        'discount_id' => $discountId,
                        'total_price' => $realTotal,
                        'total_after_discount' => $discountId ? $finalPrice : null,
                    ]);
                }
            }
        }
    }
}
