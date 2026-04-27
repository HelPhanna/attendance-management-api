<?php

namespace App\Actions\GradeLevelSubject;

use App\Models\GradeLevelSubject;
use Illuminate\Http\Request;

class CreateGradeLevelSubject
{
    public function execute(Request $request): array
    {
        $validated = $request->validate([
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'subject_id' => ['required', 'array'],
            'subject_id.*' => ['exists:subjects,id'],
        ]);

        $gradeLevelId = $validated['grade_level_id'];

        $created = [];
        $skipped = [];
        $successCount = 0;

        foreach ($validated['subject_id'] as $sid) {

            $exists = GradeLevelSubject::where('grade_level_id', $gradeLevelId)
                ->where('subject_id', $sid)
                ->exists();

            if ($exists) {
                $skipped[] = $sid;
                continue;
            }

            $created[] = GradeLevelSubject::create([
                'grade_level_id' => $gradeLevelId,
                'subject_id' => $sid,
            ]);

            $successCount++;
        }

        return [
            'grade_level_id' => $gradeLevelId,
            'subjects' => $created,
            'assigned_count' => $successCount,
            'skipped_subjects' => $skipped,
        ];
    }
}
