<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    public function run()
    {
        $sizes = [
            ['name' => '35', 'cm' => 22.5,'status' => 1],
            ['name' => '36', 'cm' => 23.0,'status' => 1],
            ['name' => '37', 'cm' => 23.5,'status' => 1],
            ['name' => '38', 'cm' => 24.0,'status' => 1],
            ['name' => '39', 'cm' => 24.5,'status' => 1],
            ['name' => '40', 'cm' => 25.0,'status' => 1],
            ['name' => '41', 'cm' => 25.5,'status' => 1],
            ['name' => '42', 'cm' => 26.0,'status' => 1],
            ['name' => '43', 'cm' => 26.5,'status' => 1],
            ['name' => '44', 'cm' => 27.0,'status' => 1],
            ['name' => '45', 'cm' => 27.5,'status' => 1],
            ['name' => '46', 'cm' => 28.0,'status' => 1],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }
    }
}
