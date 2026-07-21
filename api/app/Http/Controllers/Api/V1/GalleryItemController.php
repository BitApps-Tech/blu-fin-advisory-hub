<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryItem\StoreGalleryItemRequest;
use App\Http\Requests\GalleryItem\UpdateGalleryItemRequest;
use App\Http\Resources\GalleryItemResource;
use App\Models\GalleryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class GalleryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $query = GalleryItem::query()->with('image');

        // Search by title or caption
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('caption', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if (request()->filled('category')) {
            $query->where('category', request('category'));
        }

        // Filter by is_active
        if (request()->has('is_active')) {
            $query->where('is_active', request('is_active'));
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

        return GalleryItemResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGalleryItemRequest $request
     * @return JsonResponse
     */
    public function store(StoreGalleryItemRequest $request)
    {
        $validated = $request->validated();
        
        // Convert is_active to boolean if present
        if (isset($validated['is_active'])) {
            $validated['is_active'] = in_array($validated['is_active'], [1, '1', 'true', true], true);
        }
        
        $item = GalleryItem::create($validated);
        $this->clearGalleryCache();

        return response()->json([
            'success' => true,
            'message' => 'Gallery item created successfully.',
            'data' => new GalleryItemResource($item->load('image')),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param GalleryItem $gallery
     * @return GalleryItemResource
     */
    public function show(GalleryItem $gallery)
    {
        $gallery->load('image');
        return new GalleryItemResource($gallery);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateGalleryItemRequest $request
     * @param GalleryItem $gallery
     * @return JsonResponse
     */
    public function update(UpdateGalleryItemRequest $request, GalleryItem $gallery)
    {
        $validated = $request->validated();
        
        // Convert is_active to boolean if present
        if (isset($validated['is_active'])) {
            $validated['is_active'] = in_array($validated['is_active'], [1, '1', 'true', true], true);
        }
        
        $gallery->update($validated);
        $gallery->load('image');
        $this->clearGalleryCache();

        return response()->json([
            'success' => true,
            'message' => 'Gallery item updated successfully.',
            'data' => new GalleryItemResource($gallery),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GalleryItem $gallery
     * @return JsonResponse
     */
    public function destroy(GalleryItem $gallery)
    {
        $gallery->delete();
        $this->clearGalleryCache();

        return response()->json([
            'success' => true,
            'message' => 'Gallery item deleted successfully.',
        ]);
    }

    private function clearGalleryCache(): void
    {
        Cache::forget('public.gallery');
        foreach (GalleryItem::CATEGORIES as $category) {
            Cache::forget("public.gallery.{$category}");
        }
    }
}
