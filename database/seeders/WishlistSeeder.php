<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wishlist;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ trước khi thêm mới
        Wishlist::truncate();

        // Thêm dữ liệu mẫu
        Wishlist::create([
            'user_id' => 1,
            'product_id' => 2,
        ]);

        Wishlist::create([
            'user_id' => 1,
            'product_id' => 3,
        ]);

        Wishlist::create([
            'user_id' => 2,
            'product_id' => 1,
        ]);
    }
}

