<?php

namespace App\Actions\Student;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CreateStudent
{
    public function create(Request $request): Student
    {
        $validated = $request->validate([
            'user_id'      => ['required', 'integer', 'exists:users,id'],
            'student_code' => ['required', 'string', 'max:255', 'unique:students,student_code'],
            'status'       => ['nullable', 'string', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        // Ensure one user has at most one student record
        if (Student::where('user_id', $validated['user_id'])->exists()) {
            throw ValidationException::withMessages([
                'user_id' => ['This user is already linked to a student.'],
            ]);
        }

        $validated['status'] = $validated['status'] ?? 'active';

        return Student::create($validated);
    }
}
