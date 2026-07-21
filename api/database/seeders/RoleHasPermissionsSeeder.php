<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionsSeeder extends Seeder
{
    /**
     * Assign all permissions to role_id = 1.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::find(1);

        if (!$role) {
            $this->command->error('Role with id = 1 was not found. Run RoleSeeder first.');
            return;
        }

        $permissions = Permission::where('guard_name', 'api')->get();

        if ($permissions->isEmpty()) {
            $this->command->error('No permissions found. Run PermissionsSeeder or RoleSeeder first.');
            return;
        }

        $role->syncPermissions($permissions);

        $this->command->info("Assigned {$permissions->count()} permissions to role_id = 1 ('{$role->name}').");
    }
}
