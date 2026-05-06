<?php

namespace App\Actions\ReportExport;

use App\Models\AttendanceRecord;
use App\Models\Classes;
use App\Models\ClassSession;
use App\Models\Student;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GenerateAttendanceReportData
{
    public function execute(array $filter): array
    {
        // ── Detect which mode: daily (date) vs range (date_from / date_to) ──────
        $isDaily = isset($filter['date']) && !isset($filter['date_from']);

        if ($isDaily) {
            return $this->executeDaily($filter);
        }

        return $this->executeRange($filter);
    }

    // ── Daily export: single date + class session (from AttendancePage) ─────────
    private function executeDaily(array $filter): array
    {
        $validated = validator($filter, [
            'date'       => 'required|date',
            'term_id'    => 'required|integer|exists:terms,id',
            'class_id'   => 'required|integer|exists:classes,id',
            'teacher_id' => 'required|integer',
            'class_session_id' => 'nullable|integer|exists:class_sessions,id',
            'start_time' => 'nullable|string',
            'end_time'   => 'nullable|string',
        ])->validate();

        $date    = $validated['date'];
        $dayName = Carbon::parse($date)->format('l'); // e.g. "Monday"

        // Prefer exact class session from UI after a successful search.
        if (!empty($validated['class_session_id'])) {
            $session = ClassSession::query()
                ->where('id', $validated['class_session_id'])
                ->where('term_id', $validated['term_id'])
                ->where('class_id', $validated['class_id'])
                ->where('teacher_id', $validated['teacher_id'])
                ->first();
        } else {
            // Fallback to filter-based lookup.
            $query = ClassSession::query()
                ->where('day_of_week', $dayName)
                ->where('term_id',     $validated['term_id'])
                ->where('class_id',    $validated['class_id'])
                ->where('teacher_id',  $validated['teacher_id']);

            if (!empty($validated['start_time'])) {
                $query->whereRaw('TIME(start_time) = TIME(?)', [$validated['start_time']]);
            }
            if (!empty($validated['end_time'])) {
                $query->whereRaw('TIME(end_time) = TIME(?)', [$validated['end_time']]);
            }

            $session = $query->first();
        }

        if (!$session) {
            throw new \Exception("No class session found for {$dayName} with the given filters.");
        }

        $term  = Term::find($validated['term_id']);
        $class = Classes::findOrFail($validated['class_id']);

        // Get all attendance records for this session on this date
        $records = AttendanceRecord::query()
            ->where('class_session_id', $session->id)
            ->whereDate('created_at', $date)
            ->get()
            ->keyBy('student_id');

        // Get enrolled students via the class (uses Student::classes() belongsToMany through enrollment table)
        $students = Student::whereHas('classes', function ($q) use ($validated) {
                $q->where('classes.id', $validated['class_id']);
            })
            ->with(['user:id,name'])
            ->get();

        if ($students->isEmpty()) {
            throw new \Exception("No students enrolled in this class.");
        }

        $reportRows  = [];
        $grandTotals = ['present' => 0, 'absent' => 0, 'permission' => 0];

        foreach ($students as $student) {
            $record = $records->get($student->id);
            $status = $record->status ?? 'absent';

            $present    = $status === 'present'    ? 1 : 0;
            $absent     = $status === 'absent'     ? 1 : 0;
            $permission = $status === 'permission' ? 1 : 0;

            $reportRows[] = [
                'student_code' => $student->student_code ?? '-',
                'name'         => $student->user->name ?? '-',
                'class_name'   => $class->name ?? '-',
                'present'      => $present,
                'permission'   => $permission,
                'absent'       => $absent,
            ];

            $grandTotals['present']    += $present;
            $grandTotals['permission'] += $permission;
            $grandTotals['absent']     += $absent;
        }

        return [
            'filter'     => $filter,
            'period'     => ['from' => $date, 'to' => $date],
            'term_name'  => $term->name ?? '-',
            'class_name' => $class->name ?? '-',
            'rows'       => $reportRows,
            'totals'     => $grandTotals,
        ];
    }

    // ── Range export: date_from / date_to (from ReportsAnalyticsPage) ───────────
    private function executeRange(array $filter): array
    {
        $validated = validator($filter, [
            'date_from'        => 'required|date',
            'date_to'          => 'required|date|after_or_equal:date_from',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id'          => 'required|exists:terms,id',
            'class_id'         => 'required|exists:classes,id',
        ])->validate();

        $terms = Term::where('id', $validated['term_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->first();

        if (!$terms) {
            throw ValidationException::withMessages([
                'term_id' => 'Term does not belong to selected academic year.'
            ]);
        }

        $class = Classes::findOrFail($validated['class_id']);

        $summary = AttendanceRecord::query()
            ->whereHas('classSession', function ($p) use ($validated) {
                $p->where('class_id', $validated['class_id'])
                  ->where('term_id',  $validated['term_id']);
            })
            ->whereBetween('created_at', [
                $validated['date_from'] . ' 00:00:00',
                $validated['date_to']   . ' 23:59:59',
            ])
            ->select('student_id', 'status', DB::raw('COUNT(*) as count'))
            ->groupBy('student_id', 'status')
            ->get()
            ->groupBy('student_id');

        $studentIds = $summary->keys()->toArray();

        if (empty($studentIds)) {
            throw new \Exception("No attendance records found for this class and term.");
        }

        $students = Student::whereIn('id', $studentIds)
            ->with(['user' => fn($p) => $p->select('users.id', 'users.name')])
            ->get(['students.id', 'students.student_code', 'students.user_id']);

        $reportRows  = [];
        $grandTotals = ['present' => 0, 'absent' => 0, 'permission' => 0];

        foreach ($students as $student) {
            $counts = $summary->get($student->id, collect());

            $present    = (int) ($counts->firstWhere('status', 'present')->count    ?? 0);
            $permission = (int) ($counts->firstWhere('status', 'permission')->count ?? 0);
            $absent     = (int) ($counts->firstWhere('status', 'absent')->count     ?? 0);

            $reportRows[] = [
                'student_code' => $student->student_code ?? '-',
                'name'         => $student->user->name ?? '-',
                'class_name'   => $class->name ?? '-',
                'present'      => $present,
                'permission'   => $permission,
                'absent'       => $absent,
            ];

            $grandTotals['present']    += $present;
            $grandTotals['permission'] += $permission;
            $grandTotals['absent']     += $absent;
        }

        return [
            'filter'     => $filter,
            'period'     => ['from' => $validated['date_from'], 'to' => $validated['date_to']],
            'term_name'  => $terms->name ?? '-',
            'class_name' => $class->name ?? '-',
            'rows'       => $reportRows,
            'totals'     => $grandTotals,
        ];
    }
}
