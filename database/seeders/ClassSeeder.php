<?php

namespace Database\Seeders;

use App\Models\Classes;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            [
                'name' => 'Class 1-A',
                'grade_level_id' => 1,
                'start_date' => Carbon::create(2025, 1, 1),
                'end_date' => Carbon::create(2025, 12, 31),
                'room_number' => '101',
            ],
            [
                'name' => 'Class 1-B',
                'grade_level_id' => 1,
                'start_date' => Carbon::create(2025, 1, 1),
                'end_date' => Carbon::create(2025, 12, 31),
                'room_number' => '102',
            ],
            [
                'name' => 'Class 2-A',
                'grade_level_id' => 2,
                'start_date' => Carbon::create(2025, 1, 1),
                'end_date' => Carbon::create(2025, 12, 31),
                'room_number' => '201',
            ],
            [
                'name' => 'Class 2-B',
                'grade_level_id' => 2,
                'start_date' => Carbon::create(2025, 1, 1),
                'end_date' => Carbon::create(2025, 12, 31),
                'room_number' => '202',
            ],
            [
                'name' => 'Class 3-A',
                'grade_level_id' => 3,
                'start_date' => Carbon::create(2025, 1, 1),
                'end_date' => Carbon::create(2025, 12, 31),
                'room_number' => '301',
            ],
        ];

        foreach ($classes as $class) {
            Classes::updateOrCreate(
                ['name' => $class['name']],
                $class
            );
        }
    }
}
