<?php

namespace App\Actions\ClassTeacher;

use App\Models\ClassTeacher;

class DeleteClassTeacher
{
    public function delete(ClassTeacher $classTeacher): void
    {
        $classTeacher->delete();
    }
}
