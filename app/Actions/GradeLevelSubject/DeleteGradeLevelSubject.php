<?php

namespace App\Actions\GradeLevelSubject;

use App\Models\GradeLevelSubject;
use App\Models\GradeLevel;

class DeleteGradeLevelSubject
{
    public function execute(int $gradeLevelId): array
    {
        $gradeLevel = GradeLevel::find($gradeLevelId);

        if (!$gradeLevel) {
            throw new \Exception('Grade level not found.');
        }

        $deletedCount = GradeLevelSubject::where('grade_level_id', $gradeLevelId)
            ->delete();

        return [
            'grade_level_id' => $gradeLevelId,
            'deleted_count' => $deletedCount,
        ];
    }
}
