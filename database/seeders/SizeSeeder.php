<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    public function run()
    {
        $sizes = [
            ['name' => '36'],
            ['name' => '37'],
            ['name' => '38'],
            ['name' => '39'],
            ['name' => '40'],
            ['name' => '41'],
            ['name' => '42'],
            ['name' => '43'],
            ['name' => '44'],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }
    }
}
