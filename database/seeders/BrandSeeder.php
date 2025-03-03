<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['id' => 1, 'name' => 'Adidas', 'slug' => 'adidas', 'logo' => 'images/brands/adidas.png'],
            ['id' => 2, 'name' => 'Asics', 'slug' => 'asics', 'logo' => 'images/brands/asics.png'],
            ['id' => 3, 'name' => 'Bata', 'slug' => 'bata', 'logo' => 'images/brands/bata.png'],
            ['id' => 4, 'name' => 'Nike', 'slug' => 'nike', 'logo' => 'images/brands/nike.png'],
            ['id' => 5, 'name' => 'Puma', 'slug' => 'puma', 'logo' => 'images/brands/puma.png'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['id' => $brand['id']],  
                [
                    'name' => $brand['name'],
                    'slug' => Str::slug($brand['name']),
                    'logo' => $brand['logo'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
