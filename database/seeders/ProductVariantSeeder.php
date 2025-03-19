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

        for ($i = 1; $i <= 30; $i++) {
            ProductVariant::create([
                'product_id' => $faker->randomElement($productIds), 
                'size_id' => $faker->randomElement($sizeIds),       
                'color_id' => $faker->randomElement($colorIds),
                'stock' => $faker->numberBetween(10, 100),
                'sold' => $faker->numberBetween(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
