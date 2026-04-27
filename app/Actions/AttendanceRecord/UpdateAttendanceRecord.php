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
            $records = $data['records'];
            $userId = Auth::id();
            $now = now();

            $rows = collect($records)->map(function ($record) use ($classSessionId, $userId, $now) {
                return [
                    'class_session_id' => $classSessionId,
                    'student_id'       => $record['student_id'],
                    'recorded_by'      => $userId,
                    'status'           => $record['status'] ?? 'present',
                    'comment'          => $record['comment'] ?? null,
                    'updated_at'       => $now,
                ];
            })->toArray();

            // Update existing records (based on unique key)
            AttendanceRecord::upsert(
                $rows,
                ['class_session_id', 'student_id'], // unique columns
                ['recorded_by', 'status', 'comment', 'updated_at'] // columns to update
            );

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
