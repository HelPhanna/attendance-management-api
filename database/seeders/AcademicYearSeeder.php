<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $academicYears = [
            [
                'name' => '2024-2025',
                'start_date' => Carbon::create(2024, 1, 1),
                'end_date' => Carbon::create(2024, 12, 31),
            ],
            [
                'name' => '2025-2026',
                'start_date' => Carbon::create(2025, 1, 1),
                'end_date' => Carbon::create(2025, 12, 31),
            ],
            [
                'name' => '2026-2027',
                'start_date' => Carbon::create(2026, 1, 1),
                'end_date' => Carbon::create(2026, 12, 31),
            ],
        ];

        foreach ($academicYears as $year) {
            AcademicYear::updateOrCreate(
                ['name' => $year['name']],
                $year
            );
        }
    }
}
