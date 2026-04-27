<?php

namespace App\Actions\AttendanceRecord;

use App\Models\ClassSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FilterAttendanceRecord
{
    public function execute(array $data): array
    {
        try {
            DB::beginTransaction();

            $dayName = Carbon::parse($data['date'])->format('l'); // Monday, Tuesday...

            $query = ClassSession::query()
                ->with([
                    'attendance',      // ✅ your relationship name
                    'class.students',
                ])
                ->where('day_of_week', $dayName)
                ->where('term_id', $data['term_id'])
                ->where('class_id', $data['class_id'])
                ->where('teacher_id', $data['teacher_id']);

            if (!empty($data['start_time'])) {
                $query->where('start_time', $data['start_time']);
            }

            if (!empty($data['end_time'])) {
                $query->where('end_time', $data['end_time']);
            }

            $session = $query->first();

            if (!$session) {
                DB::commit();
                return [
                    'success' => false,
                    'code' => 404,
                    'message' => "No class session found. (date day = {$dayName})",
                    'data' => null,
                ];
            }

            $students = $session->class->students->map(function ($student) use ($session) {
                $record = $session->attendance->firstWhere('student_id', $student->id); // ✅

                return [
                    'student_id' => $student->id,
                    'name'       => trim(($student->last_name ?? '') . ', ' . ($student->first_name ?? '')),
                    'status'     => $record->status ?? null,
                    'comment'    => $record->comment ?? null,
                ];
            })->values();

            DB::commit();

            return [
                'success' => true,
                'code' => 200,
                'message' => 'Filter success.',
                'data' => [
                    'class_session_id' => $session->id,
                    'day_of_week' => $dayName,
                    'start_time' => $session->start_time,
                    'end_time' => $session->end_time,
                    'students' => $students,
                ],
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
