<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    public function run()
    {
        $sizes = [
            ['name' => '35', 'cm' => 22.5],
            ['name' => '36', 'cm' => 23.0],
            ['name' => '37', 'cm' => 23.5],
            ['name' => '38', 'cm' => 24.0],
            ['name' => '39', 'cm' => 24.5],
            ['name' => '40', 'cm' => 25.0],
            ['name' => '41', 'cm' => 25.5],
            ['name' => '42', 'cm' => 26.0],
            ['name' => '43', 'cm' => 26.5],
            ['name' => '44', 'cm' => 27.0],
            ['name' => '45', 'cm' => 27.5],
            ['name' => '46', 'cm' => 28.0],
        ];

        foreach ($sizes as $size) {
            Size::firstOrCreate(
                ['name' => $size['name']],
                [
                    'cm' => $size['cm'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
