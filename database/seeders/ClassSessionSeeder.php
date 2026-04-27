<?php

namespace Database\Seeders;

use App\Models\ClassSession;
use Illuminate\Database\Seeder;

class ClassSessionSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        // Create sessions for each day
        foreach ($daysOfWeek as $day) {
            $sessions[] = [
                'class_id' => 1,
                'teacher_id' => 1,
                'term_id' => 1,
                'subject_id' => 1,
                'day_of_week' => $day,
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'status' => 'scheduled',
            ];

            $sessions[] = [
                'class_id' => 2,
                'teacher_id' => 2,
                'term_id' => 1,
                'subject_id' => 2,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'status' => 'scheduled',
            ];

            $sessions[] = [
                'class_id' => 3,
                'teacher_id' => 1,
                'term_id' => 1,
                'subject_id' => 3,
                'day_of_week' => $day,
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'status' => 'scheduled',
            ];
        }

        foreach ($sessions as $session) {
            ClassSession::updateOrCreate(
                [
                    'class_id' => $session['class_id'],
                    'day_of_week' => $session['day_of_week'],
                    'start_time' => $session['start_time'],
                ],
                $session
            );
        }
    }
}
