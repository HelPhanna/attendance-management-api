<?php

namespace App\Actions\Student;

use App\Models\Student;

class DeleteStudent
{
    public function delete(Student $student): void
    {
        $student->delete();
    }
}
