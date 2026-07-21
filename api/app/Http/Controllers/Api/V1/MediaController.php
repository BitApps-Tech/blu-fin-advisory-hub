<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreMediaRequest;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $query = Media::query();

        // Search by title or alt
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('alt', 'like', "%{$search}%");
            });
        }

        // Filter by MIME type
        if (request()->has('mime')) {
            $mime = request('mime');
            if (str_contains($mime, '/')) {
                // Specific MIME type
                $query->where('mime', $mime);
            } else {
                // MIME type category (image, application, etc.)
                $query->where('mime', 'like', "{$mime}/%");
            }
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

        $media = $query->paginate(request('per_page', 15));

        return MediaResource::collection($media);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMediaRequest $request
     * @return JsonResponse
     */
    public function store(StoreMediaRequest $request)
    {
        $file = $request->file('file');

        if (!$file || !$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => $file ? $file->getErrorMessage() : 'No file was uploaded.',
            ], 422);
        }

        $title = $request->input('title');
        $alt = $request->input('alt_text', $request->input('alt'));
        $description = $request->input('description');

        try {
            $media = $this->mediaService->store($file, $title, $alt, auth()->id());
        } catch (\Throwable $e) {
            Log::error('Media upload failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save uploaded file. Please check storage permissions.',
            ], 500);
        }
        
        // Update description if provided
        if ($description) {
            $media->description = $description;
            $media->save();
            $media = $media->fresh();
        }

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully.',
            'data' => new MediaResource($media),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Media $medium
     * @return MediaResource
     */
    public function show(Media $medium)
    {
        return new MediaResource($medium);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMediaRequest $request
     * @param Media $medium
     * @return JsonResponse
     */
    public function update(UpdateMediaRequest $request, Media $medium)
    {
        try {
            Log::info('Media update called', [
                'media_id' => $medium->id,
                'request_data' => $request->all(),
            ]);
            
            $validated = $request->validated();
            
            Log::info('Validated data', ['validated' => $validated]);
            
            // Map alt_text to alt if provided
            if (isset($validated['alt_text'])) {
                $validated['alt'] = $validated['alt_text'];
                unset($validated['alt_text']);
            }
            
            $updated = $medium->update($validated);
            
            Log::info('Update result', ['updated' => $updated, 'media' => $medium->toArray()]);

            return response()->json([
                'success' => true,
                'message' => 'Media updated successfully.',
                'data' => new MediaResource($medium->fresh()),
            ]);
        } catch (\Exception $e) {
            Log::error('Media update error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update media: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Media $medium
     * @return JsonResponse
     */
    public function destroy(Media $medium)
    {
        try {
            Log::info('Media delete called', [
                'media_id' => $medium->id,
                'media_path' => $medium->path,
            ]);
            
            $deleted = $this->mediaService->delete($medium);
            
            Log::info('Delete result', ['deleted' => $deleted]);
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Media deleted successfully.',
                ]);
            }
            
            Log::warning('Delete returned false');
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete media.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Media deletion error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete media: ' . $e->getMessage(),
            ], 500);
        }
    }
}

