<?php

namespace Database\Seeders;

use App\Models\ReportExports;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReportExportSeeder extends Seeder
{
    public function run(): void
    {
        $exports = [
            [
                'user_id' => 3,
                'class_id' => 1,
                'report_type' => 'attendance',
                'file_type' => 'pdf',
                'status' => 'completed',
                'file_path' => 'exports/class-1-report-2025-01-15.pdf',
                'file_size_kb' => 256,
                'filters' => json_encode(['term' => 1, 'class' => 1]),
                'exported_at' => Carbon::create(2025, 1, 15, 10, 30),
            ],
            [
                'user_id' => 4,
                'class_id' => 2,
                'report_type' => 'attendance',
                'file_type' => 'xlsx',
                'status' => 'completed',
                'file_path' => 'exports/class-2-report-2025-01-15.xlsx',
                'file_size_kb' => 512,
                'filters' => json_encode(['term' => 1, 'class' => 2]),
                'exported_at' => Carbon::create(2025, 1, 15, 11, 45),
            ],
        ];

        foreach ($exports as $export) {
            ReportExports::updateOrCreate(
                [
                    'user_id' => $export['user_id'],
                    'class_id' => $export['class_id'],
                    'file_path' => $export['file_path'],
                ],
                $export
            );
        }
    }
}
