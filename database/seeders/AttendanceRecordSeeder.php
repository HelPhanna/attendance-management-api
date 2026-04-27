<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use Illuminate\Database\Seeder;

class AttendanceRecordSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'class_session_id' => 1,
                'student_id' => 1,
                'recorded_by' => 3,
                'status' => 'present',
                'comment' => null,
            ],
            [
                'class_session_id' => 1,
                'student_id' => 2,
                'recorded_by' => 3,
                'status' => 'present',
                'comment' => null,
            ],
            [
                'class_session_id' => 2,
                'student_id' => 1,
                'recorded_by' => 4,
                'status' => 'absent',
                'comment' => 'Sick leave',
            ],
            [
                'class_session_id' => 2,
                'student_id' => 3,
                'recorded_by' => 4,
                'status' => 'present',
                'comment' => null,
            ],
        ];

        foreach ($records as $record) {
            AttendanceRecord::updateOrCreate(
                [
                    'class_session_id' => $record['class_session_id'],
                    'student_id' => $record['student_id'],
                ],
                $record
            );
        }
    }
}
