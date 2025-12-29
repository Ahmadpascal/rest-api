<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role ada
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'user', 'guard_name' => 'web']
        );

        // Buat admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        // Assign role admin
        if (! $admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }
    }
}
