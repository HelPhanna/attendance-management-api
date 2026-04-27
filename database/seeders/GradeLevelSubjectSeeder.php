<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeLevelSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate to avoid duplicates
        DB::table('grade_level_subjects')->truncate();

        $gradeLevelSubjects = [
            // Grade 1 subjects
            [
                'grade_level_id' => 1,
                'subject_id' => 1, // Mathematics
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_level_id' => 1,
                'subject_id' => 2, // English
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_level_id' => 1,
                'subject_id' => 6, // History
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_level_id' => 1,
                'subject_id' => 7, // Geography
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Grade 2 subjects
            [
                'grade_level_id' => 2,
                'subject_id' => 1, // Mathematics
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_level_id' => 2,
                'subject_id' => 2, // English
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_level_id' => 2,
                'subject_id' => 3, // Physics
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Grade 3 subjects
            [
                'grade_level_id' => 3,
                'subject_id' => 1, // Mathematics
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_level_id' => 3,
                'subject_id' => 2, // English
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_level_id' => 3,
                'subject_id' => 3, // Physics
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_level_id' => 3,
                'subject_id' => 4, // Chemistry
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_level_id' => 3,
                'subject_id' => 8, // Computer Science
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('grade_level_subjects')->insert($gradeLevelSubjects);
    }
}
