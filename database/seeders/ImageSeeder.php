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
        $variants = ProductVariant::all();

        foreach ($variants as $variant) {
            Image::create([
                'variant_id' => $variant->id,
                'image' => Image::getRandomImage(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
