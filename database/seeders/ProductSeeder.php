<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 50; $i++) {
            Product::create([
                'name' => $faker->word,
                'slug' => $faker->slug,
                'description' => $faker->paragraph,
                'price' => $faker->randomFloat(2, 1000000, 10000000),
                'stock' => $faker->numberBetween(1, 100),
                'category_id' => $faker->numberBetween(1, 10),
                'brand_id' => $faker->numberBetween(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
