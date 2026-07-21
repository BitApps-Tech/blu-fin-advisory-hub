<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicApi\StoreOrderRequest;
use App\Http\Requests\PublicApi\StoreContactRequest;
use App\Http\Requests\PublicApi\StoreFeedbackRequest;
use Illuminate\Http\Request;
use App\Http\Resources\GalleryItemResource;
use App\Http\Resources\MenuItemResource;
use App\Http\Resources\MenuCategoryResource;
use App\Http\Resources\SpecialtyResource;
use App\Http\Resources\SettingResource;
use App\Models\ContactMessage;
use App\Models\CustomerFeedback;
use App\Rules\EthiopianPhone;
use App\Services\CustomerPhoneService;
use App\Services\NewsletterEmailService;
use App\Services\OrderSmsService;
use App\Models\Event;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Specialty;
use App\Models\GalleryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class PublicController extends Controller
{
    /**
     * Get menu categories with nested items.
     *
     * @return AnonymousResourceCollection
     */
    public function menuCategories()
    {
        // Reduced cache time to 60 seconds for real-time updates
        // Cache will be cleared automatically when menu items/categories are updated
        $categories = Cache::remember('public.menu_categories', 60, function () {
            return MenuCategory::where('is_active', true)
                ->with(['activeMenuItems' => function ($query) {
                    $query->where('is_active', true)
                        ->with('image')
                        ->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        });

        return MenuCategoryResource::collection($categories);
    }

    /**
     * Get menu items.
     *
     * @return AnonymousResourceCollection
     */
    public function menuItems()
    {
        $query = MenuItem::with(['category', 'image'])
            ->where('is_active', true);

        if (request()->has('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        if (request()->has('is_special')) {
            $query->where('is_special', request('is_special') == '1' || request('is_special') === true);
        }

        $items = $query->orderBy('order')->get();

        return MenuItemResource::collection($items);
    }

    /**
     * Get specialties.
     *
     * @return AnonymousResourceCollection
     */
    public function specialties()
    {
        $specialties = Cache::remember('public.specialties', 3600, function () {
            return Specialty::where('is_active', true)
                ->with('image')
                ->orderBy('order')
                ->get();
        });

        return SpecialtyResource::collection($specialties);
    }

    /**
     * Get gallery items.
     *
     * @return AnonymousResourceCollection
     */
    public function gallery(Request $request)
    {
        $category = $request->query('category');
        $allowed = GalleryItem::CATEGORIES;

        if ($category && in_array($category, $allowed, true)) {
            $cacheKey = "public.gallery.{$category}";
            $gallery = Cache::remember($cacheKey, 3600, function () use ($category) {
                return GalleryItem::where('is_active', true)
                    ->where('category', $category)
                    ->with('image')
                    ->orderBy('order')
                    ->get();
            });
        } else {
            $gallery = Cache::remember('public.gallery', 3600, function () {
                return GalleryItem::where('is_active', true)
                    ->with('image')
                    ->orderBy('order')
                    ->get();
            });
        }

        return GalleryItemResource::collection($gallery);
    }

    /**
     * Store contact message.
     *
     * @param StoreContactRequest $request
     * @return JsonResponse
     */
    public function contact(StoreContactRequest $request)
    {
        // Rate limiting: 5 messages per hour per IP
        $key = 'contact:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many contact requests. Please try again later.',
            ], 429);
        }

        RateLimiter::hit($key, 3600); // 1 hour

        $validated = $request->validated();

        $phoneService = app(CustomerPhoneService::class);
        $validated['phone'] = $phoneService->normalize($validated['phone']);
        $phoneService->recordIfNew($validated['phone'], $validated['name']);

        app(NewsletterEmailService::class)->recordIfNew(
            $validated['email'] ?? null,
            $validated['name'],
            'contact'
        );

        $message = ContactMessage::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message. We will get back to you soon.',
            'data' => [
                'id' => $message->id,
            ],
        ], 201);
    }

    /**
     * Store customer feedback questionnaire.
     */
    public function feedback(StoreFeedbackRequest $request): JsonResponse
    {
        $key = 'feedback:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many feedback submissions. Please try again later.',
            ], 429);
        }

        RateLimiter::hit($key, 3600);

        $validated = $request->validated();

        if (!empty($validated['phone'])) {
            $phoneService = app(CustomerPhoneService::class);
            $validated['phone'] = $phoneService->normalize($validated['phone']);
            $phoneService->recordIfNew($validated['phone']);
        }

        $feedback = CustomerFeedback::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback. We appreciate you taking the time to share your experience.',
            'data' => [
                'id' => $feedback->id,
            ],
        ], 201);
    }

    /**
     * Store public order.
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function order(StoreOrderRequest $request)
    {
        // Rate limiting: 10 orders per hour per IP
        $key = 'order:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many order requests. Please try again later.',
            ], 429);
        }

        RateLimiter::hit($key, 3600); // 1 hour

        $validated = $request->validated();
        $items = $validated['items'];
        unset($validated['items']);

        $phoneService = app(CustomerPhoneService::class);
        $validated['phone'] = $phoneService->normalize($validated['phone']);
        $phoneService->recordIfNew($validated['phone'], $validated['customer_name']);

        if (!empty($validated['email'])) {
            app(NewsletterEmailService::class)->recordIfNew(
                $validated['email'],
                $validated['customer_name'],
                'order'
            );
        }

        $order = Order::create($validated);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item['menu_item_id'] ?? null,
                'name' => $item['name'],
                'qty' => $item['qty'],
                'unit_price' => $item['unit_price'],
            ]);
        }

        $order->refresh();
        $order->load('items');

        app(OrderSmsService::class)->sendOrderPlacedNotification($order);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully.',
            'data' => [
                'code' => $order->code,
                'order' => [
                    'id' => $order->id,
                    'code' => $order->code,
                    'status' => $order->status,
                    'total' => (float) $order->total,
                ],
            ],
        ], 201);
    }

    /**
     * Get public settings (read-only).
     *
     * @return JsonResponse
     */
    public function settings()
    {
        $settings = Cache::remember('public.settings', 3600, function () {
            return Setting::whereIn('group', ['site', 'seo', 'social'])
                ->get()
                ->groupBy('group');
        });

        $grouped = $settings->map(function ($group) {
            return SettingResource::collection($group);
        });

        return response()->json([
            'success' => true,
            'data' => $grouped,
        ]);
    }

    /**
     * Get the most recent active event.
     *
     * @return JsonResponse
     */
    public function latestEvent()
    {
        $event = Cache::remember('public.latest_event', 1800, function () {
            return Event::where('is_active', true)
                ->whereIn('status', ['upcoming', 'ongoing'])
                ->where('event_date', '>=', now())
                ->with('image')
                ->orderBy('event_date', 'asc')
                ->first();
        });

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'No active events found.',
            ], 404);
        }

        // Get full image URL
        $imageUrl = null;
        if ($event->image) {
            // Use the Media model's url accessor for full URL
            $imageUrl = $event->image->url;
            // If the URL doesn't start with http, prepend the app URL
            if (!str_starts_with($imageUrl, 'http')) {
                $imageUrl = url($imageUrl);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $event->id,
                'title' => $event->title,
                'presenter' => $event->presenter,
                'description' => $event->description,
                'activity' => $event->activity,
                'location' => $event->location,
                'event_date' => $event->event_date->toIso8601String(),
                'status' => $event->status,
                'image' => $event->image ? [
                    'id' => $event->image->id,
                    'url' => $imageUrl,
                    'alt' => $event->image->alt,
                ] : null,
            ],
        ]);
    }

    /**
     * Get all active events.
     *
     * @return JsonResponse
     */
    public function activeEvents()
    {
        $events = Cache::remember('public.active_events', 1800, function () {
            return Event::where('is_active', true)
                ->whereIn('status', ['upcoming', 'ongoing'])
                ->where('event_date', '>=', now())
                ->with('image')
                ->orderBy('event_date', 'asc')
                ->get();
        });

        if ($events->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No active events found.',
                'data' => [],
            ], 404);
        }

        $eventsData = $events->map(function ($event) {
            // Get full image URL
            $imageUrl = null;
            if ($event->image) {
                $imageUrl = $event->image->url;
                if (!str_starts_with($imageUrl, 'http')) {
                    $imageUrl = url($imageUrl);
                }
            }

            return [
                'id' => $event->id,
                'title' => $event->title,
                'presenter' => $event->presenter,
                'description' => $event->description,
                'activity' => $event->activity,
                'location' => $event->location,
                'event_date' => $event->event_date->toIso8601String(),
                'status' => $event->status,
                'image' => $event->image ? [
                    'id' => $event->image->id,
                    'url' => $imageUrl,
                    'alt' => $event->image->alt,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $eventsData,
        ]);
    }

    /**
     * Subscribe an email or phone number.
     *
     * Footer email signups are stored as newsletter subscribers. Phone signups
     * from the popup remain stored as customers for the existing SMS workflow.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function subscribe(Request $request)
    {
        // Rate limiting: 3 subscriptions per hour per IP
        $key = 'subscribe:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many subscription requests. Please try again later.',
            ], 429);
        }

        $validated = $request->validate([
            'email' => 'nullable|required_without:phone|email|max:255',
            'phone' => ['nullable', 'required_without:email', 'string', 'max:20', new EthiopianPhone()],
            'name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:100',
        ]);

        if (!empty($validated['phone'])) {
            $validated['phone'] = preg_replace('/\s+/', '', $validated['phone']);
        }

        if (!empty($validated['email'])) {
            $email = strtolower(trim($validated['email']));
            $source = $validated['source'] ?? 'footer';
            $newsletterService = app(NewsletterEmailService::class);
            $subscriber = $newsletterService->recordIfNew(
                $email,
                $validated['name'] ?? null,
                $source
            );

            RateLimiter::hit($key, 3600); // 1 hour

            return response()->json([
                'success' => true,
                'message' => $newsletterService->wasNewlyCreated($subscriber)
                    ? 'Thank you for subscribing! You will receive exclusive offers and coffee stories.'
                    : 'This email is already subscribed. Thank you for staying connected!',
                'already_subscribed' => !$newsletterService->wasNewlyCreated($subscriber),
                'data' => [
                    'id' => $subscriber->id,
                    'subscribed' => true,
                ],
            ], $newsletterService->wasNewlyCreated($subscriber) ? 201 : 200);
        }

        $phoneService = app(CustomerPhoneService::class);
        $phone = $phoneService->normalize($validated['phone']);
        $customer = $phoneService->recordIfNew($phone, $validated['name'] ?? null);

        if ($customer && !$phoneService->wasNewlyCreated($customer)) {
            return response()->json([
                'success' => true,
                'message' => 'You have already submitted your phone number. Thank you for your continued support!',
                'already_subscribed' => true,
                'data' => [
                    'id' => $customer->id,
                    'subscribed' => true,
                ],
            ], 200);
        }

        RateLimiter::hit($key, 3600); // 1 hour

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing! You will receive exclusive deals and offers.',
            'data' => [
                'id' => $customer->id,
                'subscribed' => true,
            ],
        ], 201);
    }
}

