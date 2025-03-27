<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            1 => ['name' => 'Nam', 'slug' => 'nam', 'image' => 'images/categories/Men.jpg'],
            2 => ['name' => 'Nữ', 'slug' => 'nu', 'image' => 'images/categories/Women.jpg'],
        ];

        foreach ($categories as $id => $data) {
            Category::updateOrCreate(
                ['id' => $id],
                array_merge($data, [
                    'parent_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $subCategories = [
            3  => ['name' => 'Yêu thích Nam', 'parent_id' => 1, 'slug' => 'yeu-thich-nam', 'image' =>'images/categories/Kids.jpg'],
            4  => ['name' => 'Nổi bật Nam', 'parent_id' => 1, 'slug' => 'noi-bat-nam'],
            5  => ['name' => 'Bán chạy Nam', 'parent_id' => 1, 'slug' => 'ban-chay-nam'],
            6  => ['name' => 'Yêu thích Nữ', 'parent_id' => 2, 'slug' => 'yeu-thich-nu'],
            7  => ['name' => 'Nổi bật Nữ', 'parent_id' => 2, 'slug' => 'noi-bat-nu'],
            8  => ['name' => 'Bán chạy Nữ', 'parent_id' => 2, 'slug' => 'ban-chay-nu'],
        ];

        foreach ($subCategories as $id => $data) {
            Category::updateOrCreate(
                ['id' => $id],
                array_merge($data, [
                    'image' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
