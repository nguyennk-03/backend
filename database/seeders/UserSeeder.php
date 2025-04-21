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
            ['name' => 'Nguyễn Văn A', 'email' => 'user@gmail.com', 'password' => 'user@gmail.com','role' => 'user'],
            ['name' => 'Trần Văn B', 'email' => 'admin@gmail.com', 'password' => 'admin@gmail.com','role' => 'admin'],
            ['name' => 'Ngô Khánh Nguyên', 'email' => 'nguyennkps27609@fpt.edu.vn', 'password' => 'nguyennkps27609@fpt.edu.vn', 'role' => 'admin'],
            ['name' => 'Nguyễn Hữu Kiệt', 'email' => 'kietnhps27657@fpt.edu.vn', 'password' => 'kietnhps27657@fpt.edu.vn', 'role' => 'admin'],
            ['name' => 'Nguyễn Tuấn Anh', 'email' => 'anhntps35235@fpt.edu.vn', 'password' => 'anhntps35235@fpt.edu.vn', 'role' => 'admin'],
            ['name' => 'Trần Văn Nhân', 'email' => 'nhantvps33579@fpt.edu.vn', 'password' => 'nhantvps33579@fpt.edu.vn', 'role' => 'admin'],
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
                'role' => $user['role'] ?? 'user',
                'status' => 0,
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
