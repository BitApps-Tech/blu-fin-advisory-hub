<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions grouped by modules
        $permissions = [
            // Menus
            'menus.view',
            'menus.create',
            'menus.update',
            'menus.delete',
            
            // Specialties
            'specialties.view',
            'specialties.create',
            'specialties.update',
            'specialties.delete',
            
            // Gallery
            'gallery.view',
            'gallery.create',
            'gallery.update',
            'gallery.delete',
            
            // Orders
            'orders.view',
            'orders.create',
            'orders.update',
            'orders.delete',
            'orders.export',
            
            // Messages
            'messages.view',
            'messages.update',
            'messages.delete',
            
            // Settings
            'settings.view',
            'settings.update',
            
            // Media
            'media.view',
            'media.create',
            'media.update',
            'media.delete',
            
            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            
            // Events
            'events.view',
            'events.create',
            'events.update',
            'events.delete',
            
            // Catering
            'catering.view',
            'catering.create',
            'catering.update',
            'catering.delete',
            
            // Customers
            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',
            
            // SMS
            'sms.view',
            'sms.create',
            'sms.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'api']
            );
        }

        // Create or update roles with 'api' guard
        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'api']);
        $editor = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'api']);
        $author = Role::firstOrCreate(['name' => 'Author', 'guard_name' => 'api']);
        $viewer = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'api']);

        // Assign all permissions to SuperAdmin
        $superAdmin->givePermissionTo(Permission::all());

        // Assign permissions to Editor (full access except users management)
        $editor->givePermissionTo([
            'menus.view', 'menus.create', 'menus.update', 'menus.delete',
            'specialties.view', 'specialties.create', 'specialties.update', 'specialties.delete',
            'gallery.view', 'gallery.create', 'gallery.update', 'gallery.delete',
            'orders.view', 'orders.create', 'orders.update', 'orders.delete', 'orders.export',
            'messages.view', 'messages.update', 'messages.delete',
            'settings.view', 'settings.update',
            'media.view', 'media.create', 'media.update', 'media.delete',
            'events.view', 'events.create', 'events.update', 'events.delete',
            'catering.view', 'catering.create', 'catering.update', 'catering.delete',
            'customers.view', 'customers.create', 'customers.update', 'customers.delete',
            'sms.view', 'sms.create', 'sms.delete',
        ]);

        // Assign permissions to Author (limited create/update)
        $author->givePermissionTo([
            'menus.view',
            'specialties.view', 'specialties.create', 'specialties.update',
            'gallery.view', 'gallery.create', 'gallery.update',
            'orders.view',
            'messages.view',
            'media.view', 'media.create',
            'events.view', 'events.create',
            'catering.view', 'catering.create',
            'customers.view',
            'sms.view',
        ]);

        // Assign permissions to Viewer (read-only)
        $viewer->givePermissionTo([
            'menus.view',
            'specialties.view',
            'gallery.view',
            'orders.view',
            'messages.view',
            'settings.view',
            'media.view',
            'events.view',
            'catering.view',
            'customers.view',
            'sms.view',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}

