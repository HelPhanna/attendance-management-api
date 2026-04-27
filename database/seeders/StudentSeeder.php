<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'user_id' => 5, // Student
                'student_code' => 'STU-001',
                'status' => 'active',
            ],
            [
                'user_id' => 6, // Student
                'student_code' => 'STU-002',
                'status' => 'active',
            ],
            [
                'user_id' => 7, // Student
                'student_code' => 'STU-003',
                'status' => 'active',
            ],
            [
                'user_id' => 8, // Student
                'student_code' => 'STU-004',
                'status' => 'active',
            ],
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(
                ['student_code' => $student['student_code']],
                $student
            );
        }
    }
}
