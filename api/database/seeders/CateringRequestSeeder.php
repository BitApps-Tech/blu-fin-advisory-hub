<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CateringRequest;
use Carbon\Carbon;

class CateringRequestSeeder extends Seeder
{
    public function run()
    {
        $requests = [
            [
                'customer_name' => 'John Smith',
                'customer_email' => 'john.smith@email.com',
                'customer_phone' => '+1 555 0101',
                'event_type' => 'Wedding',
                'event_date' => Carbon::now()->addDays(45)->setTime(18, 0),
                'event_location' => 'Grand Hotel Ballroom',
                'guest_count' => 150,
                'menu_preferences' => 'French pastries, wedding cake, dessert buffet',
                'special_requirements' => 'Gluten-free options needed for 10 guests',
                'estimated_budget' => 5000.00,
                'quoted_price' => 4800.00,
                'status' => 'confirmed',
                'notes' => 'VIP client - priority service',
            ],
            [
                'customer_name' => 'Sarah Johnson',
                'customer_email' => 'sarah.j@company.com',
                'customer_phone' => '+1 555 0202',
                'event_type' => 'Corporate',
                'event_date' => Carbon::now()->addDays(15)->setTime(14, 0),
                'event_location' => 'Tech Corp Office',
                'guest_count' => 75,
                'menu_preferences' => 'Assorted pastries, coffee, tea',
                'estimated_budget' => 1500.00,
                'quoted_price' => 1350.00,
                'status' => 'quoted',
            ],
            [
                'customer_name' => 'Michael Brown',
                'customer_email' => 'mbrown@email.com',
                'customer_phone' => '+1 555 0303',
                'event_type' => 'Birthday',
                'event_date' => Carbon::now()->addDays(30)->setTime(16, 0),
                'event_location' => 'Community Center',
                'guest_count' => 50,
                'menu_preferences' => 'Birthday cake, cupcakes, cookies',
                'special_requirements' => 'Nut-free desserts',
                'estimated_budget' => 800.00,
                'status' => 'pending',
            ],
            [
                'customer_name' => 'Emma Wilson',
                'customer_email' => 'emma.w@email.com',
                'customer_phone' => '+1 555 0404',
                'event_type' => 'Baby Shower',
                'event_date' => Carbon::now()->addDays(20)->setTime(13, 0),
                'event_location' => 'Private Residence',
                'guest_count' => 30,
                'menu_preferences' => 'Mini cupcakes, macarons, tea sandwiches',
                'estimated_budget' => 600.00,
                'quoted_price' => 550.00,
                'status' => 'confirmed',
            ],
        ];

        foreach ($requests as $request) {
            CateringRequest::create($request);
        }

        $this->command->info('Catering requests seeded successfully!');
    }
}
