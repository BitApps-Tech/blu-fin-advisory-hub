<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ImportsCsv;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ImportsCsv;
    /**
     * Get list of users with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 10);
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Create a new user
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'role_id' => 'nullable|exists:roles,id',
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Remove password_confirmation and role_id from validated data before creating user
        unset($validated['password_confirmation']);
        $roleId = $validated['role_id'] ?? null;
        unset($validated['role_id']);
        
        $user = User::create($validated);

        // Assign role if role_id is provided
        if ($roleId) {
            $role = \Spatie\Permission\Models\Role::find($roleId);
            if ($role) {
                $user->assignRole($role->name);
            }
        }

        return response()->json([
            'data' => $user->load('roles'),
            'message' => 'User created successfully',
        ], 201);
    }

    /**
     * Get a specific user
     */
    public function show($id): JsonResponse
    {
        $user = User::with('roles', 'permissions')->findOrFail($id);
        return response()->json(['data' => $user]);
    }

    /**
     * Update a user
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => [
                'sometimes',
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'role_id' => 'nullable|exists:roles,id',
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
            // Remove password_confirmation from validated data
            unset($validated['password_confirmation']);
        } else {
            unset($validated['password']);
        }

        // Extract role_id before updating user
        $roleId = $validated['role_id'] ?? null;
        unset($validated['role_id']);

        $user->update($validated);

        // Update role if role_id is provided
        if ($roleId) {
            $role = \Spatie\Permission\Models\Role::find($roleId);
            if ($role) {
                $user->syncRoles([$role->name]);
            }
        }

        return response()->json([
            'data' => $user->load('roles'),
            'message' => 'User updated successfully',
        ]);
    }

    /**
     * Delete a user
     */
    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Get the authenticated user's abilities (roles and permissions).
     *
     * @return JsonResponse
     */
    public function abilities(): JsonResponse
    {
        $user = User::query()
            ->with(['roles', 'permissions'])
            ->findOrFail(auth('api')->id());

        return response()->json([
            'success' => true,
            'data' => [
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'all_permissions' => $user->getAllPermissions()->map(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'guard_name' => $permission->guard_name,
                    ];
                })
            ]
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        $rows = $this->parseCsvUpload($request);
        $imported = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $line = $index + 2;

            try {
                if (empty($row['name'])) {
                    throw new \InvalidArgumentException('Name is required.');
                }

                if (empty($row['email'])) {
                    throw new \InvalidArgumentException('Email is required.');
                }

                if (empty($row['password'])) {
                    throw new \InvalidArgumentException('Password is required.');
                }

                if (User::where('email', $row['email'])->exists()) {
                    throw new \InvalidArgumentException("Email \"{$row['email']}\" already exists.");
                }

                $passwordConfirmation = $row['password_confirmation'] ?? $row['password'];

                $validator = validator([
                    'password' => $row['password'],
                    'password_confirmation' => $passwordConfirmation,
                ], [
                    'password' => [
                        'required',
                        'string',
                        'min:8',
                        'confirmed',
                        'regex:/[a-z]/',
                        'regex:/[A-Z]/',
                        'regex:/[0-9]/',
                        'regex:/[@$!%*#?&]/',
                    ],
                ], [
                    'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
                ]);

                if ($validator->fails()) {
                    throw new \InvalidArgumentException($validator->errors()->first('password'));
                }

                $roleId = null;
                if (!empty($row['role_id'])) {
                    $roleId = (int) $row['role_id'];
                } elseif (!empty($row['role'])) {
                    $role = \Spatie\Permission\Models\Role::where('name', $row['role'])->first();
                    if (!$role) {
                        throw new \InvalidArgumentException("Role \"{$row['role']}\" not found.");
                    }
                    $roleId = (int) $role->id;
                }

                $user = User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password']),
                ]);

                if ($roleId) {
                    $role = \Spatie\Permission\Models\Role::find($roleId);
                    if ($role) {
                        $user->assignRole($role->name);
                    }
                }

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = ['row' => $line, 'message' => $e->getMessage()];
            }
        }

        return $this->importResult($imported, $errors);
    }
}
