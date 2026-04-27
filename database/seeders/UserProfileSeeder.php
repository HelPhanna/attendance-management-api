<?php

namespace Database\Seeders;

use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'user_id' => 1,
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone' => '+1234567890',
                'gender' => 'Male',
                'date_of_birth' => '1990-01-01',
                'address' => '123 Admin Street',
            ],
            [
                'user_id' => 2,
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '+1234567891',
                'gender' => 'Male',
                'date_of_birth' => '1991-01-01',
                'address' => '456 Main Street',
            ],
            [
                'user_id' => 3,
                'first_name' => 'John',
                'last_name' => 'Teacher',
                'phone' => '+1234567892',
                'gender' => 'Male',
                'date_of_birth' => '1988-01-01',
                'address' => '789 Teacher Lane',
            ],
            [
                'user_id' => 4,
                'first_name' => 'Jane',
                'last_name' => 'Teacher',
                'phone' => '+1234567893',
                'gender' => 'Female',
                'date_of_birth' => '1989-01-01',
                'address' => '321 School Road',
            ],
            [
                'user_id' => 5,
                'first_name' => 'Alice',
                'last_name' => 'Student',
                'phone' => '+1234567894',
                'gender' => 'Female',
                'date_of_birth' => '2010-01-01',
                'address' => '654 Student Ave',
            ],
            [
                'user_id' => 6,
                'first_name' => 'Bob',
                'last_name' => 'Student',
                'phone' => '+1234567895',
                'gender' => 'Male',
                'date_of_birth' => '2010-06-01',
                'address' => '987 Youth Blvd',
            ],
            [
                'user_id' => 7,
                'first_name' => 'Charlie',
                'last_name' => 'Student',
                'phone' => '+1234567896',
                'gender' => 'Male',
                'date_of_birth' => '2010-09-01',
                'address' => '135 College Dr',
            ],
            [
                'user_id' => 8,
                'first_name' => 'Diana',
                'last_name' => 'Student',
                'phone' => '+1234567897',
                'gender' => 'Female',
                'date_of_birth' => '2011-01-01',
                'address' => '246 Education St',
            ],
        ];

        foreach ($profiles as $profile) {
            UserProfile::updateOrCreate(
                ['user_id' => $profile['user_id']],
                $profile
            );
        }
    }
}
