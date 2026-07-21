<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerFeedbackResource;
use App\Models\CustomerFeedback;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerFeedbackController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $query = $this->filteredQuery();
        $summary = $this->summarize((clone $query)->get());

        $sortBy = $this->resolveSortColumn();
        $sortOrder = strtolower((string) request('sort_order', 'desc')) === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $paginated = $query->paginate((int) request('per_page', 15));

        return CustomerFeedbackResource::collection($paginated)->additional([
            'summary' => $summary,
        ]);
    }

    public function show(CustomerFeedback $feedback): CustomerFeedbackResource
    {
        $feedback->load('reader');

        return new CustomerFeedbackResource($feedback);
    }

    public function markAsRead(CustomerFeedback $feedback): JsonResponse
    {
        $feedback->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Feedback marked as read.',
            'data' => new CustomerFeedbackResource($feedback->fresh('reader')),
        ]);
    }

    public function destroy(CustomerFeedback $feedback): JsonResponse
    {
        $feedback->delete();

        return response()->json([
            'success' => true,
            'message' => 'Feedback deleted successfully.',
        ]);
    }

    private function filteredQuery(): Builder
    {
        $query = CustomerFeedback::query()->with('reader');

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('customer_order', 'like', "%{$search}%")
                    ->orWhere('comments', 'like', "%{$search}%");
            });
        }

        if (request()->has('is_read')) {
            $query->where('is_read', filter_var(request('is_read'), FILTER_VALIDATE_BOOLEAN));
        }

        if (request()->filled('from_date')) {
            $query->whereDate('visit_date', '>=', request('from_date'));
        }

        if (request()->filled('to_date')) {
            $query->whereDate('visit_date', '<=', request('to_date'));
        }

        return $query;
    }

    private function resolveSortColumn(): string
    {
        $allowed = [
            'visit_date' => 'visit_date',
            'created_at' => 'created_at',
            'submitted_date' => 'created_at',
            'phone' => 'phone',
            'average_rating' => 'food_taste',
        ];

        $sortBy = (string) request('sort_by', 'created_at');

        return $allowed[$sortBy] ?? 'created_at';
    }

    private function summarize($items): array
    {
        if ($items->isEmpty()) {
            return [
                'count' => 0,
                'overall_average' => null,
                'food_average' => null,
                'service_average' => null,
                'environment_average' => null,
            ];
        }

        return [
            'count' => $items->count(),
            'overall_average' => round($items->avg(fn (CustomerFeedback $item) => $item->averageRating()), 2),
            'food_average' => round($items->avg(fn (CustomerFeedback $item) => $item->foodQualityAverage()), 2),
            'service_average' => round($items->avg(fn (CustomerFeedback $item) => $item->serviceAverage()), 2),
            'environment_average' => round($items->avg(fn (CustomerFeedback $item) => $item->environmentAverage()), 2),
        ];
    }
}
