<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\ProductVariant;
use App\Models\Product;
use Faker\Factory as Faker;

class ImageSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $productVariantIds = ProductVariant::pluck('id')->toArray();

        for ($i = 1; $i <= 30; $i++) {
            Image::create([
                'variant_id' => $faker->randomElement($productVariantIds),
                'image_url' => Product::getRandomImage(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
