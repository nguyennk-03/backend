<?php
namespace Database\Seeders;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Discount;
use App\Models\Payment;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $userIds = User::pluck('id')->toArray();
        $discountIds = Discount::pluck('id')->toArray();
        $paymentIds = Payment::pluck('id')->toArray();

        foreach ($userIds as $userId) {
            // Mỗi user có thể có từ 1-10 đơn hàng
            $orderCount = rand(1, 10);

            for ($i = 0; $i < $orderCount; $i++) {
                $totalPrice = $faker->randomFloat(2, 500000, 10000000); // VD: 500,000 - 10,000,000
                $discountId = $faker->optional(0.5)->randomElement($discountIds);
                $paymentId = $faker->optional(0.8)->randomElement($paymentIds);
                $status = $faker->randomElement(array_column(OrderStatusEnum::cases(), 'value'));
                $paymentStatus = $faker->randomElement(array_column(PaymentStatusEnum::cases(), 'value'));

                $discountAmount = $discountId ? $totalPrice * (rand(5, 20) / 100) : 0;
                $finalPrice = $totalPrice - $discountAmount;

                Order::create([
                    'code' => 'STEPVIET' . strtoupper($faker->bothify('#####')),
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
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
