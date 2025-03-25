<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            SizeSeeder::class,
            ColorSeeder::class,
            ProductVariantSeeder::class,
            ImageSeeder::class,
            UserSeeder::class,
            CartSeeder::class,
            PaymentSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            DiscountSeeder::class,
            ReviewSeeder::class,
            ProductDiscountSeeder::class,
            WishlistSeeder::class,
            NotifiSeeder::class,
            NewsSeeder::class,
        ]);
    }
}
