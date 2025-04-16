<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Tạo 2 biến thể cho mỗi sản phẩm (49 sản phẩm x 2 = 98 biến thể)
        foreach (range(1, 49) as $productId) {
            foreach (range(1, 2) as $variant) {
                $price = $faker->randomFloat(2, 500000, 3000000);
                $discount_percent = $faker->numberBetween(0, 30);
                $discounted_price = $discount_percent ? $price * (1 - $discount_percent / 100) : null;

                ProductVariant::create([
                    'product_id' => $productId,
                    'size_id' => $faker->numberBetween(1, 12),
                    'color_id' => $faker->numberBetween(1, 10),
                    'price' => $price,
                    'discount_percent' => $discount_percent,
                    'discounted_price' => $discounted_price,
                    'stock_quantity' => $faker->numberBetween(5, 50),
                    'sold' => $faker->numberBetween(0, 20),
                ]);
            }
        }
    }
}