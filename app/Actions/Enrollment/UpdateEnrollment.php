<?php

namespace App\Actions\Enrollment;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UpdateEnrollment
{
    public function update(Request $request, Enrollment $enrollment): Enrollment
    {
        $validated = $request->validate([
            'enrolled_on' => ['sometimes', 'nullable', 'date'],
        ]);

        $enrollment->update($validated);

        return $enrollment->fresh(['student.user', 'classes']);
    }
}
