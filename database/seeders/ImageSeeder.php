<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Image;
use App\Models\Product;

class ImageSeeder extends Seeder
{
    public function run()
    {
        // Lấy tất cả file ảnh trong thư mục public/images/products
        $imageDir = public_path('images/products');
        $imageFiles = File::files($imageDir);

        // Lấy tất cả các product variants
        $variants = Product::all();

        // Kiểm tra xem có đủ ảnh để gán cho các biến thể không
        if (count($imageFiles) < count($variants)) {
            echo "Không đủ ảnh để gán cho tất cả biến thể!";
            return;
        }

        foreach ($variants as $index => $variant) {
            if (isset($imageFiles[$index])) {
                $file = $imageFiles[$index];
                $imagePath = 'images/products/' . $file->getFilename();

                // Gán ảnh cho biến thể, ảnh đầu tiên là ảnh chính (is_main = true)
                Image::create([
                    'product_id' => $variant->id,
                    'image'       => $imagePath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
