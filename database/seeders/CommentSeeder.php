<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');
        $users = User::all();
        $products = Product::pluck('id')->toArray();

        if ($users->isEmpty() || empty($products)) return;

        // Tách users thành admin và user
        $adminUsers = $users->where('role', 'admin')->pluck('id')->toArray();
        $normalUsers = $users->where('role', 'user')->pluck('id')->toArray();

        $commentPairs = [
            'Giày này có chống nước không?' => 'Dạ giày có đế chống trơn, đi mưa vẫn bám tốt ạ!',
            'Form giày này rộng hay ôm chân vậy shop?' => 'Dạ giày form ôm vừa chân, mang khá êm và thoải mái ạ!',
            'Có size 41 không ạ?' => 'Dạ size 41 hiện vẫn còn hàng, bạn đặt sớm giúp shop nha!',
            'Mang đôi này đi chạy bộ được không?' => 'Giày này thiết kế chuyên cho chạy bộ luôn đó bạn.',
            'Thời gian giao hàng bao lâu vậy shop?' => 'Giao hàng toàn quốc từ 2–4 ngày làm việc bạn nha.',
            'Giày có bảo hành không?' => 'Dạ bên em bảo hành keo đế trong 3 tháng nha!',
            'Chất liệu da hay vải vậy ạ?' => 'Dạ giày chất liệu vải cao cấp, thoáng khí ạ!',
            'Nếu không vừa thì có đổi size được không?' => 'Shop hỗ trợ đổi size trong 7 ngày kể từ khi nhận hàng bạn nhé!',
            'Shop còn màu trắng không vậy?' => 'Mẫu màu trắng hiện vẫn còn hàng ạ, bạn yên tâm!',
            'Đôi này mang đi học có hợp không?' => 'Dạ phù hợp bạn nha, thiết kế trẻ trung dễ phối đồ!',
            'Giày có nặng không ạ?' => 'Dạ nhẹ lắm bạn ơi, đi cả ngày vẫn thoải mái!',
            'Giày có tăng chiều cao không vậy?' => 'Dạ đế cao tầm 3cm, hỗ trợ tăng chiều cao tốt bạn nhé!',
            'Có hỗ trợ thanh toán khi nhận hàng không?' => 'Dạ có hỗ trợ COD toàn quốc luôn ạ!',
            'Đế giày có bị trơn không ạ?' => 'Dạ đế có rãnh chống trượt, đi mưa vẫn yên tâm bạn nhé!',
            'Sản phẩm này là hàng chính hãng chứ?' => 'Dạ hàng chính hãng, có đầy đủ tem và hộp nha bạn!',
            'Mình nên chọn size bao nhiêu nếu chân dài 26cm?' => 'Bạn chọn size 41 là vừa chân nha!',
            'Mẫu này còn màu trắng không shop?' => 'Dạ mẫu này còn màu trắng nha bạn!',
            'Giày có phù hợp để chạy bộ không?' => 'Dạ hoàn toàn phù hợp bạn nhé, giày chuyên chạy bộ ạ!',
            'Thời gian giao hàng khoảng bao lâu?' => 'Từ 2–4 ngày tuỳ khu vực nha bạn!',
            'Có được kiểm tra hàng trước khi thanh toán không?' => 'Dạ có, bạn được kiểm tra trước khi thanh toán ạ!',
            'Giày có bảo hành không vậy?' => 'Dạ bảo hành keo đế trong 3 tháng bạn nha!',
            'Nếu không vừa size có được đổi không?' => 'Dạ có hỗ trợ đổi size miễn phí bạn ạ!',
            'Đế giày này có trơn không ạ?' => 'Dạ đế chống trượt, đảm bảo an toàn khi di chuyển!',
            'Giày này có sẵn ở cửa hàng không?' => 'Dạ sản phẩm này có sẵn tại shop nha!',
            'Mình mang size 41 thì nên chọn size nào?' => 'Dạ chọn đúng size 41 là vừa bạn nha!',
            'Shop có hỗ trợ đổi size miễn phí không?' => 'Dạ có hỗ trợ đổi size miễn phí trong 7 ngày ạ!',
            'Mẫu này sản xuất ở đâu vậy?' => 'Dạ mẫu này được sản xuất tại Việt Nam bạn nhé!',
            'Giày có thể giặt máy không?' => 'Dạ không nên giặt máy, bạn nên vệ sinh nhẹ nhàng bằng tay nha!',
            'Có chương trình khuyến mãi nào không?' => 'Dạ hiện đang có giảm 10% cho đơn từ 500K bạn nha!',
        ];

        // Đưa các câu hỏi và câu trả lời vào từng sản phẩm
        foreach ($products as $productId) {
            foreach ($commentPairs as $question => $answer) {
                // Người dùng bình thường tạo comment
                $userComment = Comment::create([
                    'product_id' => $productId,
                    'user_id' => $faker->randomElement($normalUsers),
                    'message' => $question,
                    'is_staff' => false,
                    'is_hidden' => 1,
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                ]);

                // Admin trả lời bình luận
                Comment::create([
                    'product_id' => $productId,
                    'user_id' => $faker->randomElement($adminUsers),
                    'message' => $answer,
                    'is_staff' => true,
                    'is_hidden' => 1,
                    'parent_id' => $userComment->id,
                    'created_at' => now(),
                ]);
            }
        }
    }
}
