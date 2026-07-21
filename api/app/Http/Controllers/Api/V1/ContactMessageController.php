<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessage\UpdateContactMessageRequest;
use App\Http\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $query = ContactMessage::query()->with('reader');

        // Search by name, email, subject, or message
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filter by is_read
        if (request()->has('is_read')) {
            $query->where('is_read', request('is_read'));
        }

        // Filter by email
        if (request()->has('email')) {
            $query->where('email', request('email'));
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

        $messages = $query->paginate(request('per_page', 15));

        return ContactMessageResource::collection($messages);
    }

    /**
     * Display the specified resource.
     *
     * @param ContactMessage $message
     * @return ContactMessageResource
     */
    public function show(ContactMessage $message)
    {
        $message->load('reader');
        return new ContactMessageResource($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateContactMessageRequest $request
     * @param ContactMessage $message
     * @return JsonResponse
     */
    public function update(UpdateContactMessageRequest $request, ContactMessage $message)
    {
        $message->update($request->validated());
        $message->load('reader');

        return response()->json([
            'success' => true,
            'message' => 'Contact message updated successfully.',
            'data' => new ContactMessageResource($message),
        ]);
    }

    /**
     * Mark message as read.
     *
     * @param ContactMessage $message
     * @return JsonResponse
     */
    public function markAsRead(ContactMessage $message)
    {
        $message->markAsRead(auth()->id());
        $message->load('reader');

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read.',
            'data' => new ContactMessageResource($message),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ContactMessage $message
     * @return JsonResponse
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact message deleted successfully.',
        ]);
    }
}
