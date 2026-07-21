# Prompt 4 Implementation Summary - Database Schema

## ✅ What Has Been Created

### Migrations (9 tables)
1. ✅ `menu_categories` - id, name, slug, description, is_active, order
2. ✅ `media` - id, disk, path, mime, size, width, height, title, alt, created_by
3. ✅ `menu_items` - id, category_id, name, slug, description, price, is_special, is_active, image_id, order (soft deletes)
4. ✅ `specialties` - id, title, slug, excerpt, description, image_id, is_active, order (soft deletes)
5. ✅ `gallery_items` - id, title, caption, image_id, is_active, order (soft deletes)
6. ✅ `contact_messages` - id, name, email, phone, subject, message, is_read, read_at, read_by
7. ✅ `orders` - id, code, customer_name, phone, email, order_type, status, scheduled_at, note, total
8. ✅ `order_items` - id, order_id, menu_item_id, name, qty, unit_price, total
9. ✅ `settings` - id, group, key, value, type

### Models (9 models with relationships)
All models created with:
- ✅ Eloquent relationships
- ✅ Soft deletes where applicable
- ✅ Slug auto-generation
- ✅ Order auto-calculation (for orders)
- ✅ Helper methods

### JsonResources (10 resources)
All resources created for standardized API output

### Example Implementation
- ✅ MenuCategoryController (full CRUD with search, filter, pagination)
- ✅ StoreMenuCategoryRequest & UpdateMenuCategoryRequest (validation)

## 📋 Remaining Controllers to Create

Follow the same pattern as `MenuCategoryController`. Here's the template:

### Controller Pattern
```php
<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\{Model}\{StoreModel}Request;
use App\Http\Requests\{Model}\{UpdateModel}Request;
use App\Http\Resources\{Model}Resource;
use App\Models\{Model};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class {Model}Controller extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $query = {Model}::query();

        // Search
        if (request()->has('search')) {
            $query->where('name', 'like', "%{request('search')}%");
            // or 'title' for specialties/gallery
        }

        // Filters
        if (request()->has('is_active')) {
            $query->where('is_active', request('is_active'));
        }
        if (request()->has('category_id')) {
            $query->where('category_id', request('category_id'));
        }
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        // Date ranges
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

        return {Model}Resource::collection($query->paginate(request('per_page', 15)));
    }

    public function store(Store{Model}Request $request): JsonResponse
    {
        ${model} = {Model}::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => '{Model} created successfully.',
            'data' => new {Model}Resource(${model}),
        ], 201);
    }

    public function show({Model} ${model}): {Model}Resource
    {
        return new {Model}Resource(${model});
    }

    public function update(Update{Model}Request $request, {Model} ${model}): JsonResponse
    {
        ${model}->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => '{Model} updated successfully.',
            'data' => new {Model}Resource(${model}),
        ]);
    }

    public function destroy({Model} ${model}): JsonResponse
    {
        ${model}->delete();
        return response()->json([
            'success' => true,
            'message' => '{Model} deleted successfully.',
        ]);
    }
}
```

## 🔧 Controllers Needed

### 1. MenuItemController
**Location:** `api/app/Http/Controllers/Api/V1/MenuItemController.php`
- Search by: name, description
- Filters: category_id, is_active, is_special
- Sort by: order, name, price, created_at

**FormRequests:**
- `api/app/Http/Requests/MenuItem/StoreMenuItemRequest.php`
  - Rules: name, slug (nullable, unique), description, price (required, numeric, min:0), category_id, is_special, is_active, image_id, order
  - Permission: `menus.create`

- `api/app/Http/Requests/MenuItem/UpdateMenuItemRequest.php`
  - Same rules but slug unique except current item
  - Permission: `menus.update`

### 2. SpecialtyController
**Location:** `api/app/Http/Controllers/Api/V1/SpecialtyController.php`
- Search by: title, excerpt, description
- Filters: is_active
- Sort by: order, title, created_at

**FormRequests:**
- `api/app/Http/Requests/Specialty/StoreSpecialtyRequest.php`
  - Rules: title, slug (nullable, unique), excerpt, description, image_id, is_active, order
  - Permission: `specialties.create`

- `api/app/Http/Requests/Specialty/UpdateSpecialtyRequest.php`
  - Same rules
  - Permission: `specialties.update`

### 3. GalleryItemController
**Location:** `api/app/Http/Controllers/Api/V1/GalleryItemController.php`
- Search by: title, caption
- Filters: is_active
- Sort by: order, title, created_at

**FormRequests:**
- `api/app/Http/Requests/GalleryItem/StoreGalleryItemRequest.php`
  - Rules: title, caption, image_id (required), is_active, order
  - Permission: `gallery.create`

- `api/app/Http/Requests/GalleryItem/UpdateGalleryItemRequest.php`
  - Same rules
  - Permission: `gallery.update`

