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
            $records = $data['records'];
            $userId = Auth::id(); // recorded_by = logged user

            $now = now();

            $rows = collect($records)->map(function ($record) use ($classSessionId, $userId, $now) {
                return [
                    'class_session_id' => $classSessionId,
                    'student_id'       => $record['student_id'],
                    'recorded_by'      => $userId,
                    'status'           => $record['status'] ?? 'present',
                    'comment'          => $record['comment'] ?? null,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            })->toArray();

            // Because you have unique(class_session_id, student_id)
            AttendanceRecord::upsert(
                $rows,
                ['class_session_id', 'student_id'],
                ['recorded_by', 'status', 'comment', 'updated_at']
            );

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
