<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all modules and their CRUD permissions
        $modules = [
            'dashboard' => ['view'],
            'orders' => ['view', 'create', 'edit', 'delete', 'export'],
            'menu' => ['view', 'create', 'edit', 'delete'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'items' => ['view', 'create', 'edit', 'delete'],
            'specialties' => ['view', 'create', 'edit', 'delete'],
            'gallery' => ['view', 'create', 'edit', 'delete'],
            'messages' => ['view', 'reply', 'delete', 'mark_read'],
            'feedback' => ['view', 'delete', 'mark_read'],
            'media' => ['view', 'upload', 'edit', 'delete'],
            'events' => ['view', 'create', 'edit', 'delete'],
            'catering' => ['view', 'create', 'edit', 'delete'],
            'customers' => ['view', 'create', 'edit', 'delete'],
            'newsletter' => ['view', 'create', 'delete', 'send'],
            'sms' => ['view', 'send', 'bulk_send'],
            'users' => ['view', 'create', 'edit', 'delete'],
            'roles' => ['view', 'create', 'edit', 'delete', 'assign_permissions'],
            'permissions' => ['view', 'create', 'delete'],
            'settings' => ['view', 'edit'],
        ];

        $createdPermissions = [];

        // Create all permissions
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permissionName = "{$module}.{$action}";
                
                $permission = Permission::firstOrCreate(
                    [
                        'name' => $permissionName,
                        'guard_name' => 'api'
                    ]
                );
                
                $createdPermissions[] = $permission;
                
                $this->command->info("Created/Found permission: {$permissionName}");
            }
        }

        // Find role_id = 1 (whatever it's named) and assign all permissions
        $roleOne = Role::find(1);
        if ($roleOne) {
            $roleOne->syncPermissions($createdPermissions);
            $this->command->info("\n✅ Assigned all permissions to role_id = 1 ('{$roleOne->name}')");
            $this->command->info("   Total permissions assigned: " . $roleOne->permissions->count());
        }

        // Also ensure "Admin" role has all permissions
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($createdPermissions);
            $this->command->info("✅ Assigned all permissions to 'Admin' role (ID: {$adminRole->id})");
        }
        
        // And "Super Admin" role (two words)
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminRole->syncPermissions($createdPermissions);
            $this->command->info("✅ Assigned all permissions to 'Super Admin' role (ID: {$superAdminRole->id})");
        }

        // And "SuperAdmin" role (one word)
        $superAdminOneWord = Role::where('name', 'SuperAdmin')->first();
        if ($superAdminOneWord) {
            $superAdminOneWord->syncPermissions($createdPermissions);
            $this->command->info("✅ Assigned all permissions to 'SuperAdmin' role (ID: {$superAdminOneWord->id})");
        }

        $this->command->info("\n✅ Successfully created " . count($createdPermissions) . " permissions");

        $this->command->info("\n📊 Permission Summary:");
        $this->command->info("   Total Permissions: " . count($createdPermissions));
        if ($adminRole) {
            $this->command->info("   Admin Role Permissions: " . $adminRole->permissions()->count());
        }
        if ($superAdminRole) {
            $this->command->info("   Super Admin Role Permissions: " . $superAdminRole->permissions()->count());
        }
    }
}

