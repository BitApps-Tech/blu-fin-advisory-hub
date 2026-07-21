<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ImportsCsv;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItem\StoreMenuItemRequest;
use App\Http\Requests\MenuItem\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class MenuItemController extends Controller
{
    use ImportsCsv;
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $query = MenuItem::query()->with(['category', 'image']);

        // Search by name or description
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if (request()->has('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        // Filter by is_active
        if (request()->has('is_active')) {
            $query->where('is_active', request('is_active'));
        }

        // Filter by is_special
        if (request()->has('is_special')) {
            $query->where('is_special', request('is_special'));
        }

        // Date range filter
        if (request()->has('from_date')) {
            $query->whereDate('created_at', '>=', request('from_date'));
        }
        if (request()->has('to_date')) {
            $query->whereDate('created_at', '<=', request('to_date'));
        }

        // Sort
        $sortBy = request('sort_by', 'order');
        $sortOrder = request('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $items = $query->paginate(request('per_page', 15));

        return MenuItemResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMenuItemRequest $request
     * @return JsonResponse
     */
    public function store(StoreMenuItemRequest $request)
    {
        $item = MenuItem::create($request->validated());
        $this->syncSpecialtyFromMenuItem($item);

        // Clear menu cache to show updates immediately
        Cache::forget('public.menu_categories');
        Cache::forget('public.specialties');

        return response()->json([
            'success' => true,
            'message' => 'Menu item created successfully.',
            'data' => new MenuItemResource($item->load(['category', 'image'])),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param MenuItem $menuItem
     * @return MenuItemResource
     */
    public function show(MenuItem $menuItem)
    {
        $menuItem->load(['category', 'image']);
        return new MenuItemResource($menuItem);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMenuItemRequest $request
     * @param MenuItem $menuItem
     * @return JsonResponse
     */
    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem)
    {
        $oldSlug = $menuItem->slug;
        $menuItem->update($request->validated());
        $this->removeSyncedSpecialty($oldSlug, $menuItem->slug);
        $this->syncSpecialtyFromMenuItem($menuItem);
        $menuItem->load(['category', 'image']);

        // Clear menu cache to show updates immediately
        Cache::forget('public.menu_categories');
        Cache::forget('public.specialties');

        return response()->json([
            'success' => true,
            'message' => 'Menu item updated successfully.',
            'data' => new MenuItemResource($menuItem),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MenuItem $menuItem
     * @return JsonResponse
     */
    public function destroy(MenuItem $menuItem)
    {
        $this->removeSyncedSpecialty($menuItem->slug);
        $menuItem->delete();

        // Clear menu cache to show updates immediately
        Cache::forget('public.menu_categories');
        Cache::forget('public.specialties');

        return response()->json([
            'success' => true,
            'message' => 'Menu item deleted successfully.',
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        $rows = $this->parseCsvUpload($request);
        $imported = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $line = $index + 2;

            try {
                if (empty($row['name'])) {
                    throw new \InvalidArgumentException('Name is required.');
                }

                if (!isset($row['price']) || $row['price'] === '') {
                    throw new \InvalidArgumentException('Price is required.');
                }

                $categoryId = $this->resolveMenuCategoryId($row);
                $slug = $this->slugOrGenerate($row['slug'] ?? null, $row['name']);

                if (MenuItem::where('slug', $slug)->exists()) {
                    throw new \InvalidArgumentException("Slug \"{$slug}\" already exists.");
                }

                $item = MenuItem::create([
                    'category_id' => $categoryId,
                    'name' => $row['name'],
                    'slug' => $slug,
                    'description' => $row['description'] ?? null,
                    'price' => (float) $row['price'],
                    'is_special' => $this->toBool($row['is_special'] ?? null, false),
                    'is_active' => $this->toBool($row['is_active'] ?? null),
                    'image_id' => $this->nullableInt($row['image_id'] ?? null),
                    'order' => $this->nullableInt($row['order'] ?? null) ?? 0,
                ]);
                $this->syncSpecialtyFromMenuItem($item);

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = ['row' => $line, 'message' => $e->getMessage()];
            }
        }

        Cache::forget('public.menu_categories');
        Cache::forget('public.specialties');

        return $this->importResult($imported, $errors);
    }

    private function syncSpecialtyFromMenuItem(MenuItem $item): void
    {
        if (!$item->is_special) {
            $this->removeSyncedSpecialty($item->slug);
            return;
        }

        $specialty = Specialty::withTrashed()->where('slug', $item->slug)->first() ?? new Specialty([
            'slug' => $item->slug,
        ]);

        $specialty->fill([
            'title' => $item->name,
            'excerpt' => $item->description,
            'description' => $item->description,
            'image_id' => $item->image_id,
            'is_active' => $item->is_active,
            'order' => $item->order,
        ]);

        if (method_exists($specialty, 'restore') && $specialty->trashed()) {
            $specialty->restore();
        }

        $specialty->save();
    }

    private function removeSyncedSpecialty(string $slug, ?string $newSlug = null): void
    {
        if ($newSlug !== null && $slug === $newSlug) {
            return;
        }

        Specialty::where('slug', $slug)->delete();
    }

    /**
     * @param array<string, string> $row
     */
    private function resolveMenuCategoryId(array $row): int
    {
        if (!empty($row['category_id'])) {
            $category = MenuCategory::find($row['category_id']);
            if (!$category) {
                throw new \InvalidArgumentException('Category ID not found.');
            }

            return (int) $category->id;
        }

        if (!empty($row['category_slug'])) {
            $category = MenuCategory::where('slug', $row['category_slug'])->first();
            if ($category) {
                return (int) $category->id;
            }
        }

        if (!empty($row['category_name'])) {
            $category = MenuCategory::where('name', $row['category_name'])->first();
            if ($category) {
                return (int) $category->id;
            }
        }

        throw new \InvalidArgumentException('Provide category_id, category_slug, or category_name.');
    }
}
