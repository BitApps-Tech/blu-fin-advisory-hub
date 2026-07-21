<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create SuperAdmin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'mamo@mamokachaplc.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@12345'),
                'email_verified_at' => now(),
            ] 
        );

        // Assign SuperAdmin role (with api guard)
        $role = \Spatie\Permission\Models\Role::where('name', 'SuperAdmin')->where('guard_name', 'api')->first();
        if ($role && !$superAdmin->hasRole($role)) {
            $superAdmin->assignRole($role);
        }

        $this->command->info('SuperAdmin user created:');
        $this->command->info('Email: mamo@mamokachaplc.com');
        $this->command->info('Password: Admin@12345');
    }
}

