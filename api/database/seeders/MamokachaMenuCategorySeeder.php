<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

/**
 * Canonical Mamokacha menu category order for admin + public menu.
 * php artisan db:seed --class=MamokachaMenuCategorySeeder
 */
class MamokachaMenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hot Drinks', 'slug' => 'hot-drinks', 'order' => 1],
            ['name' => 'Breakfast', 'slug' => 'breakfast', 'order' => 2],
            ['name' => 'Sandwiches', 'slug' => 'sandwiches', 'order' => 3],
            ['name' => 'Lasagna and Pizza', 'slug' => 'lasagna-and-pizza', 'order' => 4],
            ['name' => 'Salads', 'slug' => 'salads', 'order' => 5],
            ['name' => 'Juices', 'slug' => 'juices', 'order' => 6],
            ['name' => 'Pastry', 'slug' => 'pastry', 'order' => 7],
            ['name' => 'Cookies', 'slug' => 'cookies', 'order' => 8],
            ['name' => 'Cold Drinks', 'slug' => 'cold-drinks', 'order' => 9],
        ];

        foreach ($categories as $category) {
            MenuCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => "Delicious {$category['name']} options from Mamokacha",
                    'is_active' => true,
                    'order' => $category['order'],
                ]
            );
        }
    }
}
