<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;
use App\Models\ProductVariant;
use Faker\Factory as Faker;

class CartSeeder extends Seeder
{
   public function run()
    {
        $faker = Faker::create();

        // Lấy danh sách ID có sẵn
        $userIds = User::pluck('id')->toArray();
        $productIds = ProductVariant::pluck('id')->toArray();

        for ($i = 1; $i <= 30; $i++) {
            Cart::create([
                'user_id' => $faker->randomElement($userIds),
                'product_variant_id' => $faker->randomElement($productIds),
                'quantity' => $faker->numberBetween(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
