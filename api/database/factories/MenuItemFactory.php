<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition()
    {
        return [
            'category_id' => MenuCategory::factory(),
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug(3),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 100),
            'is_special' => $this->faker->boolean(30),
            'is_active' => true,
            'order' => $this->faker->numberBetween(0, 100),
        ];
    }
}











