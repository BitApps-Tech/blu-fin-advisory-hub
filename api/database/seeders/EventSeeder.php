<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [
            [
                'title' => 'Pastry Making Workshop',
                'presenter' => 'Chef Marie Laurent',
                'description' => 'Learn the art of French pastry making with our master chef. Perfect for beginners and enthusiasts.',
                'activity' => 'Workshop',
                'location' => 'MamoKacha Kitchen Studio',
                'event_date' => Carbon::now()->addDays(7)->setTime(14, 0),
                'status' => 'upcoming',
                'is_active' => true,
            ],
            [
                'title' => 'Christmas Cookie Decorating',
                'presenter' => 'Chef Sarah Johnson',
                'description' => 'Join us for a festive cookie decorating session. All materials provided!',
                'activity' => 'Class',
                'location' => 'Main Bakery Hall',
                'event_date' => Carbon::now()->addDays(14)->setTime(10, 0),
                'status' => 'upcoming',
                'is_active' => true,
            ],
            [
                'title' => 'Sweet Treats Tasting Event',
                'presenter' => 'Chef Antonio Rossi',
                'description' => 'Experience our newest creations and provide your valuable feedback.',
                'activity' => 'Tasting',
                'location' => 'MamoKacha Café',
                'event_date' => Carbon::now()->addDays(3)->setTime(16, 30),
                'status' => 'upcoming',
                'is_active' => true,
            ],
            [
                'title' => 'Bread Baking Basics',
                'presenter' => 'Master Baker David Chen',
                'description' => 'Master the fundamentals of artisan bread baking from scratch.',
                'activity' => 'Workshop',
                'location' => 'Baking Workshop Room',
                'event_date' => Carbon::now()->addDays(21)->setTime(9, 0),
                'status' => 'upcoming',
                'is_active' => true,
            ],
            [
                'title' => 'Kids Baking Party',
                'presenter' => 'Chef Emma Watson',
                'description' => 'A fun baking experience for children ages 6-12. Parents welcome!',
                'activity' => 'Party',
                'location' => 'Party Room',
                'event_date' => Carbon::now()->addDays(10)->setTime(15, 0),
                'status' => 'upcoming',
                'is_active' => true,
            ],
            [
                'title' => 'Summer Dessert Festival',
                'presenter' => 'Multiple Chefs',
                'description' => 'Annual celebration featuring our seasonal summer desserts and special offers.',
                'activity' => 'Festival',
                'location' => 'Outdoor Garden Area',
                'event_date' => Carbon::now()->subDays(30)->setTime(11, 0),
                'status' => 'completed',
                'is_active' => false,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }

        $this->command->info('Events seeded successfully!');
    }
}

