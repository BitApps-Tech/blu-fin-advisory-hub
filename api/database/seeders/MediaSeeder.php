<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        
        Media::create([
            'disk' => 'public',
            'path' => 'uploads/2024/01/croissant.jpg',
            'mime' => 'image/jpeg',
            'size' => 245678,
            'width' => 1200,
            'height' => 800,
            'title' => 'Fresh Croissant',
            'alt' => 'Buttery flaky croissant',
            'created_by' => $user->id,
        ]);

        Media::create([
            'disk' => 'public',
            'path' => 'uploads/2024/01/chocolate-cake.jpg',
            'mime' => 'image/jpeg',
            'size' => 345678,
            'width' => 1200,
            'height' => 800,
            'title' => 'Chocolate Cake',
            'alt' => 'Rich chocolate layer cake',
            'created_by' => $user->id,
        ]);

        Media::create([
            'disk' => 'public',
            'path' => 'uploads/2024/01/wedding-cake.jpg',
            'mime' => 'image/jpeg',
            'size' => 456789,
            'width' => 1200,
            'height' => 1600,
            'title' => 'Wedding Cake',
            'alt' => 'Beautiful multi-tier wedding cake',
            'created_by' => $user->id,
        ]);

        Media::create([
            'disk' => 'public',
            'path' => 'uploads/2024/01/bakery-interior.jpg',
            'mime' => 'image/jpeg',
            'size' => 567890,
            'width' => 1920,
            'height' => 1080,
            'title' => 'Bakery Interior',
            'alt' => 'Our cozy bakery interior',
            'created_by' => $user->id,
        ]);
    }
}

