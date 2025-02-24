<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\ProductVariant;
use Faker\Factory as Faker;

class ImageSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $productVariantIds = ProductVariant::pluck('id')->toArray();

        for ($i = 1; $i <= 30; $i++) {
            Image::create([
                'product_variant_id' => $faker->randomElement($productVariantIds),
                'image_url' => $faker->imageUrl(400, 400, 'fashion'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
