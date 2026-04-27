<?php

namespace App\Actions\ReportExport;

use App\Models\ReportExports;
use Illuminate\Contracts\Auth\Authenticatable;

class LogAttendanceExport
{
    public function execute(Authenticatable $user , string $type, string $path, int $sizeKb, array $filters): void
    {
        $classId = isset($filters['class_id']) ? (int) $filters['class_id'] : null;
        if (!$classId) {
            return;
        }

        ReportExports::create([
            'user_id'      => $user->id,
            'class_id'     => $classId,
            'report_type'  => 'attendance',
            'file_type'    => $type,
            'status'       => 'completed',
            'file_path'    => $path,
            'file_size_kb' => $sizeKb,
            'filters'      => $filters,
            'exported_at'  => now(),
        ]);
    }
}
