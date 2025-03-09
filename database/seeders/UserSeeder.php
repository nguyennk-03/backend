<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Ngô Khánh Nguyên', 'email' => 'nguyennkps27609@fpt.edu.vn', 'password' => 'PS27609',],
            ['name' => 'Nguyễn Hữu Kiệt', 'email' => 'kietnhps27657@fpt.edu.vn', 'password' => 'PS27657',],
            ['name' => 'Nguyễn Tuấn Anh', 'email' => 'anhntps35235@fpt.edu.vn', 'password' => 'PS35235',],
            ['name' => 'Trần Văn Nhân', 'email' => 'nhantvps33579@fpt.edu.vn', 'password' => 'PS33579',],
            ['name' => 'Văn Đức Anh', 'email' => 'anhvdps34505@fpt.edu.vn', 'password' => 'PS34505',],
            ['name' => 'Lê Nguyễn Hoàng Khiêm', 'email' => 'khiemlnhps33864@fpt.edu.vn', 'password' => 'PS33864',],
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
