<?php

namespace Database\Seeders;

use App\Models\Specialty;
use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpecialtySeeder extends Seeder
{
    public function run()
    {
        $weddingCakeMedia = Media::where('title', 'Wedding Cake')->first();

        Specialty::create([
            'title' => 'Custom Wedding Cakes',
            'slug' => Str::slug('Custom Wedding Cakes'),
            'excerpt' => 'Make your special day even sweeter with our custom wedding cakes',
            'description' => 'Our expert bakers create stunning custom wedding cakes tailored to your vision. From elegant tiered cakes to modern designs, we work with you to create the perfect centerpiece for your celebration. Choose from a variety of flavors, fillings, and decorations.',
            'image_id' => $weddingCakeMedia ? $weddingCakeMedia->id : null,
            'is_active' => true,
            'order' => 1,
        ]);

        Specialty::create([
            'title' => 'Birthday Party Packages',
            'slug' => Str::slug('Birthday Party Packages'),
            'excerpt' => 'Complete birthday packages with cake, cupcakes, and decorations',
            'description' => 'Celebrate birthdays in style with our all-inclusive party packages. Each package includes a custom birthday cake, matching cupcakes, and themed decorations. Perfect for kids and adults alike. We offer a variety of themes and flavors to make your party unforgettable.',
            'image_id' => null,
            'is_active' => true,
            'order' => 2,
        ]);
    }
}

