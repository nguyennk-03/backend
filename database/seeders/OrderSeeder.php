<?php

namespace Database\Seeders;

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
            // Mỗi user có thể có từ 1-3 đơn hàng
            $orderCount = rand(1, 3);

            for ($i = 0; $i < $orderCount; $i++) {
                $totalPrice = rand(5000000, 10000000); 
                $status = $faker->randomElement(['pending', 'completed', 'canceled']);
                $discountId = $faker->optional(0.5)->randomElement($discountIds);
                $paymentId = $faker->optional(0.8)->randomElement($paymentIds);

                Order::create([
                    'user_id' => $userId,
                    'discount_id' => $discountId,
                    'payment_id' => $paymentId,
                    'status' => $status,
                    'total_price' => $totalPrice,
                ]);
            }
        }
    }
}