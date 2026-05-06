<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceAnalyticsController extends Controller
{
    public function reportSummary(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'term_id' => ['required', 'integer', 'exists:terms,id'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
        ]);

        $baseQuery = AttendanceRecord::query()
            ->join('class_sessions', 'attendance_records.class_session_id', '=', 'class_sessions.id')
            ->join('terms', 'class_sessions.term_id', '=', 'terms.id')
            ->where('terms.academic_year_id', $validated['academic_year_id'])
            ->where('class_sessions.term_id', $validated['term_id'])
            ->where('class_sessions.class_id', $validated['class_id'])
            ->whereBetween(DB::raw('DATE(attendance_records.created_at)'), [
                $validated['date_from'],
                $validated['date_to'],
            ]);

        $summaryRow = (clone $baseQuery)
            ->selectRaw("
                COUNT(*) as total_records,
                SUM(CASE WHEN attendance_records.status = 'present' THEN 1 ELSE 0 END) as total_present,
                SUM(CASE WHEN attendance_records.status = 'absent' THEN 1 ELSE 0 END) as total_absent,
                SUM(CASE WHEN attendance_records.status = 'permission' THEN 1 ELSE 0 END) as total_permission,
                COUNT(DISTINCT DATE(attendance_records.created_at)) as classes_held
            ")
            ->first();

        $totalRecords = (int) ($summaryRow->total_records ?? 0);
        $totalPresent = (int) ($summaryRow->total_present ?? 0);
        $totalAbsent = (int) ($summaryRow->total_absent ?? 0);
        $totalPermission = (int) ($summaryRow->total_permission ?? 0);
        $classesHeld = (int) ($summaryRow->classes_held ?? 0);
        $avgAttendance = $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100, 1) : 0.0;

        $daily = (clone $baseQuery)
            ->join('classes', 'class_sessions.class_id', '=', 'classes.id')
            ->selectRaw("
                DATE(attendance_records.created_at) as date,
                classes.name as class_name,
                SUM(CASE WHEN attendance_records.status = 'present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN attendance_records.status = 'absent' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN attendance_records.status = 'permission' THEN 1 ELSE 0 END) as permission,
                ROUND(
                    100 * SUM(CASE WHEN attendance_records.status = 'present' THEN 1 ELSE 0 END) / NULLIF(COUNT(*), 0),
                    1
                ) as rate
            ")
            ->groupBy(DB::raw('DATE(attendance_records.created_at)'), 'classes.name')
            ->orderBy(DB::raw('DATE(attendance_records.created_at)'), 'asc')
            ->get()
            ->map(fn ($row) => [
                'date' => $row->date,
                'class_name' => $row->class_name,
                'present' => (int) $row->present,
                'absent' => (int) $row->absent,
                'permission' => (int) $row->permission,
                'rate' => (float) ($row->rate ?? 0),
            ])
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'classes_held' => $classesHeld,
                    'avg_attendance' => $avgAttendance,
                    'total_present' => $totalPresent,
                    'total_absent' => $totalAbsent,
                    'total_permission' => $totalPermission,
                ],
                'daily' => $daily,
            ],
        ]);
    }

    public function blacklistOverview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'term_id' => ['required', 'integer', 'exists:terms,id'],
            'threshold' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $threshold = (int) ($validated['threshold'] ?? 16);

        $rowsQuery = AttendanceRecord::query()
            ->join('class_sessions', 'attendance_records.class_session_id', '=', 'class_sessions.id')
            ->join('terms', 'class_sessions.term_id', '=', 'terms.id')
            ->join('students', 'attendance_records.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('terms.academic_year_id', $validated['academic_year_id'])
            ->where('class_sessions.term_id', $validated['term_id'])
            ->groupBy('students.id', 'students.student_code', 'users.name')
            ->selectRaw("
                students.id as student_id,
                students.student_code as roll_no,
                users.name as student_name,
                SUM(CASE WHEN attendance_records.status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance_records.status = 'absent' THEN 1 ELSE 0 END) as total_absences,
                SUM(CASE WHEN attendance_records.status = 'permission' THEN 1 ELSE 0 END) as permission_count,
                COUNT(*) as total_records
            ");

        if (!empty($validated['search'])) {
            $search = trim($validated['search']);
            $rowsQuery->where(function ($query) use ($search) {
                $query->where('users.name', 'like', "%{$search}%")
                    ->orWhere('students.student_code', 'like', "%{$search}%");
            });
        }

        $rows = $rowsQuery
            ->orderBy('users.name')
            ->get()
            ->map(function ($row) use ($threshold) {
                $presentCount = (int) $row->present_count;
                $totalAbsences = (int) $row->total_absences;
                $permissionCount = (int) $row->permission_count;
                $totalRecords = (int) $row->total_records;

                $attendanceRate = $totalRecords > 0
                    ? round(($presentCount / $totalRecords) * 100, 1)
                    : 0.0;

                $status = 'good_standing';
                if ($totalAbsences >= $threshold) {
                    $status = 'blacklisted';
                } elseif ($totalAbsences >= max(1, $threshold - 2)) {
                    $status = 'warning';
                }

                return [
                    'student_id' => (int) $row->student_id,
                    'roll_no' => $row->roll_no ?? '-',
                    'student_name' => $row->student_name ?? '-',
                    'total_absences' => $totalAbsences,
                    'attendance_rate' => $attendanceRate,
                    'status' => $status,
                    'present_count' => $presentCount,
                    'permission_count' => $permissionCount,
                ];
            })
            ->values();

        $blacklistedCount = $rows->where('status', 'blacklisted')->count();
        $warningCount = $rows->where('status', 'warning')->count();
        $goodStandingCount = $rows->where('status', 'good_standing')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'threshold' => $threshold,
                    'total_students' => $rows->count(),
                    'blacklisted_count' => $blacklistedCount,
                    'warning_count' => $warningCount,
                    'good_standing_count' => $goodStandingCount,
                ],
                'rows' => $rows,
            ],
        ]);
    }
}

