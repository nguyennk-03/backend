<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Faker\Factory as Faker;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $users = User::all();
        $products = Product::all();

        for ($i = 1; $i <= 50; $i++) {
            Review::create([
                'user_id' => $users->random()->id,
                'variant_id' => $products->random()->id,
                'rating' => $faker->numberBetween(1, 5),
                'comment' => $faker->sentence(10),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
