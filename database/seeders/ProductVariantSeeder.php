<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = Size::all();
        $colors = Color::all();
        $products = Product::all();

        foreach ($products as $product) {
            $size = $sizes->random();
            $color = $colors->random();

            $price = rand(1200000, 3000000);
            $discountOptions = [0, 10, 15, 20];
            $discountPercent = $discountOptions[array_rand($discountOptions)];
            $discountedPrice = round($price * (1 - $discountPercent / 100), -3);

            $stock_quantity = rand(20, 100);
            $sold = rand(0, $stock_quantity / 2);

            ProductVariant::create([
                'product_id'        => $product->id,
                'size_id'           => $size->id,
                'color_id'          => $color->id,
                'price'             => $price,
                'discount_percent'  => $discountPercent,
                'discounted_price'  => $discountedPrice,
                'stock_quantity'    => $stock_quantity,
                'sold'              => $sold,
            ]);
        }
    }
}
