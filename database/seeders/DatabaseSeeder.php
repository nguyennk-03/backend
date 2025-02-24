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
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class,
            DiscountSeeder::class,
            ReviewSeeder::class,
            ProductDiscountSeeder::class,
        ]);
    }
}
