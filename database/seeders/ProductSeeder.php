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
            ['name' => 'Nike Air Force 1', 'description' => 'Giày thể thao Nike Air Force 1 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 2695000, 'image' => 'images/products/nike-1.png'],
            ['name' => 'Nike Air Max 270', 'description' => 'Giày thể thao Nike Air Max 270 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 2940000, 'image' => 'images/products/nike-2.png'],
            ['name' => 'Nike Air Huarache', 'description' => 'Giày thể thao Nike Air Huarache chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 2450000, 'image' => 'images/products/nike-3.png'],
            ['name' => 'Nike Air Zoom Pegasus', 'description' => 'Giày thể thao Nike Air Zoom Pegasus chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 2940000, 'image' => 'images/products/nike-4.png'],
            ['name' => 'Nike React Element', 'description' => 'Giày thể thao Nike React Element chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 3185000, 'image' => 'images/products/nike-5.png'],
            ['name' => 'Nike Air Max 90', 'description' => 'Giày thể thao Nike Air Max 90 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 3185000, 'image' => 'images/products/nike-6.png'],
            ['name' => 'Nike Air Jordan 1', 'description' => 'Giày thể thao Nike Air Jordan 1 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 4165000, 'image' => 'images/products/nike-7.png'],
            ['name' => 'Nike Dunk Low', 'description' => 'Giày thể thao Nike Dunk Low chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 2817500, 'image' => 'images/products/nike-8.png'],
            ['name' => 'Nike Blazer Mid 77', 'description' => 'Giày thể thao Nike Blazer Mid 77 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 2572500, 'image' => 'images/products/nike-9.png'],
            ['name' => 'Nike Air Max 97', 'description' => 'Giày thể thao Nike Air Max 97 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 4410000, 'image' => 'images/products/nike-10.png'],
            ['name' => 'Nike React Infinity Run', 'description' => 'Giày thể thao Nike React Infinity Run chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 3920000, 'image' => 'images/products/nike-11.png'],
            ['name' => 'Nike Pegasus 40', 'description' => 'Giày thể thao Nike Pegasus 40 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 3430000, 'image' => 'images/products/nike-12.png'],
            ['name' => 'Nike ZoomX Vaporfly Next', 'description' => 'Giày thể thao Nike ZoomX Vaporfly Next chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 6125000, 'image' => 'images/products/nike-13.png'],
            ['name' => 'Nike SB Dunk High', 'description' => 'Giày thể thao Nike SB Dunk High chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 3062500, 'image' => 'images/products/nike-14.png'],
            ['name' => 'Nike Air Max', 'description' => 'Giày thể thao Nike Air Max chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 3675000, 'image' => 'images/products/nike-15.png'],
            ['name' => 'Nike Air Max Plus (TN Air)', 'description' => 'Giày thể thao Nike Air Max Plus (TN Air) chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 4287500, 'image' => 'images/products/nike-16.png'],
            ['name' => 'Adidas Ultraboost 22', 'description' => 'Giày chạy bộ Adidas Ultraboost 22 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 4655000, 'image' => 'images/products/adidas-1.png'],
            ['name' => 'Adidas NMD R1', 'description' => 'Giày chạy bộ Adidas NMD R1 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 3430000, 'image' => 'images/products/adidas-2.png'],
            ['name' => 'Adidas Superstar', 'description' => 'Giày chạy bộ Adidas Superstar với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 2450000, 'image' => 'images/products/adidas-3.png'],
            ['name' => 'Adidas Stan Smith', 'description' => 'Giày chạy bộ Adidas Stan Smith với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 2450000, 'image' => 'images/products/adidas-4.png'],
            ['name' => 'Adidas Yeezy Boost 350 V2', 'description' => 'Giày chạy bộ Adidas Yeezy Boost 350 V2 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 5635000, 'image' => 'images/products/adidas-5.png'],
            ['name' => 'Adidas Forum Low', 'description' => 'Giày chạy bộ Adidas Forum Low với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 2695000, 'image' => 'images/products/adidas-6.png'],
            ['name' => 'Adidas Gazelle', 'description' => 'Giày chạy bộ Adidas Gazelle với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 2450000, 'image' => 'images/products/adidas-7.png'],
            ['name' => 'Adidas Samba OG', 'description' => 'Giày chạy bộ Adidas Samba OG với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 2940000, 'image' => 'images/products/adidas-8.png'],
            ['name' => 'Adidas ZX 750', 'description' => 'Giày chạy bộ Adidas ZX 750 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 3185000, 'image' => 'images/products/adidas-9.png'],
            ['name' => 'Adidas Predator Freak', 'description' => 'Giày chạy bộ Adidas Predator Freak với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 3675000, 'image' => 'images/products/adidas-10.png'],
            ['name' => 'Adidas Harden Vol. 7', 'description' => 'Giày chạy bộ Adidas Harden Vol. 7 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 3920000, 'image' => 'images/products/adidas-11.png'],
            ['name' => 'Puma Suede Classic', 'description' => 'Giày sneaker Puma Suede Classic phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 1960000, 'image' => 'images/products/puma-1.png'],
            ['name' => 'Puma RS-X', 'description' => 'Giày sneaker Puma RS-X phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 2940000, 'image' => 'images/products/puma-2.png'],
            ['name' => 'Puma Future Rider', 'description' => 'Giày sneaker Puma Future Rider phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 2450000, 'image' => 'images/products/puma-3.png'],
            ['name' => 'Puma Cali', 'description' => 'Giày sneaker Puma Cali phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 2205000, 'image' => 'images/products/puma-4.png'],
            ['name' => 'Puma Clyde OG', 'description' => 'Giày sneaker Puma Clyde OG phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 2450000, 'image' => 'images/products/puma-5.png'],
            ['name' => 'Puma Ignite', 'description' => 'Giày sneaker Puma Ignite phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 2695000, 'image' => 'images/products/puma-6.png'],
            ['name' => 'Puma Deviate Nitro', 'description' => 'Giày sneaker Puma Deviate Nitro phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 3920000, 'image' => 'images/products/puma-7.png'],
            ['name' => 'Puma Smash V2', 'description' => 'Giày sneaker Puma Smash V2 phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 1470000, 'image' => 'images/products/puma-8.png'],
            ['name' => 'Puma Slipstream', 'description' => 'Giày sneaker Puma Slipstream phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 2450000, 'image' => 'images/products/puma-9.png'],
            ['name' => 'Puma Axelion', 'description' => 'Giày sneaker Puma Axelion phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 2205000, 'image' => 'images/products/puma-10.png'],
            ['name' => 'Puma Ultra Ultimate', 'description' => 'Giày sneaker Puma Ultra Ultimate phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 3430000, 'image' => 'images/products/puma-11.png'],
            ['name' => 'Asics Gel-Kayano 30', 'description' => 'Giày chạy bộ Asics Gel-Kayano 30 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 4410000, 'image' => 'images/products/asics-1.png'],
            ['name' => 'Asics Gel-Nimbus 26', 'description' => 'Giày chạy bộ Asics Gel-Nimbus 26 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 4900000, 'image' => 'images/products/asics-2.png'],
            ['name' => 'Asics Novablast 4', 'description' => 'Giày chạy bộ Asics Novablast 4 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 3675000, 'image' => 'images/products/asics-3.png'],
            ['name' => 'Asics Metaspeed Sky+', 'description' => 'Giày chạy bộ Asics Metaspeed Sky+ với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 6125000, 'image' => 'images/products/asics-4.png'],
            ['name' => 'Asics GT-2000 12', 'description' => 'Giày chạy bộ Asics GT-2000 12 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 3430000, 'image' => 'images/products/asics-5.png'],
            ['name' => 'Bata North Star Kick', 'description' => 'Giày da Bata North Star Kick phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 1225000, 'image' => 'images/products/bata-1.png'],
            ['name' => 'Bata Power Xtreme', 'description' => 'Giày da Bata Power Xtreme phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 1470000, 'image' => 'images/products/bata-2.png'],
            ['name' => 'Bata Red Label Casual', 'description' => 'Giày da Bata Red Label Casual phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 1347500, 'image' => 'images/products/bata-3.png'],
            ['name' => 'Bata Weinbrenner Trek', 'description' => 'Giày da Bata Weinbrenner Trek phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 1715000, 'image' => 'images/products/bata-4.png'],
            ['name' => 'Bata Comfit Walker', 'description' => 'Giày da Bata Comfit Walker phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 1470000, 'image' => 'images/products/bata-5.png'],
            ['name' => 'Bata Bubblegummers Kids Sneakers', 'description' => 'Giày da Bata Bubblegummers Kids Sneakers phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 980000, 'image' => 'images/products/bata-6.png'],
            ['name' => 'Bata Tennis Classic', 'description' => 'Giày da Bata Tennis Classic phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 1225000, 'image' => 'images/products/bata-7.png'],
            ['name' => 'Bata Ambassador Formal', 'description' => 'Giày da Bata Ambassador Formal phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 1592500, 'image' => 'images/products/bata-8.png'],
            ['name' => 'Bata School Shoes (B.First)', 'description' => 'Giày da Bata School Shoes (B.First) phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 1102500, 'image' => 'images/products/bata-9.png'],
            ['name' => 'Bata Heritage Hotshot', 'description' => 'Giày da Bata Heritage Hotshot phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 1347500, 'image' => 'images/products/bata-10.png'],
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
