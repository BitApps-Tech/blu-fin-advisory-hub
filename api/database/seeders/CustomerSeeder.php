<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'Alice Thompson',
                // 'email' => 'alice.thompson@email.com',
                'phone' => '+1 555 1001',
                // 'address' => '123 Maple Street, New York, NY 10001',
                // 'date_of_birth' => Carbon::parse('1985-05-15'),
                // 'customer_type' => 'vip',
                // 'total_orders' => 28,
                // 'total_spent' => 2340.50,
                // 'loyalty_points' => 234,
                // 'notes' => 'Prefers chocolate-based desserts. Regular customer.',
                // 'is_active' => true,
            ],
            [
                'name' => 'Robert Garcia',
                // 'email' => 'robert.g@email.com',
                'phone' => '+1 555 1002',
                // 'address' => '456 Oak Avenue, Brooklyn, NY 11201',
                // 'date_of_birth' => Carbon::parse('1990-08-22'),
                // 'customer_type' => 'regular',
                // 'total_orders' => 12,
                // 'total_spent' => 780.00,
                // 'loyalty_points' => 78,
                // 'is_active' => true,
            ],
            [
                'name' => 'Global Tech Inc',
                // 'email' => 'events@globaltech.com',
                 'phone' => '+1 555 2000',
                // 'address' => '789 Business Blvd, Manhattan, NY 10022',
                // 'customer_type' => 'corporate',
                // 'total_orders' => 45,
                // 'total_spent' => 12500.00,
                // 'loyalty_points' => 1250,
                // 'notes' => 'Monthly corporate orders. Invoice required.',
                // 'is_active' => true,
            ],
            [
                'name' => 'Lisa Martinez',
                // 'email' => 'lisa.martinez@email.com',
                'phone' => '+1 555 1003',
                // 'address' => '321 Pine Road, Queens, NY 11354',
                // 'date_of_birth' => Carbon::parse('1988-12-10'),
                // 'customer_type' => 'regular',
                // 'total_orders' => 8,
                // 'total_spent' => 420.00,
                // 'loyalty_points' => 42,
                // 'is_active' => true,
            ],
            [
                'name' => 'David Kim',
                // 'email' => 'david.kim@email.com',
                'phone' => '+1 555 1004',
                // 'address' => '654 Elm Street, Bronx, NY 10451',
                // 'date_of_birth' => Carbon::parse('1982-03-28'),
                // 'customer_type' => 'vip',
                // 'total_orders' => 35,
                // 'total_spent' => 3200.00,
                // 'loyalty_points' => 320,
                // 'notes' => 'Allergic to nuts. Always specify nut-free options.',
                // 'is_active' => true,
            ],
            [
                'name' => 'Jennifer Lee',
                // 'email' => 'jennifer.lee@email.com',
                'phone' => '+1 555 1005',
                // 'address' => '987 Cedar Lane, Staten Island, NY 10301',
                // 'customer_type' => 'regular',
                // 'total_orders' => 5,
                // 'total_spent' => 215.00,
                // 'loyalty_points' => 21,
                // 'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $this->command->info('Customers seeded successfully!');
    }
}
