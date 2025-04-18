<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Nguyễn Văn A', 'email' => 'user1@gmail.com  ', 'password' => 'user1234@','role' => 'user'],
            ['name' => 'Nguyễn Văn B', 'email' => 'admin1@gmail.com', 'password' => 'admin1234@','role' => 'admin'],
            ['name' => 'Ngô Khánh Nguyên', 'email' => 'nguyennkps27609@fpt.edu.vn', 'password' => 'nguyennkps27609@fpt.edu.vn',],
            ['name' => 'Nguyễn Hữu Kiệt', 'email' => 'kietnhps27657@fpt.edu.vn', 'password' => 'kietnhps27657@fpt.edu.vn',],
            ['name' => 'Nguyễn Tuấn Anh', 'email' => 'anhntps35235@fpt.edu.vn', 'password' => 'anhntps35235@fpt.edu.vn',],
            ['name' => 'Trần Văn Nhân', 'email' => 'nhantvps33579@fpt.edu.vn', 'password' => 'nhantvps33579@fpt.edu.vn',],
            ['name' => 'Văn Đức Anh', 'email' => 'anhvdps34505@fpt.edu.vn', 'password' => 'anhvdps34505@fpt.edu.vn',],
            ['name' => 'Lê Nguyễn Hoàng Khiêm', 'email' => 'khiemlnhps33864@fpt.edu.vn', 'password' => 'khiemlnhps33864@fpt.edu.vn',],
        ];

        foreach ($users as $index => $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($user['password']),
                'phone' => '09876543' . $index,
                'avatar' => 'images/users/user-' . ($index + 1) . '.jpg',
                'address' => 'FPT University, Vietnam',
                'role' => 'admin',
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
