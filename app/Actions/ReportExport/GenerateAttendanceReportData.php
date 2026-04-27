<?php

namespace App\Actions\ReportExport;

use App\Models\AttendanceRecord;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GenerateAttendanceReportData
{
    public function execute(array $filter): array 
    {
        $validated = validator($filter, [
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id' => 'required|exists:terms,id',
            'class_id' => 'required|exists:classes,id',
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

        $summery = AttendanceRecord::query()
            ->whereHas('classSession', function ($p) use ($validated) {
                $p->where('class_id', $validated['class_id'])
                  ->where('term_id', $validated['term_id']);
            })
            ->whereBetween('created_at', [
                $validated['date_from'] . ' 00:00:00',
                $validated['date_to'] . ' 23:59:59'
            ])
            ->select('student_id', 'status', DB::raw('COUNT(*) as count'))
            ->groupBy('student_id', 'status')
            ->get()
            ->groupBy('student_id');
            
        $studentIds = $summery->keys()->toArray();

        if (empty($studentIds)) {
            throw new \Exception("No attendance records found for this class and term.");
        }

        $students = Student::whereIn('id', $studentIds)
            ->with(['user' => fn($p) => $p->select('users.id', 'users.name')])
            ->get([
                'students.id',
                'students.student_code',
                'students.user_id'
            ]);

        $reportRows = [];
        $grandTotals = ['present' => 0, 'absent' => 0, 'permission' => 0];

        foreach ($students as $student) {
            $counts = $summery->get($student->id, collect());

            $present = (int) ($counts->firstWhere('status', 'present')->count ?? 0);
            $permission = (int) ($counts->firstWhere('status', 'permission')->count ?? 0);
            $absent = (int) ($counts->firstWhere('status', 'absent')->count ?? 0);

            $reportRows[] = [
                'student_code' => $student->student_code ?? '-',
                'name' => $student->user->name ?? '-',
                'class_name' => $class->name ?? '-',
                'present' => $present,
                'permission' => $permission,
                'absent' => $absent,
            ];

            $grandTotals['present'] += $present;
            $grandTotals['permission'] += $permission;
            $grandTotals['absent'] += $absent;
        }

        return [
            'filter' => $filter,
            'period' => ['from' => $validated['date_from'], 'to' => $validated['date_to']],
            'term_name' => $terms->name ?? '-',
            'class_name' => $class->name ?? '-',
            'rows' => $reportRows,
            'totals' => $grandTotals,
        ];
    }
}