<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manageUsers = Permission::firstOrCreate(
            ['key' => 'users.manage'],
            ['name' => 'Manage Users', 'status' => 'active']
        );

        $manageRoles = Permission::firstOrCreate(
            ['key' => 'roles.manage'],
            ['name' => 'Manage Roles', 'status' => 'active']
        );

        $adminRole = Role::firstOrCreate(
            ['key' => 'admin'],
            ['name' => 'Admin', 'status' => 'active']
        );

        $adminRole->permissions()->syncWithoutDetaching([
            $manageUsers->id,
            $manageRoles->id,
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
