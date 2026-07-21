<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ImportsCsv;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuCategory\StoreMenuCategoryRequest;
use App\Http\Requests\MenuCategory\UpdateMenuCategoryRequest;
use App\Http\Resources\MenuCategoryResource;
use App\Models\MenuCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class MenuCategoryController extends Controller
{
    use ImportsCsv;
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $query = MenuCategory::query()->withCount('menuItems');

        // Search by name
        if (request()->has('search')) {
            $search = request('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by is_active
        if (request()->has('is_active')) {
            $query->where('is_active', request('is_active'));
        }

        // Sort
        $sortBy = request('sort_by', 'order');
        $sortOrder = request('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $categories = $query->paginate(request('per_page', 15));

        return MenuCategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMenuCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreMenuCategoryRequest $request)
    {
        $validated = $request->validated();
        
        // Convert is_active to boolean if present
        if (isset($validated['is_active'])) {
            $validated['is_active'] = in_array($validated['is_active'], [1, '1', 'true', true], true);
        }
        
        $category = MenuCategory::create($validated);

        // Clear menu cache to show updates immediately
        Cache::forget('public.menu_categories');

        return response()->json([
            'success' => true,
            'message' => 'Menu category created successfully.',
            'data' => new MenuCategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param MenuCategory $menuCategory
     * @return MenuCategoryResource
     */
    public function show(MenuCategory $menuCategory)
    {
        $menuCategory->load('menuItems');
        return new MenuCategoryResource($menuCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMenuCategoryRequest $request
     * @param MenuCategory $menuCategory
     * @return JsonResponse
     */
    public function update(UpdateMenuCategoryRequest $request, MenuCategory $menuCategory)
    {
        $validated = $request->validated();
        
        // Convert is_active to boolean if present
        if (isset($validated['is_active'])) {
            $validated['is_active'] = in_array($validated['is_active'], [1, '1', 'true', true], true);
        }
        
        // Check if category is being deactivated and has menu items
        if (isset($validated['is_active']) && !$validated['is_active']) {
            $itemCount = $menuCategory->menuItems()->count();
            if ($itemCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot deactivate this category because it is currently used in {$itemCount} menu item(s). Please reassign or remove those items first.",
                ], 422);
            }
        }

        $menuCategory->update($validated);

        // Clear menu cache to show updates immediately
        Cache::forget('public.menu_categories');

        return response()->json([
            'success' => true,
            'message' => 'Menu category updated successfully.',
            'data' => new MenuCategoryResource($menuCategory),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MenuCategory $menuCategory
     * @return JsonResponse
     */
    public function destroy(MenuCategory $menuCategory)
    {
        // Check if category has menu items
        $itemCount = $menuCategory->menuItems()->count();
        if ($itemCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this category because it is currently used in {$itemCount} menu item(s). Please reassign or remove those items first.",
            ], 422);
        }

        $menuCategory->delete();

        // Clear menu cache to show updates immediately
        Cache::forget('public.menu_categories');

        return response()->json([
            'success' => true,
            'message' => 'Menu category deleted successfully.',
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

                $slug = $this->slugOrGenerate($row['slug'] ?? null, $row['name']);

                if (MenuCategory::where('slug', $slug)->exists()) {
                    throw new \InvalidArgumentException("Slug \"{$slug}\" already exists.");
                }

                MenuCategory::create([
                    'name' => $row['name'],
                    'slug' => $slug,
                    'description' => $row['description'] ?? null,
                    'is_active' => $this->toBool($row['is_active'] ?? null),
                    'order' => $this->nullableInt($row['order'] ?? null) ?? 0,
                ]);

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = ['row' => $line, 'message' => $e->getMessage()];
            }
        }

        Cache::forget('public.menu_categories');

        return $this->importResult($imported, $errors);
    }
}

