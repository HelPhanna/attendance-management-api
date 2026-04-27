<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate to avoid duplicates
        DB::table('user_role')->truncate();

        $userRoles = [
            [
                'user_id' => 1,
                'role_id' => 1, // Super Admin
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'role_id' => 2, // Admin
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'role_id' => 3, // Teacher
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'role_id' => 3, // Teacher
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'role_id' => 4, // Student
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'role_id' => 4, // Student
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'role_id' => 4, // Student
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'role_id' => 4, // Student
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('user_role')->insert($userRoles);
    }
}
