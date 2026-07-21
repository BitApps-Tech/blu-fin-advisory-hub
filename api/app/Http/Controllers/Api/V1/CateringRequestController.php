<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CateringRequest;
use App\Rules\EthiopianPhone;
use App\Services\CustomerPhoneService;
use App\Services\NewsletterEmailService;
use Illuminate\Http\Request;

class CateringRequestController extends Controller
{
    private function cateringRules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes|required' : 'required';

        return [
            'customer_name' => "{$required}|string|max:255",
            'customer_email' => "{$required}|email",
            'customer_phone' => [$required, 'string', 'max:20', new EthiopianPhone()],
            'event_type' => 'nullable|string',
            'event_date' => "{$required}|date",
            'event_location' => "{$required}|string",
            'guest_count' => "{$required}|integer|min:1",
            'menu_preferences' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'estimated_budget' => 'nullable|numeric|min:0',
            'quoted_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:pending,quoted,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ];
    }

    private function preparePhoneData(array $validated): array
    {
        $phoneService = app(CustomerPhoneService::class);
        $validated['customer_phone'] = $phoneService->normalize($validated['customer_phone']);
        $phoneService->recordIfNew($validated['customer_phone'], $validated['customer_name'] ?? null);

        app(NewsletterEmailService::class)->recordIfNew(
            $validated['customer_email'] ?? null,
            $validated['customer_name'] ?? null,
            'catering'
        );

        return $validated;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search = $request->input('search');

        $query = CateringRequest::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('request_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($requests);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('customer_phone')) {
            $request->merge([
                'customer_phone' => preg_replace('/\s+/', '', (string) $request->input('customer_phone')),
            ]);
        }

        $validated = $request->validate($this->cateringRules());
        $validated = $this->preparePhoneData($validated);

        $cateringRequest = CateringRequest::create($validated);

        return response()->json($cateringRequest, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = CateringRequest::findOrFail($id);
        return response()->json($request);
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
        $cateringRequest = CateringRequest::findOrFail($id);

        if ($request->has('customer_phone')) {
            $request->merge([
                'customer_phone' => preg_replace('/\s+/', '', (string) $request->input('customer_phone')),
            ]);
        }

        $validated = $request->validate($this->cateringRules(true));

        if (!empty($validated['customer_phone'])) {
            $validated = $this->preparePhoneData($validated);
        }

        $cateringRequest->update($validated);

        return response()->json($cateringRequest);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request = CateringRequest::findOrFail($id);
        $request->delete();

        return response()->json(['message' => 'Catering request deleted successfully']);
    }
}
