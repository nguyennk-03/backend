<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Faker\Factory as Faker;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 5; $i++) {
            Brand::create([
                'name' => $faker->company,
                'slug' => $faker->slug,
                'description' => $faker->sentence,
                'logo' => $faker->imageUrl(200, 200, 'business'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
