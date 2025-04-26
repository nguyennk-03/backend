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

        // Danh sách màu sắc phổ biến cho từng thương hiệu
        $colors = [
            1 => ['Đen', 'Trắng', 'Xám', 'Xanh dương', 'Đỏ'], // Adidas (brand_id: 1)
            2 => ['Trắng', 'Đen', 'Xanh dương', 'Đỏ', 'Xám'], // Asics (brand_id: 2)
            3 => ['Đen', 'Nâu', 'Trắng', 'Hồng', 'Xanh dương'], // Bata (brand_id: 3)
            4 => ['Đen', 'Trắng', 'Đỏ', 'Xanh dương', 'Vàng'], // Nike (brand_id: 4)
            5 => ['Đen', 'Trắng', 'Xám', 'Xanh lá', 'Cam'], // Puma (brand_id: 5)
        ];

        $products = [
            ['name' => 'Nike Air Force 1', 'description' => 'Giày thể thao Nike Air Force 1 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 28000.00, 'image' => 'images/products/nike-1.png'], // 2,800,000 VND
            ['name' => 'Nike Air Max 270', 'description' => 'Giày thể thao Nike Air Max 270 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 30000.00, 'image' => 'images/products/nike-2.png'], // 3,000,000 VND
            ['name' => 'Nike Air Huarache', 'description' => 'Giày thể thao Nike Air Huarache chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 26000.00, 'image' => 'images/products/nike-3.png'], // 2,600,000 VND
            ['name' => 'Nike Air Zoom Pegasus', 'description' => 'Giày thể thao Nike Air Zoom Pegasus chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 31000.00, 'image' => 'images/products/nike-4.png'], // 3,100,000 VND
            ['name' => 'Nike React Element', 'description' => 'Giày thể thao Nike React Element chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 32000.00, 'image' => 'images/products/nike-5.png'], // 3,200,000 VND
            ['name' => 'Nike Air Max 90', 'description' => 'Giày thể thao Nike Air Max 90 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 32000.00, 'image' => 'images/products/nike-6.png'], // 3,200,000 VND
            ['name' => 'Nike Air Jordan 1', 'description' => 'Giày thể thao Nike Air Jordan 1 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 45000.00, 'image' => 'images/products/nike-7.png'], // 4,500,000 VND
            ['name' => 'Nike Dunk Low', 'description' => 'Giày thể thao Nike Dunk Low chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 29000.00, 'image' => 'images/products/nike-8.png'], // 2,900,000 VND
            ['name' => 'Nike Blazer Mid 77', 'description' => 'Giày thể thao Nike Blazer Mid 77 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 27000.00, 'image' => 'images/products/nike-9.png'], // 2,700,000 VND
            ['name' => 'Nike Air Max 97', 'description' => 'Giày thể thao Nike Air Max 97 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 46000.00, 'image' => 'images/products/nike-10.png'], // 4,600,000 VND
            ['name' => 'Nike React Infinity Run', 'description' => 'Giày thể thao Nike React Infinity Run chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 40000.00, 'image' => 'images/products/nike-11.png'], // 4,000,000 VND
            ['name' => 'Nike Pegasus 40', 'description' => 'Giày thể thao Nike Pegasus 40 chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 35000.00, 'image' => 'images/products/nike-12.png'], // 3,500,000 VND
            ['name' => 'Nike ZoomX Vaporfly Next', 'description' => 'Giày thể thao Nike ZoomX Vaporfly Next chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 53000.00, 'image' => 'images/products/nike-13.png'], // 5,300,000 VND
            ['name' => 'Nike SB Dunk High', 'description' => 'Giày thể thao Nike SB Dunk High chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 31000.00, 'image' => 'images/products/nike-14.png'], // 3,100,000 VND
            ['name' => 'Nike Air Max', 'description' => 'Giày thể thao Nike Air Max chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 37000.00, 'image' => 'images/products/nike-15.png'], // 3,700,000 VND
            ['name' => 'Nike Air Max Plus (TN Air)', 'description' => 'Giày thể thao Nike Air Max Plus (TN Air) chất lượng cao.', 'category_id' => rand(1, 2), 'brand_id' => 4, 'sale' => 0, 'status' => 1, 'price' => 43000.00, 'image' => 'images/products/nike-16.png'], // 4,300,000 VND
            ['name' => 'Adidas Ultraboost 22', 'description' => 'Giày chạy bộ Adidas Ultraboost 22 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 45000.00, 'image' => 'images/products/adidas-1.png'], // 4,500,000 VND
            ['name' => 'Adidas NMD R1', 'description' => 'Giày chạy bộ Adidas NMD R1 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 35000.00, 'image' => 'images/products/adidas-2.png'], // 3,500,000 VND
            ['name' => 'Adidas Superstar', 'description' => 'Giày chạy bộ Adidas Superstar với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/adidas-3.png'], // 2,500,000 VND
            ['name' => 'Adidas Stan Smith', 'description' => 'Giày chạy bộ Adidas Stan Smith với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/adidas-4.png'], // 2,500,000 VND
            ['name' => 'Adidas Yeezy Boost 350 V2', 'description' => 'Giày chạy bộ Adidas Yeezy Boost 350 V2 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 48000.00, 'image' => 'images/products/adidas-5.png'], // 4,800,000 VND
            ['name' => 'Adidas Forum Low', 'description' => 'Giày chạy bộ Adidas Forum Low với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 27000.00, 'image' => 'images/products/adidas-6.png'], // 2,700,000 VND
            ['name' => 'Adidas Gazelle', 'description' => 'Giày chạy bộ Adidas Gazelle với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 25000.00, 'image' => 'images/products/adidas-7.png'], // 2,500,000 VND
            ['name' => 'Adidas Samba OG', 'description' => 'Giày chạy bộ Adidas Samba OG với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 28000.00, 'image' => 'images/products/adidas-8.png'], // 2,800,000 VND
            ['name' => 'Adidas ZX 750', 'description' => 'Giày chạy bộ Adidas ZX 750 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 32000.00, 'image' => 'images/products/adidas-9.png'], // 3,200,000 VND
            ['name' => 'Adidas Predator Freak', 'description' => 'Giày chạy bộ Adidas Predator Freak với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 36000.00, 'image' => 'images/products/adidas-10.png'], // 3,600,000 VND
            ['name' => 'Adidas Harden Vol. 7', 'description' => 'Giày chạy bộ Adidas Harden Vol. 7 với công nghệ Boost.', 'category_id' => rand(1, 2), 'brand_id' => 1, 'sale' => 0, 'status' => 1, 'price' => 39000.00, 'image' => 'images/products/adidas-11.png'], // 3,900,000 VND
            ['name' => 'Puma Suede Classic', 'description' => 'Giày sneaker Puma Suede Classic phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 20000.00, 'image' => 'images/products/puma-1.png'], // 2,000,000 VND
            ['name' => 'Puma RS-X', 'description' => 'Giày sneaker Puma RS-X phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 28000.00, 'image' => 'images/products/puma-2.png'], // 2,800,000 VND
            ['name' => 'Puma Future Rider', 'description' => 'Giày sneaker Puma Future Rider phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 24000.00, 'image' => 'images/products/puma-3.png'], // 2,400,000 VND
            ['name' => 'Puma Cali', 'description' => 'Giày sneaker Puma Cali phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 22000.00, 'image' => 'images/products/puma-4.png'], // 2,200,000 VND
            ['name' => 'Puma Clyde OG', 'description' => 'Giày sneaker Puma Clyde OG phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 23000.00, 'image' => 'images/products/puma-5.png'], // 2,300,000 VND
            ['name' => 'Puma Ignite', 'description' => 'Giày sneaker Puma Ignite phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 26000.00, 'image' => 'images/products/puma-6.png'], // 2,600,000 VND
            ['name' => 'Puma Deviate Nitro', 'description' => 'Giày sneaker Puma Deviate Nitro phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 38000.00, 'image' => 'images/products/puma-7.png'], // 3,800,000 VND
            ['name' => 'Puma Smash V2', 'description' => 'Giày sneaker Puma Smash V2 phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 15000.00, 'image' => 'images/products/puma-8.png'], // 1,500,000 VND
            ['name' => 'Puma Slipstream', 'description' => 'Giày sneaker Puma Slipstream phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 24000.00, 'image' => 'images/products/puma-9.png'], // 2,400,000 VND
            ['name' => 'Puma Axelion', 'description' => 'Giày sneaker Puma Axelion phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 22000.00, 'image' => 'images/products/puma-10.png'], // 2,200,000 VND
            ['name' => 'Puma Ultra Ultimate', 'description' => 'Giày sneaker Puma Ultra Ultimate phong cách hiện đại.', 'category_id' => rand(1, 2), 'brand_id' => 5, 'sale' => 0, 'status' => 1, 'price' => 34000.00, 'image' => 'images/products/puma-11.png'], // 3,400,000 VND
            ['name' => 'Asics Gel-Kayano 30', 'description' => 'Giày chạy bộ Asics Gel-Kayano 30 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 48000.00, 'image' => 'images/products/asics-1.png'], // 4,800,000 VND
            ['name' => 'Asics Gel-Nimbus 26', 'description' => 'Giày chạy bộ Asics Gel-Nimbus 26 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 49000.00, 'image' => 'images/products/asics-2.png'], // 4,900,000 VND
            ['name' => 'Asics Novablast 4', 'description' => 'Giày chạy bộ Asics Novablast 4 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 36000.00, 'image' => 'images/products/asics-3.png'], // 3,600,000 VND
            ['name' => 'Asics Metaspeed Sky+', 'description' => 'Giày chạy bộ Asics Metaspeed Sky+ với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 52000.00, 'image' => 'images/products/asics-4.png'], // 5,200,000 VND
            ['name' => 'Asics GT-2000 12', 'description' => 'Giày chạy bộ Asics GT-2000 12 với hỗ trợ cao cấp.', 'category_id' => rand(1, 2), 'brand_id' => 2, 'sale' => 0, 'status' => 1, 'price' => 34000.00, 'image' => 'images/products/asics-5.png'], // 3,400,000 VND
            ['name' => 'Bata North Star Kick', 'description' => 'Giày da Bata North Star Kick phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 15000.00, 'image' => 'images/products/bata-1.png'], // 1,500,000 VND
            ['name' => 'Bata Power Xtreme', 'description' => 'Giày da Bata Power Xtreme phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 16000.00, 'image' => 'images/products/bata-2.png'], // 1,600,000 VND
            ['name' => 'Bata Red Label Casual', 'description' => 'Giày da Bata Red Label Casual phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 14000.00, 'image' => 'images/products/bata-3.png'], // 1,400,000 VND
            ['name' => 'Bata Weinbrenner Trek', 'description' => 'Giày da Bata Weinbrenner Trek phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 17000.00, 'image' => 'images/products/bata-4.png'], // 1,700,000 VND
            ['name' => 'Bata Comfit Walker', 'description' => 'Giày da Bata Comfit Walker phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 15000.00, 'image' => 'images/products/bata-5.png'], // 1,500,000 VND
            ['name' => 'Bata Bubblegummers Kids Sneakers', 'description' => 'Giày da Bata Bubblegummers Kids Sneakers phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 12000.00, 'image' => 'images/products/bata-6.png'], // 1,200,000 VND
            ['name' => 'Bata Tennis Classic', 'description' => 'Giày da Bata Tennis Classic phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 13000.00, 'image' => 'images/products/bata-7.png'], // 1,300,000 VND
            ['name' => 'Bata Ambassador Formal', 'description' => 'Giày da Bata Ambassador Formal phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 18000.00, 'image' => 'images/products/bata-8.png'], // 1,800,000 VND
            ['name' => 'Bata School Shoes (B.First)', 'description' => 'Giày da Bata School Shoes (B.First) phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 11000.00, 'image' => 'images/products/bata-9.png'], // 1,100,000 VND
            ['name' => 'Bata Heritage Hotshot', 'description' => 'Giày da Bata Heritage Hotshot phong cách lịch lãm.', 'category_id' => rand(1, 2), 'brand_id' => 3, 'sale' => 0, 'status' => 1, 'price' => 14000.00, 'image' => 'images/products/bata-10.png'], // 1,400,000 VND
        ];

        foreach ($products as $product) {
            // Gán kích thước dựa trên danh mục
            $size = $product['category_id'] == 1
                ? ['40', '41', '42']  // Nam
                : ['36', '37', '38']; // Nữ

            // Gán toàn bộ màu sắc theo thương hiệu
            $color = $colors[$product['brand_id']] ?? ['Đen'];

            Product::create(array_merge($product, [
                'size' => implode(',', $size),
                'color' => implode(',', $color),
                'stock_quantity' => $faker->numberBetween(20, 100),
                'sold' => $faker->numberBetween(0, 50),
                'hot' => $faker->numberBetween(0, 3),
            ]));
        }
    }
}
