<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use App\Models\Media;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run()
    {
        $interiorMedia = Media::where('title', 'Bakery Interior')->first();
        $cakeMedia = Media::where('title', 'Chocolate Cake')->first();

        GalleryItem::create([
            'title' => 'Our Cozy Bakery',
            'caption' => 'Step inside our warm and inviting bakery',
            'category' => GalleryItem::CATEGORY_EVENTS,
            'image_id' => $interiorMedia ? $interiorMedia->id : 1,
            'is_active' => true,
            'order' => 1,
        ]);

        GalleryItem::create([
            'title' => 'Chocolate Masterpiece',
            'caption' => 'One of our signature chocolate cakes',
            'category' => GalleryItem::CATEGORY_AGRO,
            'image_id' => $cakeMedia ? $cakeMedia->id : 2,
            'is_active' => true,
            'order' => 2,
        ]);
    }
}

