<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['key' => 'super_admin'],
            [
                'name' => 'Super Admin',
                'status' => 'active',
                'description' => 'Full system access',
            ]
        );

        $email = env('SUPER_ADMIN_EMAIL', 'superadmin@example.com');
        $name = env('SUPER_ADMIN_NAME', 'Super Admin');
        $password = env('SUPER_ADMIN_PASSWORD', 'ChangeMe123!');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'status' => 'active',
            ]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);
    }
}

