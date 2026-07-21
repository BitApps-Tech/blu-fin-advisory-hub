<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsLog;
use Carbon\Carbon;

class SmsLogSeeder extends Seeder
{
    public function run()
    {
        $smsLogs = [
            [
                'recipient_phone' => '+1 555 1001',
                'recipient_name' => 'Alice Thompson',
                'message' => 'Hi Alice! Your order #ORD-20251106-0001 is ready for pickup. Thank you!',
                'type' => 'transactional',
                'status' => 'delivered',
                'sms_provider' => 'Twilio',
                'message_id' => 'SM' . uniqid(),
                'sent_at' => Carbon::now()->subHours(2),
                'delivered_at' => Carbon::now()->subHours(2)->addMinutes(1),
            ],
            [
                'recipient_phone' => '+1 555 1002',
                'recipient_name' => 'Robert Garcia',
                'message' => 'Special offer! Get 20% off on all croissants this weekend. Visit us today!',
                'type' => 'promotional',
                'status' => 'delivered',
                'sms_provider' => 'Twilio',
                'message_id' => 'SM' . uniqid(),
                'sent_at' => Carbon::now()->subDays(1),
                'delivered_at' => Carbon::now()->subDays(1)->addMinutes(2),
            ],
            [
                'recipient_phone' => '+1 555 2000',
                'recipient_name' => 'Global Tech Inc',
                'message' => 'Your catering order for Dec 20th has been confirmed. Total: $1,350. Thank you!',
                'type' => 'transactional',
                'status' => 'delivered',
                'sms_provider' => 'Twilio',
                'message_id' => 'SM' . uniqid(),
                'sent_at' => Carbon::now()->subHours(5),
                'delivered_at' => Carbon::now()->subHours(5)->addMinutes(1),
            ],
            [
                'recipient_phone' => '+1 555 1003',
                'recipient_name' => 'Lisa Martinez',
                'message' => 'Reminder: Pastry workshop this Saturday at 2 PM. See you there!',
                'type' => 'notification',
                'status' => 'sent',
                'sms_provider' => 'Twilio',
                'message_id' => 'SM' . uniqid(),
                'sent_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'recipient_phone' => '+1 555 1004',
                'recipient_name' => 'David Kim',
                'message' => 'New specialty items available! Check out our website for details.',
                'type' => 'promotional',
                'status' => 'pending',
            ],
            [
                'recipient_phone' => '+1 555 9999',
                'recipient_name' => 'Test User',
                'message' => 'This is a test message that failed to send.',
                'type' => 'notification',
                'status' => 'failed',
                'error_message' => 'Invalid phone number format',
            ],
        ];

        foreach ($smsLogs as $log) {
            SmsLog::create($log);
        }

        $this->command->info('SMS logs seeded successfully!');
    }
}
