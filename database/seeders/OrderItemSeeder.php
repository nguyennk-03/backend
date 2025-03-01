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
        $orders = Order::all();
        $variants = ProductVariant::all();

        if ($orders->isEmpty() || $variants->isEmpty()) {
            return;
        }
        foreach ($orders as $order) {
            $randomVariants = $variants->random(rand(1, 3)); 

            foreach ($randomVariants as $variant) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $variant->id,
                    'quantity' => rand(1, 5),
                    'price' => $variant->product->price, 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

