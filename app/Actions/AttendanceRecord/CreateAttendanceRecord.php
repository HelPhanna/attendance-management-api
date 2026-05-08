<?php

namespace App\Actions\AttendanceRecord;

use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateAttendanceRecord
{
    public function execute(array $data)
    {
        try {

            DB::beginTransaction();

            $classSessionId = $data['class_session_id'];
            $attendanceDate = $data['date'];
            $records = $data['records'];
            $userId = Auth::id(); // recorded_by = logged user

            $now = now();

            $recordCollection = collect($records);
            $rows = $recordCollection
                ->filter(fn($record) => !is_null($record['status'] ?? null))
                ->map(function ($record) use ($classSessionId, $attendanceDate, $userId, $now) {
                    return [
                        'class_session_id' => $classSessionId,
                        'student_id'       => $record['student_id'],
                        'recorded_by'      => $userId,
                        'attendance_date'  => $attendanceDate,
                        'status'           => $record['status'],
                        'comment'          => $record['comment'] ?? null,
                        'created_at'       => $now,
                        'updated_at'       => $now,
                    ];
                })
                ->values()
                ->toArray();

            $unselectedStudentIds = $recordCollection
                ->filter(fn($record) => is_null($record['status'] ?? null))
                ->pluck('student_id')
                ->values()
                ->toArray();

            if (!empty($unselectedStudentIds)) {
                AttendanceRecord::query()
                    ->where('class_session_id', $classSessionId)
                    ->whereDate('attendance_date', $attendanceDate)
                    ->whereIn('student_id', $unselectedStudentIds)
                    ->delete();
            }

            // Because you have unique(class_session_id, student_id)
            if (!empty($rows)) {
                AttendanceRecord::upsert(
                    $rows,
                    ['class_session_id', 'student_id', 'attendance_date'],
                    ['recorded_by', 'status', 'comment', 'updated_at']
                );
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Attendance recorded successfully.',
                'count'   => count($rows)
            ];
        } catch (\Throwable $e) {

            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
