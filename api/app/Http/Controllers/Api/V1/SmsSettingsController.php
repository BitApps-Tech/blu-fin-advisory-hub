<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SmsSettings;
use App\Rules\EthiopianPhone;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SmsSettingsController extends Controller
{
    /**
     * Display SMS settings
     */
    public function index(): JsonResponse
    {
        $settings = SmsSettings::getOrCreate();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Update SMS settings
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'api_url' => 'required|string|url',
            'api_key' => 'required|string',
            'provider_name' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $settings = SmsSettings::getOrCreate();
        $settings->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'SMS settings updated successfully',
            'data' => $settings,
        ]);
    }

    /**
     * Test SMS configuration
     */
    public function test(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20', new EthiopianPhone()],
        ]);

        $settings = SmsSettings::getOrCreate();

        if (!$settings->api_url || !$settings->api_key) {
            return response()->json([
                'success' => false,
                'message' => 'SMS settings not configured',
            ], 422);
        }

        // You can implement actual test SMS sending here
        return response()->json([
            'success' => true,
            'message' => 'SMS settings are configured. Test SMS would be sent to: ' . $validated['phone'],
        ]);
    }
}

