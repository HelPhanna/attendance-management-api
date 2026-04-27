<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'Manage Users',
                'key' => 'users.manage',
                'status' => 'active',
            ],
            [
                'name' => 'Manage Roles',
                'key' => 'roles.manage',
                'status' => 'active',
            ],
            [
                'name' => 'Manage Permissions',
                'key' => 'permissions.manage',
                'status' => 'active',
            ],
            [
                'name' => 'Manage Teachers',
                'key' => 'teachers.manage',
                'status' => 'active',
            ],
            [
                'name' => 'Manage Students',
                'key' => 'students.manage',
                'status' => 'active',
            ],
            [
                'name' => 'Manage Attendance',
                'key' => 'attendance.manage',
                'status' => 'active',
            ],
            [
                'name' => 'View Reports',
                'key' => 'reports.view',
                'status' => 'active',
            ],
            [
                'name' => 'Export Reports',
                'key' => 'reports.export',
                'status' => 'active',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['key' => $permission['key']],
                $permission
            );
        }
    }
}
