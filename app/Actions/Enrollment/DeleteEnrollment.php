<?php

namespace App\Actions\Enrollment;

use App\Models\Enrollment;

class DeleteEnrollment
{
    public function delete(Enrollment $enrollment): void
    {
        $enrollment->delete();
    }
}
