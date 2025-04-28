<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            ProductSeeder::class,
            ImageSeeder::class,
            DiscountSeeder::class,
            PaymentSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            NotificationSeeder::class,
            WishlistSeeder::class,
            NewsSeeder::class,
            CommentSeeder::class,
        ]);
    }
}