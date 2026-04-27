<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'user_id' => 3, // Teacher
                'teacher_code' => 'TEACH-001',
                'status' => 'active',
            ],
            [
                'user_id' => 4, // Teacher
                'teacher_code' => 'TEACH-002',
                'status' => 'active',
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::updateOrCreate(
                ['teacher_code' => $teacher['teacher_code']],
                $teacher
            );
        }
    }
}
