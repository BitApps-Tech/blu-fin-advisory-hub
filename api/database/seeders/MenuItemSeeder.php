<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuItemSeeder extends Seeder
{
    public function run()
    {
        $pastriesCategory = MenuCategory::where('slug', 'pastries')->first();
        $cakesCategory = MenuCategory::where('slug', 'cakes')->first();
        $beveragesCategory = MenuCategory::where('slug', 'beverages')->first();
        
        $croissantMedia = Media::where('title', 'Fresh Croissant')->first();
        $cakeMedia = Media::where('title', 'Chocolate Cake')->first();

        // Pastries
        MenuItem::create([
            'category_id' => $pastriesCategory->id,
            'name' => 'Butter Croissant',
            'slug' => Str::slug('Butter Croissant'),
            'description' => 'Buttery, flaky, and perfectly golden croissant',
            'price' => 3.50,
            'is_special' => true,
            'is_active' => true,
            'image_id' => $croissantMedia ? $croissantMedia->id : null,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $pastriesCategory->id,
            'name' => 'Chocolate Danish',
            'slug' => Str::slug('Chocolate Danish'),
            'description' => 'Flaky pastry with rich chocolate filling',
            'price' => 4.25,
            'is_special' => false,
            'is_active' => true,
            'image_id' => null,
            'order' => 2,
        ]);

        // Cakes
        MenuItem::create([
            'category_id' => $cakesCategory->id,
            'name' => 'Chocolate Layer Cake',
            'slug' => Str::slug('Chocolate Layer Cake'),
            'description' => 'Rich chocolate cake with chocolate ganache frosting',
            'price' => 45.00,
            'is_special' => true,
            'is_active' => true,
            'image_id' => $cakeMedia ? $cakeMedia->id : null,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $cakesCategory->id,
            'name' => 'Red Velvet Cake',
            'slug' => Str::slug('Red Velvet Cake'),
            'description' => 'Classic red velvet with cream cheese frosting',
            'price' => 48.00,
            'is_special' => false,
            'is_active' => true,
            'image_id' => null,
            'order' => 2,
        ]);

        // Beverages
        MenuItem::create([
            'category_id' => $beveragesCategory->id,
            'name' => 'Cappuccino',
            'slug' => Str::slug('Cappuccino'),
            'description' => 'Espresso with steamed milk foam',
            'price' => 4.50,
            'is_special' => false,
            'is_active' => true,
            'image_id' => null,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $beveragesCategory->id,
            'name' => 'Iced Latte',
            'slug' => Str::slug('Iced Latte'),
            'description' => 'Cold espresso with milk over ice',
            'price' => 5.00,
            'is_special' => false,
            'is_active' => true,
            'image_id' => null,
            'order' => 2,
        ]);
    }
}

