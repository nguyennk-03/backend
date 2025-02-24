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
                'slug' => $faker->slug,
                'description' => $faker->sentence,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

