<?php

namespace App\Actions\Student;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateStudent
{
    public function update(Request $request, Student $student): Student
    {
        $validated = $request->validate([
            'user_id'      => ['sometimes', 'integer', 'exists:users,id'],
            'student_code' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('students', 'student_code')->ignore($student->id),
            ],
            'status' => ['sometimes', 'string', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        // If changing user_id, ensure the new user isn't already linked
        if (isset($validated['user_id']) && $validated['user_id'] !== (int) $student->user_id) {
            if (Student::where('user_id', $validated['user_id'])->exists()) {
                throw ValidationException::withMessages([
                    'user_id' => ['This user is already linked to a student.'],
                ]);
            }
        }

        $student->update($validated);

        return $student->fresh('user');
    }
}
