<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 5; $i++) {
            Category::create([
                'name' => $faker->word,
                'image_url' => Category::getRandomImage(),
                'slug' => $faker->slug,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

