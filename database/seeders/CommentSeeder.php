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
        $users = User::pluck('id')->toArray();
        $products = Product::pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            Comment::create([
                'user_id' => $faker->randomElement($users),
                'product_id' => $faker->randomElement($products),
                'content' => $faker->sentence(10),
            ]);
        }
    }
}
