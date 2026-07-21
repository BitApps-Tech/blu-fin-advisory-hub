<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Services\NewsletterEmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $source = $request->input('source');

        $query = NewsletterSubscriber::query()->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($source) {
            $query->where('source', $source);
        }

        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:100',
        ]);

        $service = app(NewsletterEmailService::class);
        $subscriber = $service->recordIfNew(
            $validated['email'],
            $validated['name'] ?? null,
            $validated['source'] ?? 'admin'
        );

        if (!$subscriber) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => ['email' => ['Please provide a valid email address.']],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $service->wasNewlyCreated($subscriber)
                ? 'Subscriber added successfully.'
                : 'This email is already subscribed.',
            'already_subscribed' => !$service->wasNewlyCreated($subscriber),
            'data' => $subscriber,
        ], $service->wasNewlyCreated($subscriber) ? 201 : 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscriber removed successfully.',
        ]);
    }
}
