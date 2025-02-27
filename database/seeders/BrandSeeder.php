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
                'logo' => Brand::getRandomImage(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
