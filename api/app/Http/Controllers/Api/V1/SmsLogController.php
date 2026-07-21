<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Rules\EthiopianPhone;
use App\Services\CustomerPhoneService;
use Illuminate\Http\Request;

class SmsLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search = $request->input('search');

        $query = SmsLog::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('recipient_phone', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $smsLogs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($smsLogs);
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
            'recipient_phone' => ['required', 'string', 'max:20', new EthiopianPhone()],
            'recipient_name' => 'nullable|string',
            'message' => 'required|string',
            'type' => 'nullable|string|in:promotional,transactional,notification,alert',
            'status' => 'nullable|string|in:pending,sent,failed,delivered',
            'response' => 'nullable|string',
            'sms_provider' => 'nullable|string',
            'message_id' => 'nullable|string',
            'sent_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'error_message' => 'nullable|string',
        ]);

        $phoneService = app(CustomerPhoneService::class);
        $validated['recipient_phone'] = $phoneService->normalize($validated['recipient_phone']);
        $phoneService->recordIfNew($validated['recipient_phone'], $validated['recipient_name'] ?? null);

        $smsLog = SmsLog::create($validated);

        return response()->json($smsLog, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $smsLog = SmsLog::findOrFail($id);
        return response()->json($smsLog);
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
        $smsLog = SmsLog::findOrFail($id);

        $validated = $request->validate([
            'recipient_phone' => ['sometimes', 'required', 'string', 'max:20', new EthiopianPhone()],
            'recipient_name' => 'nullable|string',
            'message' => 'sometimes|required|string',
            'type' => 'nullable|string|in:promotional,transactional,notification,alert',
            'status' => 'nullable|string|in:pending,sent,failed,delivered',
            'response' => 'nullable|string',
            'sms_provider' => 'nullable|string',
            'message_id' => 'nullable|string',
            'sent_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'error_message' => 'nullable|string',
        ]);

        if (!empty($validated['recipient_phone'])) {
            $phoneService = app(CustomerPhoneService::class);
            $validated['recipient_phone'] = $phoneService->normalize($validated['recipient_phone']);
            $phoneService->recordIfNew($validated['recipient_phone'], $validated['recipient_name'] ?? null);
        }

        $smsLog->update($validated);

        return response()->json($smsLog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $smsLog = SmsLog::findOrFail($id);
        $smsLog->delete();

        return response()->json(['message' => 'SMS log deleted successfully']);
    }
}

