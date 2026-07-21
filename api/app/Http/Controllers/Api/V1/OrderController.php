<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderPlaced;
use App\Services\CustomerPhoneService;
use App\Services\EmailDeliveryService;
use App\Services\NewsletterEmailService;
use App\Services\OrderSmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $query = Order::query()->with('items.menuItem');

        // Search by code, customer_name, phone, or email
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        // Filter by order_type
        if (request()->has('order_type')) {
            $query->where('order_type', request('order_type'));
        }

        // Date range filter
        if (request()->has('from_date')) {
            $query->whereDate('created_at', '>=', request('from_date'));
        }
        if (request()->has('to_date')) {
            $query->whereDate('created_at', '<=', request('to_date'));
        }

        // Sort
        $sortBy = request('sort_by', 'created_at');
        $sortOrder = request('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(request('per_page', 15));

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        
        // Handle items if provided (for customer orders)
        $items = $validated['items'] ?? [];
        unset($validated['items']);

        $phoneService = app(CustomerPhoneService::class);
        if (!empty($validated['phone'])) {
            $validated['phone'] = $phoneService->normalize($validated['phone']);
            $phoneService->recordIfNew($validated['phone'], $validated['customer_name'] ?? null);
        }

        if (!empty($validated['email'])) {
            app(NewsletterEmailService::class)->recordIfNew(
                $validated['email'],
                $validated['customer_name'] ?? null,
                'admin-order'
            );
        }

        // Generate unique order code if not provided
        if (!isset($validated['code'])) {
            $validated['code'] = 'ORD-' . strtoupper(uniqid());
        }

        $order = Order::create($validated);

        // Create order items if provided
        if (!empty($items)) {
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'] ?? null,
                    'name' => $item['name'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['qty'] * $item['unit_price'],
                ]);
            }
            // Recalculate total
            $order->refresh();
        }

        $order->load('items.menuItem');

        // Send email notification to admin
        $adminEmail = config('mail.from.address') ?? env('MAIL_FROM_ADDRESS');
        if ($adminEmail) {
            app(EmailDeliveryService::class)->sendOrLog(
                $adminEmail,
                new OrderPlaced($order),
                null,
                false,
                'Order Notification',
                'Failed to send order notification'
            );
        }

        app(OrderSmsService::class)->sendOrderPlacedNotification($order);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'data' => new OrderResource($order),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return OrderResource
     */
    public function show(Order $order)
    {
        $order->load('items.menuItem');
        return new OrderResource($order);
    }

    /**
     * Get order by code (public access for customers).
     *
     * @param string $code
     * @return JsonResponse|OrderResource
     */
    public function viewByCode(string $code)
    {
        $order = Order::where('code', $code)->with('items.menuItem')->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return JsonResponse
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();

        $phoneService = app(CustomerPhoneService::class);
        if (!empty($validated['phone'])) {
            $validated['phone'] = $phoneService->normalize($validated['phone']);
            $phoneService->recordIfNew(
                $validated['phone'],
                $validated['customer_name'] ?? $order->customer_name
            );
        }

        if (!empty($validated['email'])) {
            app(NewsletterEmailService::class)->recordIfNew(
                $validated['email'],
                $validated['customer_name'] ?? $order->customer_name,
                'admin-order'
            );
        }

        $order->update($validated);
        $order->load('items.menuItem');

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
            'data' => new OrderResource($order),
        ]);
    }

    /**
     * Update order status.
     *
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return JsonResponse
     */
    public function updateStatus(UpdateOrderRequest $request, Order $order)
    {
        $oldStatus = $order->status;
        $newStatus = $request->input('status');

        $order->update([
            'status' => $newStatus,
        ]);
        $order->load('items.menuItem');

        // Send notification if status changed
        if ($oldStatus !== $newStatus) {
            // Dispatch notification job (optional - can be implemented later)
            // OrderStatusChanged::dispatch($order, $oldStatus, $newStatus);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully.',
            'data' => new OrderResource($order),
        ]);
    }

    /**
     * Export orders to CSV.
     *
     * @return JsonResponse
     */
    public function export()
    {
        $query = Order::query()->with('items');

        // Apply same filters as index
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }
        if (request()->has('from_date')) {
            $query->whereDate('created_at', '>=', request('from_date'));
        }
        if (request()->has('to_date')) {
            $query->whereDate('created_at', '<=', request('to_date'));
        }

        $orders = $query->get();

        $filename = 'orders_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['Code', 'Customer', 'Phone', 'Email', 'Type', 'Status', 'Total', 'Date']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->code,
                    $order->customer_name,
                    $order->phone,
                    $order->email,
                    $order->order_type,
                    $order->status,
                    $order->total,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return JsonResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully.',
        ]);
    }
}
