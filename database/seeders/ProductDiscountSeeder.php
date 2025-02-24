<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Discount;
use App\Models\ProductDiscount;

class ProductDiscountSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $discounts = Discount::all();

        foreach ($products as $product) {
            // Mỗi sản phẩm có thể có 0-2 mã giảm giá
            $appliedDiscounts = $discounts->random(rand(0, 2));

            foreach ($appliedDiscounts as $discount) {
                ProductDiscount::create([
                    'product_id' => $product->id,
                    'discount_id' => $discount->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
