<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Faker\Factory as Faker;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $productIds = Product::pluck('id')->toArray();
        $sizeIds = Size::pluck('id')->toArray();
        $colorIds = Color::pluck('id')->toArray();

        // Đảm bảo mỗi sản phẩm có ít nhất một biến thể
        foreach ($productIds as $productId) {
            ProductVariant::create([
                'product_id' => $productId,
                'size_id' => $faker->randomElement($sizeIds),
                'color_id' => $faker->randomElement($colorIds),
                'stock' => $faker->numberBetween(10, 100),
                'sold' => $faker->numberBetween(0, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Thêm các biến thể ngẫu nhiên
        for ($i = 1; $i <= 50; $i++) {
            ProductVariant::create([
                'product_id' => $faker->randomElement($productIds),
                'size_id' => $faker->randomElement($sizeIds),
                'color_id' => $faker->randomElement($colorIds),
                'stock' => $faker->numberBetween(10, 100),
                'sold' => $faker->numberBetween(0, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
