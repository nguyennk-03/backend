<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run()
    {
        $colors = [
            ['name' => 'Đỏ', 'hex_code' => '#FF0000'],
            ['name' => 'Xanh lá cây', 'hex_code' => '#00FF00'],
            ['name' => 'Xanh dương', 'hex_code' => '#0000FF'],
            ['name' => 'Đen', 'hex_code' => '#000000'],
            ['name' => 'Trắng', 'hex_code' => '#FFFFFF'],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
