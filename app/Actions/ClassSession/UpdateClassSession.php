<?php

namespace App\Actions\ClassSession;

use App\Models\ClassSession;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateClassSession
{
    /**
     * Create a new class instance.
     */
public function update(Request $request, ClassSession $classSession)
{
    $validated = $request->validate([
        'class_id' => ['sometimes', 'exists:classes,id'],
        'term_id' => ['sometimes', 'exists:terms,id'],
        'teacher_id' => ['sometimes', 'exists:teachers,id'],
        'subject_id' => ['sometimes', 'exists:subjects,id'],
        'day_of_week' => [
            'sometimes',
            Rule::in(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])
        ],
        'start_time' => ['sometimes', 'date_format:H:i'],
        'end_time' => ['sometimes', 'date_format:H:i', 'after:start_time'],
        'status' => [
            'sometimes',
            Rule::in([
                'scheduled',
                'not_started',
                'ongoing',
                'completed',
                'canceled',
                'postponed',
            ]),
        ],
        'created_on' => ['nullable', 'date'],
    ]);

    $classId = $validated['class_id'] ?? $classSession->class_id;
    $termId = $validated['term_id'] ?? $classSession->term_id;
    $teacherId = $validated['teacher_id'] ?? $classSession->teacher_id;
    $subjectId = $validated['subject_id'] ?? $classSession->subject_id;

    $exists = ClassSession::where('class_id', $classId)
        ->where('term_id', $termId)
        ->where('teacher_id', $teacherId)
        ->where('subject_id', $subjectId)
        ->where('id', '!=', $classSession->id)
        ->exists();

    if ($exists) {
        throw ValidationException::withMessages([
            'class_id' => ['Duplicate record in Class Session.']
        ]);
    }

    $classSession->update($validated);

    return $classSession->refresh();
}

}
