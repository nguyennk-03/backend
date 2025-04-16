<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DiscountSeeder extends Seeder
{
    /**
     * Chạy seeder cho cơ sở dữ liệu.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 20; $i++) {
            $discountType = $faker->randomElement(['fixed', 'percentage']);
            Discount::create([
                'name' => $faker->sentence(3), // Added: Tên mã giảm giá
                'code' => strtoupper($faker->unique()->bothify('DISCOUNT##??')), // Đảm bảo code là duy nhất
                'discount_type' => $discountType,
                'value' => $discountType == 'fixed'
                    ? $faker->numberBetween(10000, 100000)
                    : $faker->randomFloat(2, 5, 40),
                'min_order_amount' => $faker->numberBetween(50000, 500000), // Added: Giá trị đơn hàng tối thiểu
                'start_date' => $faker->dateTimeBetween('-1 month', 'now'),
                'end_date' => $faker->dateTimeBetween('now', '+3 month'),
                'is_active' => $faker->boolean(80), // Added: 80% cơ hội là true
                'usage_limit' => $faker->optional(0.7)->numberBetween(10, 100), // Added: 70% có giới hạn sử dụng
                'used_count' => $faker->numberBetween(0, 10), // Added: Số lần đã sử dụng
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}