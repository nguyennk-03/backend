<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $categoryIds = Category::pluck('id')->toArray();
        $brandIds = Brand::pluck('id')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            Product::create([
                'name' => $faker->company,
                'slug' => $faker->slug,
                'description' => $faker->paragraph,
                'price' => $faker->randomFloat(2, 1000000, 10000000),
                'image_url' => Product::getRandomImage(),
                'category_id' => $faker->randomElement($categoryIds),
                'brand_id' => $faker->randomElement($brandIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
