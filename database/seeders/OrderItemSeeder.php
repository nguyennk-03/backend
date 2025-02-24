<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\ProductVariant;
use Faker\Factory as Faker;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Lấy danh sách Order ID và Product Variant ID có sẵn
        $orderIds = Order::pluck('id')->toArray();
        $productVariantIds = ProductVariant::pluck('id')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            OrderItem::create([
                'order_id' => $faker->randomElement($orderIds),
                'product_variant_id' => $faker->randomElement($productVariantIds),
                'quantity' => $faker->numberBetween(1, 5),
                'price' => $faker->randomFloat(2, 1000000, 50000000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

