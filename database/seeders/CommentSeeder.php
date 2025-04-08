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
        $faker = Faker::create();

        $userIds = User::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        if (empty($userIds) || empty($productIds)) {
            return; 
        }

        for ($i = 0; $i < 50; $i++) { 
            Comment::create([
                'product_id' => $faker->randomElement($productIds),
                'user_id' => $faker->randomElement($userIds),
                'message' => $faker->sentence(rand(5, 15)), 
                'is_staff' => $faker->boolean(10), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
