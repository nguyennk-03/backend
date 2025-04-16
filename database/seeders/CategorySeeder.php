<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Nam', 'image' => 'images/categories/Men.jpg', 'status' => 1],
            ['name' => 'Nữ', 'image' => 'images/categories/Women.jpg', 'status' => 1],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}