<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    public function run()
    {
        ContactMessage::create([
            'name' => 'Alice Johnson',
            'email' => 'alice.j@example.com',
            'phone' => '555-0201',
            'subject' => 'Wedding Cake Inquiry',
            'message' => 'Hi! I\'m getting married next summer and would love to discuss options for a custom wedding cake. Do you offer tastings?',
            'is_read' => false,
            'created_at' => now()->subDays(1),
        ]);

        ContactMessage::create([
            'name' => 'Bob Wilson',
            'email' => 'bob.wilson@example.com',
            'phone' => '555-0202',
            'subject' => 'Catering for Corporate Event',
            'message' => 'We\'re hosting a corporate event for 50 people next month. Can you provide pastries and coffee service? Please send me a quote.',
            'is_read' => true,
            'created_at' => now()->subDays(3),
        ]);

        ContactMessage::create([
            'name' => 'Carol Davis',
            'email' => 'carol.d@example.com',
            'phone' => '',
            'subject' => 'General Inquiry',
            'message' => 'What are your business hours? I\'d like to visit this weekend.',
            'is_read' => false,
            'created_at' => now()->subHours(5),
        ]);
    }
}

