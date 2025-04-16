<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }
        $newsData = [
            [
                'title' => 'Adidas Ultraboost – Bước đột phá trong công nghệ giày chạy bộ',
                'content' => 'Adidas Ultraboost từ lâu đã là lựa chọn hàng đầu của các vận động viên và những người đam mê chạy bộ. Với
                công nghệ đệm Boost cải tiến, đôi giày mang lại trải nghiệm êm ái, phản hồi lực tốt và hỗ trợ tối ưu cho bàn chân. Không
                chỉ hiệu suất, Adidas còn kết hợp thiết kế hiện đại, phù hợp cả khi tập luyện lẫn đi chơi.',
                'category_id' => 1,
                'brand_id' => 1,
                'status' => 1,
                'image' => 'images/news/adidas_ultraboost.jpg'
            ],
            [
                'title' => 'Nike ZoomX Vaporfly – Bí quyết giúp phá kỷ lục chạy bộ',
                'content' => 'Nike ZoomX Vaporfly đã tạo nên một cuộc cách mạng trong ngành giày thể thao. Với tấm sợi carbon toàn bàn
                chân, ZoomX Vaporfly giúp tăng độ bật nảy, giảm mệt mỏi và hỗ trợ tối đa cho vận động viên marathon. Nếu bạn đang tìm
                kiếm một đôi giày hiệu suất cao, đây chính là lựa chọn hàng đầu.',
                'category_id' => 2,
                'brand_id' => 4,
                'status' => 1,
                'image' => 'images/news/nike_vaporfly.jpg'
            ],
            [
                'title' => 'Puma Future Rider – Sự kết hợp hoàn hảo giữa cổ điển và hiện đại',
                'content' => 'Lấy cảm hứng từ thiết kế thập niên 80, Puma Future Rider mang đến sự trẻ trung và linh hoạt. Với đế Rider
                Foam nhẹ nhàng và lớp lót thoáng khí, đôi giày không chỉ tạo sự thoải mái mà còn giúp tăng cường hiệu suất vận động. Một
                lựa chọn tuyệt vời cho các tín đồ sneaker.',
                'category_id' => 1,
                'brand_id' => 5,
                'status' => 1,
                'image' => 'images/news/puma_futurerider.jpg'
            ],
            [
                'title' => 'Asics Novablast – Sải bước mạnh mẽ, chinh phục mọi thử thách',
                'content' => 'Dành cho những ai yêu thích tốc độ, Asics Novablast được trang bị đệm FF Blast giúp tăng độ nảy và giảm áp
                lực lên bàn chân. Kiểu dáng thể thao cùng trọng lượng nhẹ làm cho Novablast trở thành đôi giày lý tưởng cho cả việc chạy
                bộ và tập luyện hàng ngày.',
                'category_id' => 2,
                'brand_id' => 2,
                'status' => 1,
                'image' => 'images/news/asics_novablast.jpg'
            ],
            [
                'title' => 'Bata Power – Biểu tượng bền bỉ vượt thời gian',
                'content' => 'Bata Power là một trong những dòng giày có độ bền cao nhất trên thị trường. Với phần đế cao su chắc chắn
                và lớp da tổng hợp chống nước, đôi giày này thích hợp cho các hoạt động thể thao và sử dụng hàng ngày.',
                'category_id' => 1,
                'brand_id' => 3,
                'status' => 1,
                'image' => 'images/news/bata_power.jpg'
            ],
            [
                'title' => 'Nike Air Jordan – Huyền thoại không bao giờ lỗi mốt',
                'content' => 'Từ những năm 1980, Air Jordan luôn là biểu tượng của văn hóa sneaker. Với thiết kế mang đậm phong cách thể
                thao, kết hợp cùng công nghệ đệm Air độc quyền, Nike Air Jordan không chỉ là một đôi giày, mà còn là một tuyên ngôn thời
                trang.',
                'category_id' => 2,
                'brand_id' => 4,
                'status' => 1,
                'image' => 'images/news/nike_airjordan.jpg'
            ],
            [
                'title' => 'Puma Cali – Đôi giày "hot trend" trong làng thời trang',
                'content' => 'Puma Cali không chỉ đơn giản là một đôi giày thể thao mà còn là item thời trang không thể thiếu của những
                tín đồ street style. Với thiết kế đơn giản nhưng đầy tinh tế, phần đế dày giúp tạo chiều cao và điểm nhấn nổi bật.',
                'category_id' => 1,
                'status' => 1,
                'brand_id' => 5,
                'image' => 'images/news/puma_cali.jpg'
            ],
            [
                'title' => 'Adidas Superstar – Hơn 50 năm thống trị làng sneaker',
                'content' => 'Được ra mắt vào năm 1969, Adidas Superstar vẫn luôn là biểu tượng vượt thời gian. Thiết kế cổ điển với mũi
                vỏ sò huyền thoại và chất liệu da cao cấp khiến đôi giày này trở thành must-have trong tủ đồ của bất kỳ ai yêu thích
                sneaker.',
                'category_id' => 2,
                'brand_id' => 1,
                'status' => 1,
                'image' => 'images/news/adidas_superstar.jpg'
            ],
            [
                'title' => 'Asics Gel-Kayano – Bước tiến vượt bậc trong công nghệ giày chạy',
                'content' => 'Asics Gel-Kayano nổi tiếng với sự ổn định và khả năng giảm chấn tuyệt vời. Công nghệ GEL đặc trưng giúp
                bảo vệ chân khỏi tác động mạnh, mang lại cảm giác thoải mái ngay cả khi chạy đường dài.',
                'category_id' => 1,
                'brand_id' => 2,
                'status' => 1,
                'image' => 'images/news/asics_gelkayano.jpg'
            ],
            [
                'title' => 'Nike Air Max Kids – Giải pháp hoàn hảo cho trẻ em năng động',
                'content' => 'Nike Air Max Kids mang đến sự thoải mái tối đa với lớp đệm khí Air. Đôi giày này không chỉ giúp bé vận
                động linh hoạt mà còn đảm bảo độ bám tốt nhờ thiết kế đế chống trượt.',
                'category_id' => 2,
                'brand_id' => 4,
                'image' => 'images/news/nike_airmax_kids.jpg',
                'status' => 1,
            ],
        ];

        foreach ($newsData as $news) {
            News::updateOrCreate(
                ['slug' => Str::slug($news['title'])],
                [
                    'title' => $news['title'],
                    'slug' => Str::slug($news['title']),
                    'content' => $news['content'],
                    'image' => $news['image'],
                    'category_id' => $news['category_id'],
                    'brand_id' => $news['brand_id'],
                    'author' => $user->name,
                    'views' => rand(500, 5000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
