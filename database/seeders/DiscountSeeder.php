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
            // Ánh xạ discount_type
            $discountTypeLabel = $faker->randomElement(['fixed', 'percentage']);
            $discountType = $discountTypeLabel === 'fixed' ? 1 : 0;

            Discount::create([
                'name' => $faker->sentence(3),
                'code' => strtoupper($faker->unique()->bothify('DISCOUNT##??')),
                'discount_type' => $discountType,
                'value' => $discountType === 1
                    ? $faker->numberBetween(10000, 100000)
                    : $faker->randomFloat(2, 5, 40),
                'min_order_amount' => $faker->numberBetween(50000, 500000),
                'start_date' => $faker->dateTimeBetween('-1 month', 'now'),
                'end_date' => $faker->dateTimeBetween('now', '+3 month'),
                'is_active' => $faker->boolean(80),
                'usage_limit' => $faker->optional(0.7)->numberBetween(10, 100),
                'used_count' => $faker->numberBetween(0, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
