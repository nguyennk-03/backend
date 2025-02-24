<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $orders = Order::all();
        $users = User::all();
        $paymentMethods = ['momo', 'vnpay', 'paypal', 'cod'];
        $paymentStatuses = ['pending', 'completed', 'failed'];

        for ($i = 1; $i <= 50; $i++) { 
            Payment::create([
                'order_id' => $orders->random()->id,
                'user_id' => $users->random()->id,
                'payment_method' => $faker->randomElement($paymentMethods),
                'amount' => $faker->randomFloat(2, 10, 1000),
                'status' => $faker->randomElement($paymentStatuses),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
