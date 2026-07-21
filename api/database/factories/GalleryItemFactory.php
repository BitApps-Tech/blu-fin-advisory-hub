<?php

namespace Database\Factories;

use App\Models\GalleryItem;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    protected $model = GalleryItem::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'caption' => $this->faker->optional()->sentence(),
            'category' => GalleryItem::CATEGORY_EVENTS,
            'image_id' => Media::factory(),
            'is_active' => true,
            'order' => $this->faker->numberBetween(0, 20),
        ];
    }
}
