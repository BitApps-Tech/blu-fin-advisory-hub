<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CateringRequest;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class StatsController extends Controller
{
    /**
     * Get dashboard statistics
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'messages_unread' => ContactMessage::where('is_read', false)->count(),
            'gallery_count' => GalleryItem::count(),
            'menu_items_count' => MenuItem::count(),
            'orders_today_revenue' => Order::whereDate('created_at', today())
                ->whereIn('status', ['completed', 'ready', 'preparing'])
                ->sum('total'),
            'recent_messages' => ContactMessage::latest()
                ->take(5)
                ->get(['id', 'name', 'subject', 'is_read', 'created_at'])
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'name' => $message->name,
                        'subject' => $message->subject,
                        'is_read' => $message->is_read,
                        'status' => $message->is_read ? 'read' : 'unread',
                        'created_at' => $message->created_at->toIso8601String(),
                    ];
                }),
        ]);
    }

    /**
     * Get notification statistics (optional endpoint for notifications dropdown)
     *
     * @return JsonResponse
     */
    public function notifications(): JsonResponse
    {
        $notifications = [];

        // Pending orders needing attention
        $pendingOrders = Order::where('status', 'pending')
            ->latest()
            ->get();

        foreach ($pendingOrders as $order) {
            $notifications[] = [
                'id' => 'order_' . $order->id,
                'type' => 'order',
                'title' => 'New Order #' . $order->code,
                'message' => "Order from {$order->customer_name}",
                'time' => $order->created_at->diffForHumans(),
                'unread' => true,
                'created_at' => $order->created_at->toIso8601String(),
            ];
        }

        // Low stock alerts (if inventory tracking is enabled)
        if (Schema::hasColumn('menu_items', 'stock_quantity')) {
            $lowStockItems = MenuItem::where('is_active', true)
                ->whereNotNull('stock_quantity')
                ->where('stock_quantity', '<', 10)
                ->get();

            foreach ($lowStockItems as $item) {
                $notifications[] = [
                    'id' => 'stock_' . $item->id,
                    'type' => 'alert',
                    'title' => 'Low Stock Alert',
                    'message' => "{$item->name} inventory is low",
                    'time' => now()->diffForHumans(),
                    'unread' => true,
                    'created_at' => now()->toIso8601String(),
                ];
            }
        }

        // Unread contact messages
        $unreadMessages = ContactMessage::where('is_read', false)
            ->latest()
            ->get();

        foreach ($unreadMessages as $message) {
            $notifications[] = [
                'id' => 'message_' . $message->id,
                'type' => 'message',
                'title' => 'New Message',
                'message' => "From {$message->name}: {$message->subject}",
                'time' => $message->created_at->diffForHumans(),
                'unread' => true,
                'created_at' => $message->created_at->toIso8601String(),
            ];
        }

        // Upcoming active events
        $upcomingEvents = Event::where('is_active', true)
            ->where('status', 'upcoming')
            ->orderBy('event_date')
            ->get();

        foreach ($upcomingEvents as $event) {
            $notifications[] = [
                'id' => 'event_' . $event->id,
                'type' => 'event',
                'title' => 'Upcoming Event',
                'message' => $event->title,
                'time' => $event->event_date?->diffForHumans() ?? $event->created_at->diffForHumans(),
                'unread' => true,
                'created_at' => ($event->event_date ?? $event->created_at)->toIso8601String(),
            ];
        }

        // Pending catering requests
        $pendingCatering = CateringRequest::where('status', 'pending')
            ->latest()
            ->get();

        foreach ($pendingCatering as $request) {
            $notifications[] = [
                'id' => 'catering_' . $request->id,
                'type' => 'catering',
                'title' => 'Catering Request',
                'message' => "{$request->customer_name} — {$request->event_type}",
                'time' => $request->created_at->diffForHumans(),
                'unread' => true,
                'created_at' => $request->created_at->toIso8601String(),
            ];
        }

        usort($notifications, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $countByType = function (string $type) use ($notifications): int {
            return count(array_filter($notifications, fn ($n) => $n['type'] === $type && $n['unread']));
        };

        return response()->json([
            'data' => $notifications,
            'unread_count' => count(array_filter($notifications, fn ($n) => $n['unread'])),
            'counts' => [
                'order' => $countByType('order'),
                'message' => $countByType('message'),
                'event' => $countByType('event'),
                'catering' => $countByType('catering'),
                'alert' => $countByType('alert'),
            ],
        ]);
    }
}

