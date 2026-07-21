<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        // Site Settings
        Setting::create([
            'group' => 'site',
            'key' => 'site_name',
            'value' => 'MamoKacha',
            'type' => 'text',
        ]);

        Setting::create([
            'group' => 'site',
            'key' => 'site_tagline',
            'value' => 'Ethiopian coffee, cafés, and agro-processing',
            'type' => 'text',
        ]);

        Setting::create([
            'group' => 'site',
            'key' => 'address',
            'value' => 'Addis Ababa, Ethiopia',
            'type' => 'text',
        ]);

        Setting::create([
            'group' => 'site',
            'key' => 'phone',
            'value' => '(555) 123-4567',
            'type' => 'text',
        ]);

        Setting::create([
            'group' => 'site',
            'key' => 'email',
            'value' => 'info@mamokacha.com',
            'type' => 'text',
        ]);

        Setting::create([
            'group' => 'site',
            'key' => 'hours',
            'value' => "Monday - Friday: 8:00 AM - 6:00 PM\nSaturday: 9:00 AM - 5:00 PM\nSunday: 10:00 AM - 4:00 PM",
            'type' => 'text',
        ]);

        // SEO Settings
        Setting::create([
            'group' => 'seo',
            'key' => 'meta_title',
            'value' => 'MamoKacha - Ethiopian Coffee and Agro Processing',
            'type' => 'text',
        ]);

        Setting::create([
            'group' => 'seo',
            'key' => 'meta_description',
            'value' => 'Discover MamoKacha coffee, cafés, dairy, agro-processing products, and events in Ethiopia.',
            'type' => 'text',
        ]);

        // Social Media Settings
        Setting::create([
            'group' => 'social',
            'key' => 'facebook',
            'value' => 'https://facebook.com/mamokacha',
            'type' => 'text',
        ]);

        Setting::create([
            'group' => 'social',
            'key' => 'instagram',
            'value' => 'https://instagram.com/mamokacha',
            'type' => 'text',
        ]);

        // Orders Settings
        Setting::create([
            'group' => 'orders',
            'key' => 'orders_enabled',
            'value' => 'true',
            'type' => 'boolean',
        ]);

        Setting::create([
            'group' => 'orders',
            'key' => 'min_order_amount',
            'value' => '15.00',
            'type' => 'text',
        ]);

        Setting::create([
            'group' => 'orders',
            'key' => 'lead_time_minutes',
            'value' => '30',
            'type' => 'text',
        ]);
    }
}

