<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Discount;
use App\Models\ProductDiscount;
use Illuminate\Support\Facades\DB;


class ProductDiscountSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $discounts = Discount::all();
        if ($products->isEmpty() || $discounts->isEmpty()) {
            return;
        }

        foreach ($products as $product) {
            $randomDiscounts = $discounts->random(rand(1, 2)); // Mỗi sản phẩm có thể có 1-2 mã giảm giá

            foreach ($randomDiscounts as $discount) {
                DB::table('product_discounts')->insert([
                    'product_id' => $product->id,
                    'discount_id' => $discount->id,
                ]);
            }
        }
    }
}
