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
        $faker = Faker::create();

        // Tạo danh mục cha
        $categories = [
            1 => ['name' => 'Nam', 'slug' => 'nam', 'image_url' => 'images/categories/Men.jpg'],
            2 => ['name' => 'Nữ', 'slug' => 'nu', 'image_url' => 'images/categories/Women.jpg'],
            3 => ['name' => 'Trẻ Em', 'slug' => 'tre-em', 'image_url' => 'images/categories/Kids.jpg'],
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

        // Tạo danh mục con
        $subCategories = [
            4  => ['name' => 'Yêu thích', 'parent_id' => 1, 'slug' => 'yeu-thich-nam'],
            5  => ['name' => 'Nổi bật', 'parent_id' => 1, 'slug' => 'noi-bat-nam'],
            6  => ['name' => 'Bán chạy', 'parent_id' => 1, 'slug' => 'ban-chay-nam'],
            7  => ['name' => 'Yêu thích', 'parent_id' => 2, 'slug' => 'yeu-thich-nu'],
            8  => ['name' => 'Nổi bật', 'parent_id' => 2, 'slug' => 'noi-bat-nu'],
            9  => ['name' => 'Bán chạy', 'parent_id' => 2, 'slug' => 'ban-chay-nu'],
            10 => ['name' => 'Yêu thích', 'parent_id' => 3, 'slug' => 'yeu-thich-tre-em'],
            11 => ['name' => 'Nổi bật', 'parent_id' => 3, 'slug' => 'noi-bat-tre-em'],
            12 => ['name' => 'Bán chạy', 'parent_id' => 3, 'slug' => 'ban-chay-tre-em'],
        ];

        foreach ($subCategories as $id => $data) {
            Category::updateOrCreate(
                ['id' => $id],
                array_merge($data, [
                    'image_url' => $faker->imageUrl(200, 200, 'fashion'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
