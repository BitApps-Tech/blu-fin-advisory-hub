<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Get some menu items for the orders
        $menuItems = MenuItem::all();
        
        if ($menuItems->isEmpty()) {
            $this->command->warn('No menu items found. Please seed menu items first.');
            return;
        }

        $customers = [
            ['name' => 'John Doe', 'phone' => '555-0101', 'email' => 'john.doe@example.com'],
            ['name' => 'Jane Smith', 'phone' => '555-0102', 'email' => 'jane.smith@example.com'],
            ['name' => 'Michael Brown', 'phone' => '555-0103', 'email' => 'michael.b@example.com'],
            ['name' => 'Emily Davis', 'phone' => '555-0104', 'email' => 'emily.d@example.com'],
            ['name' => 'David Wilson', 'phone' => '555-0105', 'email' => 'david.w@example.com'],
            ['name' => 'Sarah Johnson', 'phone' => '555-0106', 'email' => 'sarah.j@example.com'],
            ['name' => 'Robert Miller', 'phone' => '555-0107', 'email' => 'robert.m@example.com'],
            ['name' => 'Lisa Anderson', 'phone' => '555-0108', 'email' => 'lisa.a@example.com'],
            ['name' => 'William Taylor', 'phone' => '555-0109', 'email' => 'william.t@example.com'],
            ['name' => 'Jennifer Martinez', 'phone' => '555-0110', 'email' => 'jennifer.m@example.com'],
        ];

        $orderTypes = ['pickup', 'delivery', 'dinein'];
        $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];
        
        $notes = [
            'Please add extra frosting',
            'Please deliver to the back entrance',
            'Call me when you arrive',
            'No nuts please - allergies',
            'Extra crispy',
            'Birthday celebration - please add candles',
            null,
            'Corporate event - need receipt',
            'Please wrap individually',
            'Rush order if possible',
        ];

        // Create 10 orders
        for ($i = 0; $i < 10; $i++) {
            $customer = $customers[$i];
            $orderCode = 'ORD-' . now()->format('Y') . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT);
            
            // Vary the scheduled dates
            if (in_array($i, [0, 1])) {
                $scheduledAt = now()->addHours(rand(2, 6));
            } elseif (in_array($i, [2, 3])) {
                $scheduledAt = now()->addDays(1)->setHour(rand(10, 18))->setMinute(0);
            } elseif (in_array($i, [4, 5])) {
                $scheduledAt = now()->addDays(2)->setHour(rand(10, 18))->setMinute(0);
            } elseif ($i === 6) {
                $scheduledAt = now()->subHours(2);
            } elseif (in_array($i, [7, 8])) {
                $scheduledAt = now()->subDays(1);
            } else {
                $scheduledAt = now()->subDays(2);
            }

            // Vary the status based on order
            if (in_array($i, [0, 1])) {
                $status = 'pending';
            } elseif (in_array($i, [2, 3])) {
                $status = 'confirmed';
            } elseif ($i === 4) {
                $status = 'preparing';
            } elseif ($i === 5) {
                $status = 'ready';
            } elseif (in_array($i, [6, 7, 8])) {
                $status = 'completed';
            } else {
                $status = 'cancelled';
            }

            // Create order
            $order = Order::create([
                'code' => $orderCode,
                'customer_name' => $customer['name'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
                'order_type' => $orderTypes[$i % 3],
                'status' => $status,
                'scheduled_at' => $scheduledAt,
                'note' => $notes[$i],
                'total' => 0, // Will be calculated from items
            ]);

            // Add random menu items to each order (1-4 items)
            $itemCount = rand(1, 4);
            $orderTotal = 0;
            $usedMenuItems = [];

            for ($j = 0; $j < $itemCount; $j++) {
                // Pick a random menu item that hasn't been used in this order
                do {
                    $menuItem = $menuItems->random();
                } while (in_array($menuItem->id, $usedMenuItems) && count($usedMenuItems) < $menuItems->count());
                
                $usedMenuItems[] = $menuItem->id;
                
                $quantity = rand(1, 3);
                $unitPrice = $menuItem->price;
                $itemTotal = $quantity * $unitPrice;
                $orderTotal += $itemTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'name' => $menuItem->name,
                    'qty' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $itemTotal,
                ]);
            }

            // Update order total
            $order->update(['total' => $orderTotal]);
        }

        $this->command->info('Successfully created 10 sample orders with items!');
    }
}
