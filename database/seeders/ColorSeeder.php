<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run()
    {
        $colors = ['black', 'white', 'red', 'blue', 'green', 'yellow', 'purple', 'silver', 'gold', 'brown'];

        foreach ($colors as $color) {
            Color::firstOrCreate(
                ['color_name' => $color], 
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
