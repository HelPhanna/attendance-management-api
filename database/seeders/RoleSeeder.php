<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'key' => 'super_admin',
                'status' => 'active',
                'description' => 'Full system access'
            ],
            [
                'name' => 'Admin',
                'key' => 'admin',
                'status' => 'active',
                'description' => 'System administrator'
            ],
            [
                'name' => 'Teacher',
                'key' => 'teacher',
                'status' => 'active',
                'description' => 'Teacher role'
            ],
            [
                'name' => 'Student',
                'key' => 'student',
                'status' => 'active',
                'description' => 'Student role'
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['key' => $role['key']], // unique condition
                $role
            );
        }
    }
}
