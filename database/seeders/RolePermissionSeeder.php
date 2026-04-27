<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate to avoid duplicates
        DB::table('role_permissions')->truncate();

        $rolePermissions = [
            // Super Admin - All permissions
            [
                'role_id' => 1,
                'permission_id' => 1, // Manage Users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 1,
                'permission_id' => 2, // Manage Roles
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 1,
                'permission_id' => 3, // Manage Permissions
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 1,
                'permission_id' => 4, // Manage Teachers
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 1,
                'permission_id' => 5, // Manage Students
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 1,
                'permission_id' => 6, // Manage Attendance
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 1,
                'permission_id' => 7, // View Reports
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 1,
                'permission_id' => 8, // Export Reports
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Admin
            [
                'role_id' => 2,
                'permission_id' => 1, // Manage Users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'permission_id' => 4, // Manage Teachers
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'permission_id' => 5, // Manage Students
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Teacher
            [
                'role_id' => 3,
                'permission_id' => 6, // Manage Attendance
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'permission_id' => 7, // View Reports
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Student
            [
                'role_id' => 4,
                'permission_id' => 7, // View Reports
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('role_permissions')->insert($rolePermissions);
    }
}
