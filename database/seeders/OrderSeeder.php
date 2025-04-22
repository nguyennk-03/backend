<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use App\Models\User;
use App\Models\Discount;
use App\Models\Payment;
use Faker\Factory;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $userIds = User::pluck('id')->toArray();
        $discountIds = Discount::pluck('id')->toArray();
        $paymentIds = Payment::pluck('id')->toArray();

        // Tính từ 12/2024 đến tháng hiện tại
        $start = new \DateTime('2024-12-01');
        $end = new \DateTime(); // thời điểm hiện tại
        $months = [];

        while ($start <= $end) {
            $months[] = $start->format('Y-m'); // lưu lại năm-tháng
            $start->modify('+1 month');
        }

        foreach ($userIds as $userId) {
            foreach ($months as $month) {
                for ($i = 0; $i < 10; $i++) {
                    // Ngày ngẫu nhiên trong tháng
                    $createdAt = $faker->dateTimeBetween("{$month}-01", "{$month}-28");

                    $totalPrice = $faker->randomFloat(2, 500000, 10000000);
                    $discountId = $faker->optional(0.5)->randomElement($discountIds);
                    $paymentId = $faker->optional(0.8)->randomElement($paymentIds);
                    $status = $faker->randomElement(array_column(OrderStatusEnum::cases(), 'value'));
                    $paymentStatus = $faker->randomElement(array_column(PaymentStatusEnum::cases(), 'value'));

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
                        'recipient_name' => $faker->name,
                        'recipient_phone' => $faker->phoneNumber,
                        'shipping_address' => $faker->address,
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
