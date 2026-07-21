<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PERMISSION SYSTEM CHECK ===\n\n";

// Check roles
$roles = \Spatie\Permission\Models\Role::all();
echo "📋 Roles in database:\n";
foreach ($roles as $role) {
    echo "  ID: {$role->id} | Name: '{$role->name}' | Guard: {$role->guard_name}\n";
    echo "  Permissions: " . $role->permissions->count() . "\n";
}

echo "\n";

// Check Admin role specifically (role_id = 1)
$adminRole = \Spatie\Permission\Models\Role::find(1);
if ($adminRole) {
    echo "🛡️ Admin Role (ID: 1):\n";
    echo "  Name: {$adminRole->name}\n";
    echo "  Permissions Count: " . $adminRole->permissions->count() . "\n";
    echo "  First 10 permissions:\n";
    foreach ($adminRole->permissions->take(10) as $perm) {
        echo "    - {$perm->name}\n";
    }
} else {
    echo "❌ Role ID 1 not found!\n";
}

echo "\n";

// Check total permissions
$totalPermissions = \Spatie\Permission\Models\Permission::count();
echo "🔑 Total Permissions: {$totalPermissions}\n";

// Check specific permissions
$checkPerms = ['users.view', 'roles.view', 'permissions.view', 'sms.view', 'sms.send'];
echo "\n📌 Checking specific permissions:\n";
foreach ($checkPerms as $perm) {
    $exists = \Spatie\Permission\Models\Permission::where('name', $perm)->exists();
    echo "  {$perm}: " . ($exists ? "✅ EXISTS" : "❌ NOT FOUND") . "\n";
}

// Check admin user (user_id = 1)
$adminUser = \App\Models\User::find(1);
if ($adminUser) {
    echo "\n👤 Admin User (ID: 1):\n";
    echo "  Name: {$adminUser->name}\n";
    echo "  Email: {$adminUser->email}\n";
    echo "  Roles: " . $adminUser->roles->pluck('name')->join(', ') . "\n";
    echo "  Direct Permissions: " . $adminUser->permissions->count() . "\n";
    echo "  All Permissions (via roles): " . $adminUser->getAllPermissions()->count() . "\n";
} else {
    echo "\n❌ User ID 1 not found!\n";
}

echo "\n=== END CHECK ===\n";

