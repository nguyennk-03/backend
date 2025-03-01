<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;
use Faker\Factory as Faker;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 20; $i++) {
            $discountType = $faker->randomElement(['fixed', 'percentage']);
            Discount::create([
                'code' => strtoupper($faker->bothify('DISCOUNT##??')),
                'discount_type' => $discountType,
                'value' => $discountType == 'fixed'
                    ? $faker->numberBetween(10000, 100000) 
                    : $faker->randomFloat(2, 5, 40),
                'start_date' => $faker->dateTimeBetween('-1 month', 'now'),
                'end_date' => $faker->dateTimeBetween('now', '+3 month'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
