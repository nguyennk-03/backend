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
            ['name' => 'Đen',        'hex_code' => '#000000','status' => 1],
            ['name' => 'Trắng',      'hex_code' => '#FFFFFF','status' => 1],
            ['name' => 'Đỏ',         'hex_code' => '#FF0000','status' => 1],
            ['name' => 'Xanh dương', 'hex_code' => '#0000FF','status' => 1],
            ['name' => 'Xanh lá',    'hex_code' => '#00FF00','status' => 1],
            ['name' => 'Vàng',       'hex_code' => '#FFFF00','status' => 1],
            ['name' => 'Tím',        'hex_code' => '#800080','status' => 1],
            ['name' => 'Bạc',        'hex_code' => '#C0C0C0','status' => 1],
            ['name' => 'Vàng kim',   'hex_code' => '#FFD700','status' => 1],
            ['name' => 'Nâu',        'hex_code' => '#A52A2A','status' => 1],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
