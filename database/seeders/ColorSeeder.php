<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;
use Illuminate\Support\Str;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Đen',        'hex_code' => '#000000', 'status' => 1],
            ['name' => 'Trắng',      'hex_code' => '#FFFFFF', 'status' => 1],
            ['name' => 'Xám',        'hex_code' => '#808080', 'status' => 1],
            ['name' => 'Navy',       'hex_code' => '#000080', 'status' => 1],
            ['name' => 'Đỏ',         'hex_code' => '#B22222', 'status' => 1],
            ['name' => 'Be',         'hex_code' => '#F5F5DC', 'status' => 1],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
