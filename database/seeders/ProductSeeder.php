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
            ['name' => 'Adidas Samba OG Shoes  ‘White’ IE3675', 'description' => 'Giày chạy bộ Adidas Samba OG Shoes  ‘White’ IE3675 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 26500.00, 'image' => 'images/products/adidas-1.png', 'size_id' => rand(1, 9), 'color_id' => rand(1, 5)],
            ['name' => 'Adidas Originals Samba OG Crystal Sand JI3185', 'description' => 'Giày chạy bộ Adidas Originals Samba OG Crystal Sand JI3185 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 32000.00, 'image' => 'images/products/adidas-2.png', 'size_id' => rand(1, 9), 'color_id' => 5],
            ['name' => 'Adidas Duramo SL2 Nam - IH8218', 'description' => 'Giày chạy bộ Adidas Duramo SL2 Nam - IH8218 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 17000.00, 'image' => 'images/products/adidas-3.png', 'size_id' => rand(1, 9), 'color_id' => 4],
            ['name' => 'Adidas Adizero Adios Pro 4', 'description' => 'Giày chạy bộ Adidas Adizero Adios Pro 4 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 65000.00, 'image' => 'images/products/adidas-4.png', 'size_id' => rand(1, 9), 'color_id' => 5],
            ['name' => 'Adidas Duramo Speed 2 ‘White’ IF9393', 'description' => 'Giày chạy bộ Adidas Duramo Speed 2 ‘White’ IF9393 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 29000.00, 'image' => 'images/products/adidas-5.png', 'size_id' => rand(1, 9), 'color_id' => 5],
            ['name' => 'Adidas Superstar', 'description' => 'Giày chạy bộ Adidas Superstar với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 27000.00, 'image' => 'images/products/adidas-6.png', 'size_id' => rand(1, 9), 'color_id' => 3], 
            ['name' => 'Adidas Gazelle Indoor x Liberty London', 'description' => 'Giày chạy bộ Adidas Gazelle Indoor x Liberty London với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 29000.00, 'image' => 'images/products/adidas-7.png', 'size_id' => rand(1, 9), 'color_id' => 5], 
            ['name' => 'Adidas Gazelle Indoor', 'description' => 'Giày chạy bộ Adidas Gazelle Indoor với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 36000.00, 'image' => 'images/products/adidas-8.png', 'size_id' => rand(1, 9), 'color_id' => 3], 
            ['name' => 'Adidas Dropset 3', 'description' => 'Giày chạy bộ Adidas Dropset 3 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 28000.00, 'image' => 'images/products/adidas-9.png', 'size_id' => rand(1, 9), 'color_id' => rand(1, 5)], 
            ['name' => 'Adidas Lightblaze Shoes', 'description' => 'Giày chạy bộ Adidas Lightblaze Shoes với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/adidas-10.png', 'size_id' => rand(1, 9), 'color_id' => 5], 
            ['name' => 'Adidas SSTR V Bape', 'description' => 'Giày chạy bộ Adidas SSTR V Bape với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 33000.00, 'image' => 'images/products/adidas-11.png', 'size_id' => rand(1, 9), 'color_id' => 5],
            ['name' => 'Nike Air Force 1 07 EasyOn', 'description' => 'Giày thể thao Nike Air Force 1 07 EasyOn chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 33000.00, 'image' => 'images/products/nike-1.png', 'size_id' => rand(1, 9), 'color_id' => 5],
            ['name' => 'Nike Nike Air Force 1 07 Mid', 'description' => 'Giày thể thao Nike Nike Air Force 1 07 Mid chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 33000.00, 'image' => 'images/products/nike-2.png', 'size_id' => rand(1, 9), 'color_id' => 5], 
            ['name' => 'Nike Court Vision Low', 'description' => 'Giày thể thao Nike Court Vision Low chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 21000.00, 'image' => 'images/products/nike-3.png', 'size_id' => rand(1, 9), 'color_id' => 5], 
            ['name' => 'Nike Killshot 2 Leather', 'description' => 'Giày thể thao Nike Killshot 2 Leather chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 23000.00, 'image' => 'images/products/nike-4.png', 'size_id' => rand(1, 9), 'color_id' => 5], 
            ['name' => 'Nike Nike Air Force 1 07', 'description' => 'Giày thể thao Nike Nike Air Force 1 07 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 30000.00, 'image' => 'images/products/nike-5.png', 'size_id' => rand(1, 9), 'color_id' => 5], 
            ['name' => 'Nike Air Jordan 1 Mid', 'description' => 'Giày thể thao Nike Air Jordan 1 Mid chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 27000.00, 'image' => 'images/products/nike-6.png', 'size_id' => rand(1, 9), 'color_id' => 1], 
            ['name' => 'Nike Air Jordan 1 Low', 'description' => 'Giày thể thao Nike Air Jordan 1 Low chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 33000.00, 'image' => 'images/products/nike-7.png', 'size_id' => rand(1, 9), 'color_id' => 3],
            ['name' => 'Nike Court Vision Alta', 'description' => 'Giày thể thao Nike Court Vision Alta chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 24000.00, 'image' => 'images/products/nike-8.png', 'size_id' => rand(1, 9), 'color_id' => 3], 
            ['name' => 'Nike Blazer Mid 77 By You', 'description' => 'Giày thể thao Nike Blazer Mid 77 By You chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 39000.00, 'image' => 'images/products/nike-9.png', 'size_id' => rand(1, 9), 'color_id' =>1], 
            ['name' => 'Nike Air Jordan Legacy 312 Low', 'description' => 'Giày thể thao Nike Air Jordan Legacy 312 Low chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 33000.00, 'image' => 'images/products/nike-10.png', 'size_id' => rand(1, 9), 'color_id' => 3], 
            ['name' => 'Nike Blazer Mid 77', 'description' => 'Giày thể thao Nike Blazer Mid 77 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 30000.00, 'image' => 'images/products/nike-11.png', 'size_id' => rand(1, 9), 'color_id' => 5], 
            ['name' => 'Nike C1TY', 'description' => 'Giày thể thao Nike C1TY chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 23000.00, 'image' => 'images/products/nike-12.png', 'size_id' => rand(1, 9), 'color_id' => 4], 
            ['name' => 'Nike Vaporfly 4', 'description' => 'Giày thể thao Nike Vaporfly 4 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 65000.00, 'image' => 'images/products/nike-13.png', 'size_id' => rand(1, 9), 'color_id' =>3], 
            ['name' => 'Nike Dunk High', 'description' => 'Giày thể thao Nike Dunk High chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 27000.00, 'image' => 'images/products/nike-14.png', 'size_id' => rand(1, 9), 'color_id' => 1], 
            ['name' => 'Nike Air Max 90 SE', 'description' => 'Giày thể thao Nike Air Max 90 SE chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 33000.00, 'image' => 'images/products/nike-15.png', 'size_id' => rand(1, 9), 'color_id' => 1], 
            ['name' => 'Nike Air Max Plus ', 'description' => 'Giày thể thao Nike Air Max Plus chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/nike-16.png', 'size_id' => rand(1, 9), 'color_id' => 2],
            ['name' => 'PUMA x ONE PIECE Suede Straw Hat Luffy', 'description' => 'Giày sneaker PUMA x ONE PIECE Suede Straw Hat Luffy phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 20000.00, 'image' => 'images/products/puma-1.png', 'size_id' => rand(1, 9), 'color_id' => 5], 
            ['name' => 'Puma Palermo', 'description' => 'Giày sneaker Puma Palermo phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/puma-2.png', 'size_id' => rand(1, 9), 'color_id' => 4], 
            ['name' => 'Puma Palermo LaModa Vintage', 'description' => 'Giày sneaker Puma Palermo LaModa Vintage phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 30000.00, 'image' => 'images/products/puma-3.png', 'size_id' => rand(1, 9), 'color_id' => 3],
            ['name' => 'Puma Palermo Vintage Update', 'description' => 'Giày sneaker Puma Palermo Vintage Update phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/puma-4.png', 'size_id' => rand(1, 9), 'color_id' => 2], 
            ['name' => 'Puma Clyde INT', 'description' => 'Giày sneaker Puma Clyde INT phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 23000.00, 'image' => 'images/products/puma-5.png', 'size_id' => rand(1, 9), 'color_id' => 5], 
            ['name' => 'Puma PUMA x HELLO KITTY® AND FRIENDS Easy Rider Goth', 'description' => 'Giày sneaker Puma PUMA x HELLO KITTY® AND FRIENDS Easy Rider Goth phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 35000.00, 'image' => 'images/products/puma-6.png', 'size_id' => rand(1, 9), 'color_id' => 4],
            ['name' => 'Puma PUMA x HARRY POTTER Easy Rider', 'description' => 'Giày sneaker Puma PUMA x HARRY POTTER Easy Rider phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 38000.00, 'image' => 'images/products/puma-7.png', 'size_id' => rand(1, 9), 'color_id' => 4], 
            ['name' => 'Puma Sneakers Suede Classic', 'description' => 'Giày sneaker Puma Sneakers Suede Classic phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 30000.00, 'image' => 'images/products/puma-8.png', 'size_id' => rand(1, 9), 'color_id' => 4], 
            ['name' => 'Puma Chaussures Rebond V6 Mid', 'description' => 'Giày sneaker Puma Chaussures Rebond V6 Mid phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/puma-9.png', 'size_id' => rand(1, 9), 'color_id' => 3], 
            ['name' => 'Puma Sneakers PUMA Dribble', 'description' => 'Giày sneaker Puma Sneakers PUMA Dribble phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/puma-10.png', 'size_id' => rand(1, 9), 'color_id' => 2], 
            ['name' => 'Puma ST MILER', 'description' => 'Giày sneaker Puma ST MILER phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/puma-11.png', 'size_id' => rand(1, 9), 'color_id' => 2], 
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
