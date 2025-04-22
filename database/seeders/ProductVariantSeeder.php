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
        $products = Product::with('brand')->get();
        $brandPriceRanges = [
            'Adidas' => ['min' => 1000000, 'max' => 8000000],
            'Asics'  => ['min' => 1200000, 'max' => 5000000],
            'Bata'   => ['min' => 500000,  'max' => 1500000],
            'Nike'   => ['min' => 1200000, 'max' => 10000000],
            'Puma'   => ['min' => 900000,  'max' => 4000000],
        ];
        $discountOptions = [0, 10, 15, 20];

        foreach ($products as $product) {
            $size = $sizes->random();
            $color = $colors->random();
           
            // Lấy khoảng giá theo hãng
            $brandName = $product->brand->name ?? 'Nike'; // fallback nếu thiếu
            $range = $brandPriceRanges[$brandName] ?? ['min' => 1000000, 'max' => 5000000];

            // Tính giá và giảm giá
            $price = rand($range['min'], $range['max']);
            $discountPercent = $discountOptions[array_rand($discountOptions)];
            $discountedPrice = round($price * (1 - $discountPercent / 100), -3);

            // Random tồn kho và số bán
            $stock_quantity = rand(20, 100);
            $sold = rand(0, (int)($stock_quantity / 2));

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
