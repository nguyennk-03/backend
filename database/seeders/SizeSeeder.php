<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    public function run()
    {
        $sizes = ['35', '36', '37', '38', '39', '40', '41', '42', '43', '44'];

        foreach ($sizes as $size) {
            Size::firstOrCreate(
                ['size' => $size], 
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
