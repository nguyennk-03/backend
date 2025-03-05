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
        $faker = Faker::create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'avatar' => 'images/users/user-1.jpg',
            'address' => $faker->address,
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber,
                'avatar' => User::getRandomImage(),
                'address' => $faker->address,
                'role' => 'user',
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
