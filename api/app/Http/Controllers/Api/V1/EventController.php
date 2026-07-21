<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ImportsCsv;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use ImportsCsv;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Event::query()->with('image');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('presenter', 'like', "%{$search}%")
                  ->orWhere('activity', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $events = $query->orderBy('event_date', 'desc')->get();
        
        // Update status for all events based on current time
        foreach ($events as $event) {
            $event->updateStatusBasedOnDate();
        }
        
        // Re-query to get updated data and paginate
        $query = Event::query()->with('image');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('presenter', 'like', "%{$search}%")
                  ->orWhere('activity', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }
        $events = $query->orderBy('event_date', 'desc')->paginate($perPage);

        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'presenter' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'activity' => 'nullable|string|max:255',
            'image_id' => 'nullable|exists:media,id',
            'location' => 'nullable|string|max:255',
            'event_date' => 'required|date',
            'status' => 'nullable|string|in:upcoming,ongoing,completed,cancelled',
            'is_active' => 'nullable|boolean',
        ]);

        $event = Event::create($validated);

        return response()->json($event->load('image'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::with('image')->findOrFail($id);
        // Update status based on current time
        $event->updateStatusBasedOnDate();
        $event->refresh(); // Refresh to get updated data
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'presenter' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'activity' => 'nullable|string|max:255',
            'image_id' => 'nullable|exists:media,id',
            'location' => 'nullable|string|max:255',
            'event_date' => 'sometimes|required|date',
            'status' => 'nullable|string|in:upcoming,ongoing,completed,cancelled',
            'is_active' => 'nullable|boolean',
        ]);

        $event->update($validated);

        return response()->json($event->load('image'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete(); // Soft delete

        return response()->json(['message' => 'Event deleted successfully']);
    }

    /**
     * Restore a soft-deleted event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        $event->restore();

        return response()->json($event->load('image'));
    }

    /**
     * Permanently delete an event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        $event->forceDelete();

        return response()->json(['message' => 'Event permanently deleted']);
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

                if (empty($row['event_date'])) {
                    throw new \InvalidArgumentException('event_date is required.');
                }

                $status = $row['status'] ?? 'upcoming';
                if (!in_array($status, ['upcoming', 'ongoing', 'completed', 'cancelled'], true)) {
                    throw new \InvalidArgumentException('Invalid status. Use upcoming, ongoing, completed, or cancelled.');
                }

                Event::create([
                    'title' => $row['title'],
                    'presenter' => $row['presenter'] ?? null,
                    'description' => $row['description'] ?? null,
                    'activity' => $row['activity'] ?? null,
                    'location' => $row['location'] ?? null,
                    'event_date' => $row['event_date'],
                    'status' => $status,
                    'is_active' => $this->toBool($row['is_active'] ?? null),
                    'image_id' => $this->nullableInt($row['image_id'] ?? null),
                ]);

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = ['row' => $line, 'message' => $e->getMessage()];
            }
        }

        return $this->importResult($imported, $errors);
    }
}
