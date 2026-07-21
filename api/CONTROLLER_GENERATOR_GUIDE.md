# Controller Generator Guide - Quick Reference

## Generate Controllers & Requests

Run these commands to generate the base structure:

```bash
cd api

# Generate controllers
php artisan make:controller Api/V1/MenuItemController --resource
php artisan make:controller Api/V1/SpecialtyController --resource
php artisan make:controller Api/V1/GalleryItemController --resource
php artisan make:controller Api/V1/ContactMessageController --resource
php artisan make:controller Api/V1/OrderController --resource
php artisan make:controller Api/V1/SettingController

# Generate FormRequests
php artisan make:request MenuItem/StoreMenuItemRequest
php artisan make:request MenuItem/UpdateMenuItemRequest
php artisan make:request Specialty/StoreSpecialtyRequest
php artisan make:request Specialty/UpdateSpecialtyRequest
php artisan make:request GalleryItem/StoreGalleryItemRequest
php artisan make:request GalleryItem/UpdateGalleryItemRequest
php artisan make:request ContactMessage/UpdateContactMessageRequest
php artisan make:request Order/StoreOrderRequest
php artisan make:request Order/UpdateOrderRequest
php artisan make:request Setting/UpdateSettingRequest

# Generate Policies
php artisan make:policy MenuItemPolicy --model=MenuItem
php artisan make:policy SpecialtyPolicy --model=Specialty
php artisan make:policy GalleryItemPolicy --model=GalleryItem
php artisan make:policy ContactMessagePolicy --model=ContactMessage
php artisan make:policy OrderPolicy --model=Order
php artisan make:policy SettingPolicy --model=Setting
```

## Copy Pattern from MenuCategoryController

The `MenuCategoryController` serves as a complete example. Copy its pattern and modify:
1. Model name
2. Resource name
3. Request classes
4. Search fields (name vs title)
5. Filter fields specific to each model

## Key Validation Rules by Model

### MenuItem
- `name`: required|string|max:255
- `slug`: nullable|string|unique:menu_items
- `description`: nullable|string
- `price`: required|numeric|min:0
- `category_id`: required|exists:menu_categories,id
- `is_special`: boolean
- `is_active`: boolean
- `image_id`: nullable|exists:media,id
- `order`: integer|min:0

### Specialty
- `title`: required|string|max:255
- `slug`: nullable|string|unique:specialties
- `excerpt`: nullable|string|max:500
- `description`: nullable|string
- `image_id`: nullable|exists:media,id
- `is_active`: boolean
- `order`: integer|min:0

### GalleryItem
- `title`: required|string|max:255
- `caption`: nullable|string
- `image_id`: required|exists:media,id
- `is_active`: boolean
- `order`: integer|min:0

### Order
- `customer_name`: required|string|max:255
- `phone`: required|string|max:20
- `email`: nullable|email
- `order_type`: required|in:pickup,delivery,dinein
- `scheduled_at`: nullable|date
- `note`: nullable|string
- `items`: required|array|min:1
- `items.*.menu_item_id`: nullable|exists:menu_items,id
- `items.*.name`: required|string
- `items.*.qty`: required|integer|min:1
- `items.*.unit_price`: required|numeric|min:0

### Setting
- `settings`: required|array
- `settings.*.group`: required|string
- `settings.*.key`: required|string
- `settings.*.value`: required
- `settings.*.type`: required|in:text,json,boolean,image

## Permission Checks

Each FormRequest should check permissions:

```php
public function authorize(): bool
{
    return $this->user()->can('{module}.{action}');
    // Examples:
    // menus.create, menus.update, menus.delete
    // specialties.view, specialties.create
    // orders.view, orders.update
}
```

## After Creating Files

1. Update each controller to match MenuCategoryController pattern
2. Add validation rules to FormRequests
3. Add authorization checks to FormRequests
4. Register routes in `api/routes/api.php`
5. Register policies in `app/Providers/AuthServiceProvider.php`
6. Test each endpoint

See `PROMPT4_IMPLEMENTATION_SUMMARY.md` for detailed patterns and examples.

