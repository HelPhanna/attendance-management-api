<?php

namespace App\Actions\AttendanceRecord;

use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UpdateAttendanceRecord
{
    public function execute(array $data)
    {
        try {

            DB::beginTransaction();

            $classSessionId = $data['class_session_id'];
            $attendanceDate = $data['date'];
            $records = $data['records'];
            $userId = Auth::id();
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

            // Update existing records (based on unique key)
            if (!empty($rows)) {
                AttendanceRecord::upsert(
                    $rows,
                    ['class_session_id', 'student_id', 'attendance_date'], // unique columns
                    ['recorded_by', 'status', 'comment', 'updated_at'] // columns to update
                );
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Attendance updated successfully.',
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
