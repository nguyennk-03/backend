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
        $orders = Order::all(); // Lấy tất cả các đơn hàng
        $variants = ProductVariant::all(); // Lấy tất cả các biến thể sản phẩm

        if ($orders->isEmpty() || $variants->isEmpty()) {
            return; // Nếu không có dữ liệu nào, dừng lại
        }

        foreach ($orders as $order) {
            $randomVariants = $variants->random(rand(1, 3)); // Chọn ngẫu nhiên 1-3 biến thể sản phẩm cho mỗi đơn hàng

            foreach ($randomVariants as $variant) {
                // Tính toán giá trị price = (giá trị đơn hàng * số lượng)
                $quantity = rand(1, 5); // Số lượng ngẫu nhiên cho mỗi sản phẩm trong đơn hàng
                $price = $order->total_price * $quantity; // Tính giá trị price

                // Tạo bản ghi OrderItem với price đã tính toán
                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $price, // Sử dụng giá đã tính
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
