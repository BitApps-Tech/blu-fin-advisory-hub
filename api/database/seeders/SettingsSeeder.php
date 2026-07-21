<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            // General Settings
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'MamoKacha',
                'type' => 'text',
            ],
            [
                'group' => 'general',
                'key' => 'site_description',
                'value' => 'Ethiopian coffee, cafés, and agro-processing products',
                'type' => 'text',
            ],
            [
                'group' => 'general',
                'key' => 'site_logo',
                'value' => '',
                'type' => 'image',
            ],
            [
                'group' => 'general',
                'key' => 'site_favicon',
                'value' => '',
                'type' => 'image',
            ],
            
            // Contact Settings
            [
                'group' => 'contact',
                'key' => 'email',
                'value' => 'contact@mamokacha.com',
                'type' => 'text',
            ],
            [
                'group' => 'contact',
                'key' => 'phone',
                'value' => '+251912345678',
                'type' => 'text',
            ],
            [
                'group' => 'contact',
                'key' => 'address',
                'value' => 'Addis Ababa, Ethiopia',
                'type' => 'text',
            ],
            [
                'group' => 'contact',
                'key' => 'support_email',
                'value' => 'support@mamokacha.com',
                'type' => 'text',
            ],
            
            // Business Hours
            [
                'group' => 'business',
                'key' => 'opening_hours',
                'value' => '7:00 AM - 9:30 PM',
                'type' => 'text',
            ],
            [
                'group' => 'business',
                'key' => 'weekdays',
                'value' => 'Monday - Sunday',
                'type' => 'text',
            ],
            [
                'group' => 'business',
                'key' => 'weekend_hours',
                'value' => '7:00 AM - 9:30 PM',
                'type' => 'text',
            ],
            [
                'group' => 'business',
                'key' => 'closed_days',
                'value' => 'Sunday',
                'type' => 'text',
            ],
            
            // Social Media
            [
                'group' => 'social',
                'key' => 'facebook',
                'value' => '',
                'type' => 'text',
            ],
            [
                'group' => 'social',
                'key' => 'instagram',
                'value' => '',
                'type' => 'text',
            ],
            [
                'group' => 'social',
                'key' => 'twitter',
                'value' => '',
                'type' => 'text',
            ],
            [
                'group' => 'social',
                'key' => 'youtube',
                'value' => '',
                'type' => 'text',
            ],
            
            // Order Settings
            [
                'group' => 'orders',
                'key' => 'min_order_amount',
                'value' => '50',
                'type' => 'text',
            ],
            [
                'group' => 'orders',
                'key' => 'delivery_fee',
                'value' => '20',
                'type' => 'text',
            ],
            [
                'group' => 'orders',
                'key' => 'free_delivery_threshold',
                'value' => '500',
                'type' => 'text',
            ],
            [
                'group' => 'orders',
                'key' => 'accept_orders',
                'value' => '1',
                'type' => 'boolean',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['group' => $setting['group'], 'key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

