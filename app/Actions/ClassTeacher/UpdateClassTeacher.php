<?php

namespace App\Actions\ClassTeacher;

use App\Models\ClassTeacher;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UpdateClassTeacher
{
    public function update(Request $request, ClassTeacher $classTeacher): ClassTeacher
    {
        $validated = $request->validate([
            'class_id' => ['sometimes', 'exists:classes,id'],
            'teacher_id' => ['sometimes', 'exists:teachers,id'],
            'assigned_at' => ['nullable', 'date'],
        ]);

        $classId = $validated['class_id'] ?? $classTeacher->class_id;
        $teacherId = $validated['teacher_id'] ?? $classTeacher->teacher_id;

        $exists = ClassTeacher::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('id', '!=', $classTeacher->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'class_id' => ['This teacher is already assigned to this class.'],
            ]);
        }

        $classTeacher->update($validated);

        return $classTeacher->refresh();
    }
}
