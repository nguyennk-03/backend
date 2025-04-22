<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        $sizes = [38, 39, 40]; // Example sizes
        $colors = ['Black', 'White', 'Red']; // Example colors
        $products = Product::all(); // Fetch all products using the Product model

        foreach ($products as $index => $product) {
            $brand = $this->getBrandFromProductName($product->name); // Extract brand from product name

            // Create 3 variants per product (one for each size and color combo)
            foreach ($sizes as $sizeIndex => $size) {
                $color = $colors[$sizeIndex];
                $discountPercent = rand(0, 30); // Random discount between 0% and 30%
                $originalPrice = $product->price;
                $discountedPrice = $discountPercent > 0 ? $originalPrice * (1 - $discountPercent / 100) : null;

                ProductVariant::create([
                    'product_id' => $product->id,
                    'image' => "images/products/{$brand}-" . ($index + 1) . "-{$color}-{$size}.png", 
                    'size' => $size,
                    'color' => $color,
                    'discount_percent' => $discountPercent,
                    'discounted_price' => $discountedPrice,
                    'stock_quantity' => rand(10, 100), // Random stock between 10 and 100
                    'sold' => rand(0, 20), // Random sold quantity between 0 and 20
                ]);
            }
        }
    }

    private function getBrandFromProductName($name)
    {
        if (stripos($name, 'Nike') !== false) {
            return 'nike';
        } elseif (stripos($name, 'Adidas') !== false) {
            return 'adidas';
        } elseif (stripos($name, 'Asics') !== false) {
            return 'asics';
        } elseif (stripos($name, 'Bata') !== false) {
            return 'bata';
        } elseif (stripos($name, 'Puma') !== false) {
            return 'puma';
        }
        return 'unknown';
    }
}
