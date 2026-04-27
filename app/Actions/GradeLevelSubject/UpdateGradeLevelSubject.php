<?php

namespace App\Actions\GradeLevelSubject;

use App\Models\GradeLevelSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateGradeLevelSubject
{
    public function execute(Request $request): array
    {
        $validated = $request->validate([
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'subject_id' => ['required', 'array'],
            'subject_id.*' => ['exists:subjects,id'],
        ]);

        return DB::transaction(function () use ($validated) {

            $gradeLevelId = $validated['grade_level_id'];
            $newSubjects = $validated['subject_id'];

            $currentSubjects = GradeLevelSubject::where('grade_level_id', $gradeLevelId)
                ->pluck('subject_id')
                ->toArray();

            $toAdd = array_diff($newSubjects, $currentSubjects);
            $toRemove = array_diff($currentSubjects, $newSubjects);

            // Remove old subjects
            if (!empty($toRemove)) {
                GradeLevelSubject::where('grade_level_id', $gradeLevelId)
                    ->whereIn('subject_id', $toRemove)
                    ->delete();
            }

            // Add new subjects
            $added = [];
            foreach ($toAdd as $sid) {
                $added[] = GradeLevelSubject::create([
                    'grade_level_id' => $gradeLevelId,
                    'subject_id' => $sid,
                ]);
            }

            return [
                'grade_level_id' => $gradeLevelId,
                'added_subjects' => $added,
                'removed_subjects' => array_values($toRemove),
                'final_subjects' => $newSubjects,
            ];
        });
    }
}
