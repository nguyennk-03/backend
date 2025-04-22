<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use Faker\Factory;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('vi_VN'); // Ngôn ngữ Việt

        $users = User::all();
        $products = Product::all();

        $comments = [
            'Giày rất đẹp và thoải mái.',
            'Chất lượng tốt, đi êm chân.',
            'Màu sắc như hình, rất hài lòng.',
            'Giao hàng nhanh, đóng gói kỹ.',
            'Giá hợp lý so với chất lượng.',
            'Đôi này chạy bộ rất ổn.',
            'Phù hợp đi chơi hoặc đi học.',
            'Form chuẩn, không bị chật hay rộng.',
            'Rất đáng tiền, sẽ mua lại lần sau.',
            'Thiết kế năng động, hợp thời trang.',
            'Giày khá nhẹ và bền.',
            'Đi một thời gian vẫn giữ form tốt.',
            'Lót trong êm, không bị đau chân.',
            'Đôi giày này mang đi làm cũng hợp.',
            'Sản phẩm giống mô tả, chất lượng ổn định.',
        ];

        for ($i = 1; $i <= 50; $i++) {
            Review::create([
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
                'rating' => $faker->numberBetween(3, 5), // Ưu tiên đánh giá tốt
                'comment' => $faker->randomElement($comments),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
