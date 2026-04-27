<?php

namespace Database\Seeders;

use App\Models\GradeLevel;
use Illuminate\Database\Seeder;

class GradeLevelSeeder extends Seeder
{
    public function run(): void
    {
        $gradeLevels = [
            [
                'code' => 'GL-01',
                'name' => 'Grade 1',
                'order_no' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'GL-02',
                'name' => 'Grade 2',
                'order_no' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'GL-03',
                'name' => 'Grade 3',
                'order_no' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'GL-04',
                'name' => 'Grade 4',
                'order_no' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'GL-05',
                'name' => 'Grade 5',
                'order_no' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'GL-06',
                'name' => 'Grade 6',
                'order_no' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($gradeLevels as $gradeLevel) {
            GradeLevel::updateOrCreate(
                ['code' => $gradeLevel['code']],
                $gradeLevel
            );
        }
    }
}
