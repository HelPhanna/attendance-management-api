<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments = [
            [
                'class_id' => 1,
                'student_id' => 1,
                'grade_level_id' => 1,
                'enrolled_on' => Carbon::create(2025, 1, 15),
            ],
            [
                'class_id' => 1,
                'student_id' => 2,
                'grade_level_id' => 1,
                'enrolled_on' => Carbon::create(2025, 1, 15),
            ],
            [
                'class_id' => 2,
                'student_id' => 3,
                'grade_level_id' => 1,
                'enrolled_on' => Carbon::create(2025, 1, 15),
            ],
            [
                'class_id' => 3,
                'student_id' => 4,
                'grade_level_id' => 2,
                'enrolled_on' => Carbon::create(2025, 1, 15),
            ],
        ];

        foreach ($enrollments as $enrollment) {
            Enrollment::updateOrCreate(
                [
                    'class_id' => $enrollment['class_id'],
                    'student_id' => $enrollment['student_id'],
                ],
                $enrollment
            );
        }
    }
}
