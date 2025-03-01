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
        $variantIds = ProductVariant::pluck('id')->toArray();

        foreach ($userIds as $userId) {
            // Mỗi user có từ 1-5 sản phẩm trong giỏ hàng
            $cartItems = rand(1, 5);
            
        for ($i = 0; $i < $cartItems; $i++) {
                $variantId = $faker->randomElement($variantIds);
                $quantity = rand(1, 3);
                $price = ProductVariant::find($variantId)->product->price ?? 5000000;
                $totalPrice = $quantity * $price;

                 Cart::create([
                    'user_id' => $userId,
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
