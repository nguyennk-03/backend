<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $products = [
            ['name' => 'Nike Air Force 1', 'description' => 'Giày thể thao Nike Air Force 1 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike Air Max 90', 'description' => 'Giày thể thao Nike Air Max 90 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike Air Jordan 1', 'description' => 'Giày thể thao Nike Air Jordan 1 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike Dunk Low', 'description' => 'Giày thể thao Nike Dunk Low chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike Blazer Mid 77', 'description' => 'Giày thể thao Nike Blazer Mid 77 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike Air Max 97', 'description' => 'Giày thể thao Nike Air Max 97 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike React Infinity Run', 'description' => 'Giày thể thao Nike React Infinity Run chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike Pegasus 40', 'description' => 'Giày thể thao Nike Pegasus 40 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike ZoomX Vaporfly Next', 'description' => 'Giày thể thao Nike ZoomX Vaporfly Next chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike SB Dunk High', 'description' => 'Giày thể thao Nike SB Dunk High chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike Air Max', 'description' => 'Giày thể thao Nike Air Max chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Nike Air Max Plus (TN Air)', 'description' => 'Giày thể thao Nike Air Max Plus (TN Air) chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas Ultraboost 22', 'description' => 'Giày chạy bộ Adidas Ultraboost 22 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas NMD R1', 'description' => 'Giày chạy bộ Adidas NMD R1 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas Superstar', 'description' => 'Giày chạy bộ Adidas Superstar với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas Stan Smith', 'description' => 'Giày chạy bộ Adidas Stan Smith với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas Yeezy Boost 350 V2', 'description' => 'Giày chạy bộ Adidas Yeezy Boost 350 V2 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas Forum Low', 'description' => 'Giày chạy bộ Adidas Forum Low với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas Gazelle', 'description' => 'Giày chạy bộ Adidas Gazelle với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas Samba OG', 'description' => 'Giày chạy bộ Adidas Samba OG với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas ZX 750', 'description' => 'Giày chạy bộ Adidas ZX 750 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas Predator Freak', 'description' => 'Giày chạy bộ Adidas Predator Freak với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Adidas Harden Vol. 7', 'description' => 'Giày chạy bộ Adidas Harden Vol. 7 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Suede Classic', 'description' => 'Giày sneaker Puma Suede Classic phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma RS-X', 'description' => 'Giày sneaker Puma RS-X phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Future Rider', 'description' => 'Giày sneaker Puma Future Rider phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Cali', 'description' => 'Giày sneaker Puma Cali phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Clyde OG', 'description' => 'Giày sneaker Puma Clyde OG phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Ignite', 'description' => 'Giày sneaker Puma Ignite phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Deviate Nitro', 'description' => 'Giày sneaker Puma Deviate Nitro phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Smash V2', 'description' => 'Giày sneaker Puma Smash V2 phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Slipstream', 'description' => 'Giày sneaker Puma Slipstream phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Axelion', 'description' => 'Giày sneaker Puma Axelion phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Puma Ultra Ultimate', 'description' => 'Giày sneaker Puma Ultra Ultimate phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1],
            ['name' => 'Asics Gel-Kayano 30', 'description' => 'Giày chạy bộ Asics Gel-Kayano 30 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1],
            ['name' => 'Asics Gel-Nimbus 26', 'description' => 'Giày chạy bộ Asics Gel-Nimbus 26 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1],
            ['name' => 'Asics Novablast 4', 'description' => 'Giày chạy bộ Asics Novablast 4 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1],
            ['name' => 'Asics Metaspeed Sky+', 'description' => 'Giày chạy bộ Asics Metaspeed Sky+ với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1],
            ['name' => 'Asics GT-2000 12', 'description' => 'Giày chạy bộ Asics GT-2000 12 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata North Star Kick', 'description' => 'Giày da Bata North Star Kick phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata Power Xtreme', 'description' => 'Giày da Bata Power Xtreme phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata Red Label Casual', 'description' => 'Giày da Bata Red Label Casual phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata Weinbrenner Trek', 'description' => 'Giày da Bata Weinbrenner Trek phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata Comfit Walker', 'description' => 'Giày da Bata Comfit Walker phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata Bubblegummers Kids Sneakers', 'description' => 'Giày da Bata Bubblegummers Kids Sneakers phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata Tennis Classic', 'description' => 'Giày da Bata Tennis Classic phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata Ambassador Formal', 'description' => 'Giày da Bata Ambassador Formal phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata School Shoes (B.First)', 'description' => 'Giày da Bata School Shoes (B.First) phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
            ['name' => 'Bata Heritage Hotshot', 'description' => 'Giày da Bata Heritage Hotshot phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'stock_quantity' => $faker->numberBetween(20, 100),
                'sold' => $faker->numberBetween(0, 50),
                'hot' => $faker->numberBetween(0, 3),
            ]));
        }
    }
}
