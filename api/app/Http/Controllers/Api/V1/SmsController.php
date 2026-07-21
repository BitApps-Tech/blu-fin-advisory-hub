<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Rules\EthiopianPhone;
use App\Services\CustomerPhoneService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send SMS to a single recipient
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20', new EthiopianPhone()],
            'message' => 'required|string|max:160',
            'recipient_name' => 'nullable|string|max:255',
            'type' => 'nullable|in:promotional,transactional,notification,alert',
        ]);

        $phoneService = app(CustomerPhoneService::class);
        $validated['phone'] = $phoneService->normalize($validated['phone']);
        $phoneService->recordIfNew($validated['phone'], $validated['recipient_name'] ?? null);

        $result = $this->smsService->send(
            $validated['phone'],
            $validated['message'],
            $validated['recipient_name'] ?? null,
            $validated['type'] ?? 'transactional'
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Send SMS to multiple recipients
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendBulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*.phone' => ['required', 'string', 'max:20', new EthiopianPhone()],
            'recipients.*.name' => 'sometimes|nullable|string',
            'message' => 'required|string|max:160',
            'type' => 'nullable|in:promotional,transactional,notification,alert',
        ]);

        $phoneService = app(CustomerPhoneService::class);
        foreach ($validated['recipients'] as &$recipient) {
            $recipient['phone'] = $phoneService->normalize($recipient['phone']);
            $phoneService->recordIfNew($recipient['phone'], $recipient['name'] ?? null);
        }
        unset($recipient);

        $result = $this->smsService->sendBulk(
            $validated['recipients'],
            $validated['message'],
            $validated['type'] ?? 'promotional'
        );

        return response()->json($result);
    }

    /**
     * Check if SMS service is configured
     *
     * @return JsonResponse
     */
    public function status(): JsonResponse
    {
        $isConfigured = $this->smsService->isConfigured();

        return response()->json([
            'success' => true,
            'data' => [
                'configured' => $isConfigured,
                'message' => $isConfigured 
                    ? 'SMS service is configured and ready to use'
                    : 'SMS service is not configured. Please configure in Settings.',
            ],
        ]);
    }
}

