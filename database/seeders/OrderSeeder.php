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
        $discountIds = Discount::pluck('id')->toArray();
        $paymentIds = Payment::pluck('id')->toArray();

        $brandPriceRanges = [
            'Adidas' => ['min' => 1000000, 'max' => 8000000],
            'Asics'  => ['min' => 1200000, 'max' => 5000000],
            'Bata'   => ['min' => 500000,  'max' => 1500000],
            'Nike'   => ['min' => 1200000, 'max' => 10000000],
            'Puma'   => ['min' => 900000,  'max' => 4000000],
        ];

        // Tính từ tháng hiện tại trở về 12 tháng trước
        $start = new \DateTime(); // thời điểm hiện tại
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

                    $paymentId = $faker->optional(0.8, null)->randomElement($paymentIds);
                    $statusEnum = $faker->randomElement(OrderStatusEnum::cases());

                    // Xử lý trạng thái thanh toán dựa vào trạng thái đơn hàng
                    switch ($statusEnum) {
                        case OrderStatusEnum::COMPLETED:
                            $paymentStatusEnum = PaymentStatusEnum::PAID;
                            break;
                        case OrderStatusEnum::CANCELED:
                        case OrderStatusEnum::RETURNED:
                            $paymentStatusEnum = $faker->randomElement([PaymentStatusEnum::FAILED, PaymentStatusEnum::REFUNDED]);
                            break;
                        case OrderStatusEnum::SHIPPED:
                        case OrderStatusEnum::PROCESSING:
                            $paymentStatusEnum = $faker->randomElement([PaymentStatusEnum::PENDING, PaymentStatusEnum::PAID]);
                            break;
                        default:
                            $paymentStatusEnum = $faker->randomElement(PaymentStatusEnum::cases());
                            break;
                    }

                    $status = $statusEnum->value;
                    $paymentStatus = $paymentStatusEnum->value;

                    // Tạo đơn hàng tạm thời với giá = 0
                    $order = Order::create([
                        'code' => 'STEPVIET' . strtoupper(uniqid()),
                        'user_id' => $userId,
                        'discount_id' => null,
                        'payment_id' => $paymentId ?? 1,
                        'payment_status' => $paymentStatus,
                        'status' => $status,
                        'total_price' => 0,
                        'total_after_discount' => null,
                        'recipient_name' => $faker->name(),
                        'recipient_phone' => $faker->phoneNumber(),
                        'shipping_address' => $faker->address(),
                        'note' => $faker->text(100),
                        'tracking_code' => $faker->optional()->regexify('[A-Z]{2}[0-9]{8}VN'),
                        'created_at' => $createdAt,
                        'updated_at' => now(),
                    ]);

                    // Tạo các sản phẩm trong đơn hàng (sử dụng Product thay cho ProductVariant)
                    $products = Product::inRandomOrder()->take(rand(1, 2))->get();
                    $realTotal = 0;

                    foreach ($products as $product) {
                        // Lấy thương hiệu của sản phẩm
                        $brand = $product->brand;

                        // Kiểm tra xem thương hiệu có tồn tại trong phạm vi giá không
                        if (isset($brandPriceRanges[$brand->name])) {  // Sửa lỗi tại đây
                            $minPrice = $brandPriceRanges[$brand->name]['min'];
                            $maxPrice = $brandPriceRanges[$brand->name]['max'];

                            // Gán giá ngẫu nhiên trong khoảng giá của thương hiệu
                            $price = $faker->numberBetween($minPrice, $maxPrice);
                        } else {
                            // Nếu không có phạm vi giá, sử dụng giá ngẫu nhiên
                            $price = $product->price;
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

                    // Áp dụng giảm giá nếu có sản phẩm giảm giá
                    $discountId = null;
                    $discountAmount = 0;
                    if (!empty($discountIds) && rand(0, 1)) {
                        $discountId = $faker->randomElement($discountIds);
                        $discountAmount = $realTotal * (rand(5, 20) / 100);
                    }

                    $finalPrice = $realTotal - $discountAmount;

                    // Cập nhật lại tổng tiền đơn hàng
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
