<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuCategorySeeder extends Seeder
{
    public function run()
    {
        MenuCategory::create([
            'name' => 'Pastries',
            'slug' => Str::slug('Pastries'),
            'description' => 'Fresh baked pastries made daily',
            'is_active' => true,
            'order' => 1,
        ]);

        MenuCategory::create([
            'name' => 'Cakes',
            'slug' => Str::slug('Cakes'),
            'description' => 'Delicious cakes for all occasions',
            'is_active' => true,
            'order' => 2,
        ]);

        MenuCategory::create([
            'name' => 'Beverages',
            'slug' => Str::slug('Beverages'),
            'description' => 'Hot and cold drinks',
            'is_active' => true,
            'order' => 3,
        ]);
    }
}