### 4. ContactMessageController
**Location:** `api/app/Http/Controllers/Api/V1/ContactMessageController.php`
- Search by: name, email, subject, message
- Filters: is_read
- Sort by: created_at, read_at
- Special: `markAsRead()` method

**FormRequests:**
- `api/app/Http/Requests/ContactMessage/UpdateContactMessageRequest.php`
  - Rules: is_read (boolean)
  - Permission: `messages.update`

### 5. OrderController
**Location:** `api/app/Http/Controllers/Api/V1/OrderController.php`
- Search by: code, customer_name, phone, email
- Filters: status, order_type
- Sort by: created_at, scheduled_at, total
- Special methods:
  - `updateStatus()` - Update order status
  - `export()` - Export to CSV

**FormRequests:**
- `api/app/Http/Requests/Order/StoreOrderRequest.php`
  - Rules: customer_name, phone, email (nullable), order_type, scheduled_at (nullable), note, items (array, required, min:1)
  - Items: menu_item_id (nullable), name, qty (required, min:1), unit_price
  - Permission: `orders.create`

- `api/app/Http/Requests/Order/UpdateOrderRequest.php`
  - Rules: status, scheduled_at, note
  - Permission: `orders.update`

### 6. SettingController
**Location:** `api/app/Http/Controllers/Api/V1/SettingController.php`
- Special controller (not standard CRUD)
- Methods:
  - `index()` - Get all settings grouped
  - `getGroup($group)` - Get settings by group
  - `update()` - Bulk update settings

**FormRequests:**
- `api/app/Http/Requests/Setting/UpdateSettingRequest.php`
  - Rules: settings (array), settings.*.key, settings.*.value, settings.*.type
  - Permission: `settings.update`

## 🛡️ Policies Needed

Create policies for authorization checks:

### MenuCategoryPolicy
```php
// api/app/Policies/MenuCategoryPolicy.php
public function viewAny(User $user) { return $user->can('menus.view'); }
public function view(User $user, MenuCategory $category) { return $user->can('menus.view'); }
public function create(User $user) { return $user->can('menus.create'); }
public function update(User $user, MenuCategory $category) { return $user->can('menus.update'); }
public function delete(User $user, MenuCategory $category) { return $user->can('menus.delete'); }
```

Repeat for: MenuItem, Specialty, GalleryItem, ContactMessage, Order, Setting

## 📍 Routes to Add

Add to `api/routes/api.php` inside the `Route::middleware('auth:api')->group()`:

```php
// Menu Categories
Route::apiResource('menu-categories', MenuCategoryController::class);

// Menu Items
Route::apiResource('menu-items', MenuItemController::class);

// Specialties
Route::apiResource('specialties', SpecialtyController::class);

// Gallery
Route::apiResource('gallery', GalleryItemController::class);

// Messages
Route::apiResource('messages', ContactMessageController::class);
Route::post('/messages/{message}/read', [ContactMessageController::class, 'markAsRead']);

// Orders
Route::apiResource('orders', OrderController::class);
Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);
Route::get('/orders/export', [OrderController::class, 'export']);

// Settings
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/settings/{group}', [SettingController::class, 'getGroup']);
Route::put('/settings', [SettingController::class, 'update']);
```

## 🚀 Quick Setup Commands

```bash
cd api

# Generate remaining controllers (or create manually)
php artisan make:controller Api/V1/MenuItemController --resource
php artisan make:controller Api/V1/SpecialtyController --resource
php artisan make:controller Api/V1/GalleryItemController --resource
php artisan make:controller Api/V1/ContactMessageController --resource
php artisan make:controller Api/V1/OrderController --resource
php artisan make:controller Api/V1/SettingController

# Generate policies
php artisan make:policy MenuCategoryPolicy --model=MenuCategory
php artisan make:policy MenuItemPolicy --model=MenuItem
php artisan make:policy SpecialtyPolicy --model=Specialty
php artisan make:policy GalleryItemPolicy --model=GalleryItem
php artisan make:policy ContactMessagePolicy --model=ContactMessage
php artisan make:policy OrderPolicy --model=Order
php artisan make:policy SettingPolicy --model=Setting

# Run migrations
php artisan migrate
```

## ✅ Features Implemented

- ✅ All migrations with proper indexes and foreign keys
- ✅ All models with relationships and soft deletes
- ✅ All JsonResources for consistent API responses
- ✅ Example controller (MenuCategoryController) with full CRUD
- ✅ Example FormRequests with validation and authorization
- ✅ Search functionality (name/title fields)
- ✅ Filtering (is_active, category_id, status, etc.)
- ✅ Pagination support
- ✅ Sorting support
- ✅ Date range filtering
- ✅ Slug auto-generation
- ✅ Order total calculation (automatic in OrderItem model)

## 📝 Next Steps

1. Generate remaining controllers using artisan commands or copy the pattern
2. Create remaining FormRequests following the MenuCategory example
3. Create Policies for authorization
4. Add routes to `api/routes/api.php`
5. Test all endpoints
6. Proceed to Prompt 5 (Media upload endpoints)

