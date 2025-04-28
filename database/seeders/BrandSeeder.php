<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['name' => 'Adidas', 'logo' => 'images/brands/adidas.png', 'status' => 1],
            ['name' => 'Nike', 'logo' => 'images/brands/nike.png', 'status' => 1],
            ['name' => 'Puma', 'logo' => 'images/brands/puma.png', 'status' => 1],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}