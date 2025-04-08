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
            ['name' => 'Đen',        'hex_code' => '#000000'],
            ['name' => 'Trắng',      'hex_code' => '#FFFFFF'],
            ['name' => 'Đỏ',         'hex_code' => '#FF0000'],
            ['name' => 'Xanh dương', 'hex_code' => '#0000FF'],
            ['name' => 'Xanh lá',    'hex_code' => '#00FF00'],
            ['name' => 'Vàng',       'hex_code' => '#FFFF00'],
            ['name' => 'Tím',        'hex_code' => '#800080'],
            ['name' => 'Bạc',        'hex_code' => '#C0C0C0'],
            ['name' => 'Vàng kim',   'hex_code' => '#FFD700'],
            ['name' => 'Nâu',        'hex_code' => '#A52A2A'],
        ];

        foreach ($colors as $index => $color) {
            $code = strtoupper(Str::ascii(Str::slug($color['name'], '')));
            $code = substr($code, 0, 3) . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT); // ví dụ: DEN-001

            Color::firstOrCreate(
                ['name' => $color['name']],
                [
                    'code'       => $code,
                    'hex_code'   => $color['hex_code'],
                    'image'      => null,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
