<?php

namespace App\Actions\ReportExport;

use App\Exports\AttendanceReportExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportAttendanceToEXCEL
{
    public function execute(array $reportData): array
    {
        $filename = 'attendance_report_' . now()->format('Y_m_d_His') . '.xlsx';
        
        $path = 'exports/' . $filename;

        $export = new AttendanceReportExport($reportData);

        Excel::store($export, $path, 'public');

        $sizeKb = round(Storage::disk('public')->size($path) / 1024, 2);

        return [
            'path' => $path,          
            'filename' => $filename, 
            'size_kb' => $sizeKb
        ];
    }
}