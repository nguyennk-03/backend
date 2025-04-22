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
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');

        $userIds = User::pluck('id')->toArray();
        $discountIds = Discount::pluck('id')->toArray();
        $paymentIds = Payment::pluck('id')->toArray();

        // Tính từ tháng hiện tại trở về 12 tháng trước
        $start = new \DateTime(); // thời điểm hiện tại
        $months = [];

        // Lấy 12 tháng trở về trước
        for ($i = 0; $i < 12; $i++) {
            $months[] = $start->format('Y-m'); // lưu lại năm-tháng
            $start->modify('-1 month');
        }

        foreach ($userIds as $userId) {
            foreach ($months as $month) {
                // Số đơn hàng ngẫu nhiên cho mỗi người dùng (từ 1 đến 10)
                $orderCount = rand(1, 10); // Tạo số lượng đơn hàng ngẫu nhiên cho mỗi người dùng

                for ($i = 0; $i < $orderCount; $i++) {
                    // Ngày ngẫu nhiên trong tháng
                    $endOfMonth = (new \DateTime("{$month}-01"))->modify('last day of this month');
                    $createdAt = $faker->dateTimeBetween("{$month}-01", $endOfMonth->format('Y-m-d'));

                    $totalPrice = $faker->randomFloat(2, 500000, 10000000);
                    $paymentId = $faker->optional(0.8, null)->randomElement($paymentIds);
                    $statusEnum = $faker->randomElement(OrderStatusEnum::cases());

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
                        default: // PENDING
                            $paymentStatusEnum = $faker->randomElement(PaymentStatusEnum::cases());
                            break;
                    }

                    $status = $statusEnum->value;
                    $paymentStatus = $paymentStatusEnum->value;

                    // Kiểm tra sản phẩm có giảm giá không
                    $productsWithDiscount = Product::where('sale', 1)->get(); // Lọc sản phẩm đang giảm giá
                    $discountId = null;
                    if ($productsWithDiscount->isNotEmpty()) {
                        $discountId = $faker->randomElement($discountIds); // Chọn giảm giá ngẫu nhiên
                    }

                    $discountAmount = $discountId ? $totalPrice * (rand(5, 20) / 100) : 0;
                    $finalPrice = $totalPrice - $discountAmount;

                    // Mã đơn hàng duy nhất
                    $code = 'STEPVIET' . strtoupper(uniqid());

                    Order::create([
                        'code' => $code,
                        'user_id' => $userId,
                        'discount_id' => $discountId,
                        'payment_id' => $paymentId ?? 1,
                        'payment_status' => $paymentStatus,
                        'status' => $status,
                        'total_price' => $totalPrice,
                        'total_after_discount' => $discountId ? $finalPrice : null,
                        'recipient_name' => $faker->name(),
                        'recipient_phone' => $faker->phoneNumber(),
                        'shipping_address' => $faker->address(),
                        'note' => $faker->optional()->sentence(),
                        'tracking_code' => $faker->optional()->regexify('[A-Z]{2}[0-9]{8}VN'),
                        'created_at' => $createdAt,
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
