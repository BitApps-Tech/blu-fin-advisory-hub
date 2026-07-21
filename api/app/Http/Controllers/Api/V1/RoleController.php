<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 10);
        $query = Role::query()->with('permissions')->withCount('users');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => $roles->items(),
            'meta' => [
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'total' => $roles->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'nullable|string',
        ]);

        $role = Role::create(array_merge($validated, ['guard_name' => $validated['guard_name'] ?? 'api']));

        return response()->json([
            'data' => $role,
            'message' => 'Role created successfully',
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json(['data' => $role]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        $role->update($validated);

        return response()->json([
            'data' => $role,
            'message' => 'Role updated successfully',
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    /**
     * Sync permissions for a role
     */
    public function syncPermissions(Request $request, $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Get permission names from IDs
        $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $validated['permissions'])->pluck('name')->toArray();
        
        // Sync permissions (removes old ones, adds new ones)
        $role->syncPermissions($permissions);

        return response()->json([
            'success' => true,
            'data' => $role->load('permissions'),
            'message' => 'Permissions updated successfully',
        ]);
    }
}

