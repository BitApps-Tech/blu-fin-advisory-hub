<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ImportsCsv;
use App\Http\Controllers\Controller;
use App\Http\Requests\Specialty\StoreSpecialtyRequest;
use App\Http\Requests\Specialty\UpdateSpecialtyRequest;
use App\Http\Resources\SpecialtyResource;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class SpecialtyController extends Controller
{
    use ImportsCsv;
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $query = Specialty::query()->with('image');

        // Search by title, excerpt, or description
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
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

        $specialties = $query->paginate(request('per_page', 15));

        return SpecialtyResource::collection($specialties);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSpecialtyRequest $request
     * @return JsonResponse
     */
    public function store(StoreSpecialtyRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        // A previously deleted specialty (soft delete) still owns its unique slug,
        // so reuse and restore that row instead of failing on the unique constraint.
        $trashed = Specialty::onlyTrashed()->where('slug', $data['slug'])->first();

        if ($trashed) {
            $trashed->restore();
            $trashed->update($data);
            $specialty = $trashed;
        } else {
            $specialty = Specialty::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Specialty created successfully.',
            'data' => new SpecialtyResource($specialty->load('image')),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Specialty $specialty
     * @return SpecialtyResource
     */
    public function show(Specialty $specialty)
    {
        $specialty->load('image');
        return new SpecialtyResource($specialty);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSpecialtyRequest $request
     * @param Specialty $specialty
     * @return JsonResponse
     */
    public function update(UpdateSpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->update($request->validated());
        $specialty->load('image');

        return response()->json([
            'success' => true,
            'message' => 'Specialty updated successfully.',
            'data' => new SpecialtyResource($specialty),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Specialty $specialty
     * @return JsonResponse
     */
    public function destroy(Specialty $specialty)
    {
        $specialty->delete();

        return response()->json([
            'success' => true,
            'message' => 'Specialty deleted successfully.',
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
                if (empty($row['title'])) {
                    throw new \InvalidArgumentException('Title is required.');
                }

                $slug = $this->slugOrGenerate($row['slug'] ?? null, $row['title']);

                if (Specialty::where('slug', $slug)->exists()) {
                    throw new \InvalidArgumentException("Slug \"{$slug}\" already exists.");
                }

                Specialty::create([
                    'title' => $row['title'],
                    'slug' => $slug,
                    'excerpt' => $row['excerpt'] ?? null,
                    'description' => $row['description'] ?? null,
                    'image_id' => $this->nullableInt($row['image_id'] ?? null),
                    'is_active' => $this->toBool($row['is_active'] ?? null),
                    'order' => $this->nullableInt($row['order'] ?? null) ?? 0,
                ]);

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = ['row' => $line, 'message' => $e->getMessage()];
            }
        }

        return $this->importResult($imported, $errors);
    }
}
