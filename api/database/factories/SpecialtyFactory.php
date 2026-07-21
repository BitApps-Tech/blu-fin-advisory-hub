<?php

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialtyFactory extends Factory
{
    protected $model = Specialty::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug(3),
            'excerpt' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'is_active' => true,
            'order' => $this->faker->numberBetween(0, 100),
        ];
    }
}











