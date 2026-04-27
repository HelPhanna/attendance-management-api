<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TermSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('terms')->insert([
            [
                'name' => 'Term 1',
                'academic_year_id' => 1, // make sure this academic year exists
                'start_date' => Carbon::create(2025, 1, 1),
                'end_date' => Carbon::create(2025, 4, 30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Term 2',
                'academic_year_id' => 1,
                'start_date' => Carbon::create(2025, 5, 1),
                'end_date' => Carbon::create(2025, 8, 31),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Term 3',
                'academic_year_id' => 1,
                'start_date' => Carbon::create(2025, 9, 1),
                'end_date' => Carbon::create(2025, 12, 31),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
