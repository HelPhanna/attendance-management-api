<?php

namespace Database\Seeders;

use App\Models\Blacklist;
use Illuminate\Database\Seeder;

class BlacklistSeeder extends Seeder
{
    public function run(): void
    {
        $blacklists = [
            [
                'student_id' => 1,
                'term_id' => 1,
                'absence_count' => 5,
                'flagged_at' => now(),
            ],
        ];

        foreach ($blacklists as $blacklist) {
            Blacklist::updateOrCreate(
                [
                    'student_id' => $blacklist['student_id'],
                    'term_id' => $blacklist['term_id'],
                ],
                $blacklist
            );
        }
    }
}
