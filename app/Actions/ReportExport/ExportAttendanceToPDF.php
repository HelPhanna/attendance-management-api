<?php

namespace App\Actions\ReportExport;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ExportAttendanceToPDF
{
    /**
     * Create a new class instance.
     */
    public function execute(array $reportData)
    {
        $fileName = 'attendance_report_' . now()->format('Y_m_d_His') . '.pdf';
        $path = 'exports/'.$fileName;

        Storage::disk('public')->makeDirectory('exports');

        $pdf = Pdf::loadView('reports.attendance' , $reportData);
        $pdf->setPaper('A4' , 'portrait');
        $fullPath = storage_path('app/public/'.$path);

        \file_put_contents($fullPath, $pdf->output());
        return [
            'path'     => $path,
            'filename' => $fileName,
            'size_kb'  => round(filesize($fullPath) / 1024, 0),
        ];

    }
}
