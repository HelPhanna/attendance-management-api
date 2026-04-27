<?php

namespace App\Actions\ClassTeacher;

use App\Models\ClassTeacher;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CreateClassTeacher
{
    public function create(Request $request): ClassTeacher
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'assigned_at' => ['nullable', 'date'],
        ]);

        $exists = ClassTeacher::where('class_id', $validated['class_id'])
            ->where('teacher_id', $validated['teacher_id'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'class_id' => ['This teacher is already assigned to this class.'],
            ]);
        }

        return ClassTeacher::create($validated);
    }
}
