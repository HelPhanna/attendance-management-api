<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Vichay Teacher',
                'email' => 'john.teacher@example.com',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Phanna Teacher',
                'email' => 'phanna.teacher@example.com',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'SakHak Student',
                'email' => 'sakhak.student@example.com',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Yut Student',
                'email' => 'yut.student@example.com',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Vivhet Student',
                'email' => 'vivhet.student@example.com',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Namhong Student',
                'email' => 'namhong.student@example.com',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
