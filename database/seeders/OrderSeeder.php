<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 30; $i++) {
            Order::create([
                'user_id' => $faker->numberBetween(1, 30),
                'status' => $faker->randomElement(['pending', 'completed', 'canceled', 'shipped']),
                'total_price' => $faker->randomFloat(2, 1000000, 10000000),
                'payment_status' => $faker->randomElement(['unpaid', 'paid', 'failed']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}