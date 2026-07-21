<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get();
        
        // Group settings by group
        $grouped = $settings->groupBy('group')->map(function ($group) {
            return SettingResource::collection($group);
        });

        return response()->json([
            'success' => true,
            'data' => $grouped,
        ]);
    }

    /**
     * Get settings by group.
     *
     * @param string $group
     * @return JsonResponse
     */
    public function getGroup(string $group)
    {
        $settings = Setting::where('group', $group)->orderBy('key')->get();

        return response()->json([
            'success' => true,
            'data' => SettingResource::collection($settings),
        ]);
    }

    /**
     * Update settings (bulk).
     *
     * @param UpdateSettingRequest $request
     * @return JsonResponse
     */
    public function update(UpdateSettingRequest $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $settingData) {
            Setting::setValue(
                $settingData['group'],
                $settingData['key'],
                $settingData['value'],
                $settingData['type'] ?? 'text'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully.',
            'data' => SettingResource::collection(Setting::all()),
        ]);
    }

    /**
     * Delete all settings in a group.
     *
     * @param string $group
     * @return JsonResponse
     */
    public function deleteGroup(string $group)
    {
        $deleted = Setting::where('group', $group)->delete();

        return response()->json([
            'success' => true,
            'message' => "Group '{$group}' deleted successfully.",
            'deleted_count' => $deleted,
        ]);
    }

    /**
     * Delete a specific setting.
     *
     * @param string $group
     * @param string $key
     * @return JsonResponse
     */
    public function deleteSetting(string $group, string $key)
    {
        $deleted = Setting::where('group', $group)
            ->where('key', $key)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Setting deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Setting not found.',
        ], 404);
    }
}
