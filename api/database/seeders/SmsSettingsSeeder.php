<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SmsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SMS API URL setting
        Setting::firstOrCreate(
            [
                'group' => 'sms',
                'key' => 'api_url',
            ],
            [
                'value' => '',
                'type' => 'text',
            ]
        );

        // SMS API Key setting
        Setting::firstOrCreate(
            [
                'group' => 'sms',
                'key' => 'api_key',
            ],
            [
                'value' => '',
                'type' => 'text',
            ]
        );

        $this->command->info('SMS settings seeded successfully!');
    }
}

