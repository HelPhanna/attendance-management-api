<?php

namespace App\Actions\GradeLevel;

use App\Models\GradeLevel;

class DeleteGradeLevel
{
    public function execute(int $id): bool
    {
        $gradeLevel = GradeLevel::find($id);

        if (!$gradeLevel) {
            throw new \Exception('Grade level not found.');
        }

        return $gradeLevel->delete();
    }
}
