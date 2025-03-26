<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\ProductVariant;

class ImageSeeder extends Seeder
{
    public function run()
    {
        $variants = ProductVariant::all();
        $imageIndex = 1;

        foreach ($variants as $variant) {
// Tạo đường dẫn ảnh theo thứ tự
            $imagePath = "images/products/{$variant->product->brand->name}-{$imageIndex}.png";

            if (!file_exists(public_path($imagePath))) {
                continue; // Bỏ qua nếu ảnh không tồn tại
            }

            Image::create([
                'variant_id' => $variant->id,
                'image' => $imagePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $imageIndex++;
        }
    }
}
