<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        User::create([
            'full_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'avatar' => 'images/users/user-1.jpg',
            'address' => $faker->address,
            'role' => 'admin',
        ]);

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'full_name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber,
                'avatar' => User::getRandomImage(),
                'address' => $faker->address,
                'role' => 'user',
            ]);
        }
    }
}
