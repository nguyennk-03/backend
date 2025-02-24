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

        for ($i = 1; $i <= 30; $i++) {
            User::create([
                'full_name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'avatar' => $faker->imageUrl(200, 200, 'people'),
                'role' => $faker->randomElement(['admin', 'user']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
