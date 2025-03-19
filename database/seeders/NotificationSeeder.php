<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Faker\Factory as Faker;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $userIds = User::pluck('id')->toArray(); 

        for ($i = 1; $i <= 20; $i++) {
            Notification::create([
                'user_id' => $faker->randomElement($userIds), 
                'title' => $faker->sentence(6),
                'message' => $faker->paragraph(2),
                'link' => $faker->optional()->url,
                'status' => $faker->randomElement(['unread', 'read']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
