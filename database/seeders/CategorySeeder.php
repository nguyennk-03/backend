<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 3; $i++) {
            $category = Category::create([
                'name' => $faker->word,
                'image_url' => Category::getRandomImage(),
                'slug' => Str::slug($faker->company),
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            for ($j = 0; $j < 3; $j++) {
                Category::create([
                    'name' => $faker->word,
                    'slug' => Str::slug($faker->company),
                    'image_url' => $faker->imageUrl(200, 200, 'fashion'),
                    'parent_id' => $category->id, 
                ]);
            }
        }
    }
}

