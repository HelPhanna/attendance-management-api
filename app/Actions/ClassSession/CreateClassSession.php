<?php

namespace App\Actions\ClassSession;

use App\Models\ClassSession;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateClassSession
{
    /**
     * Create a new class instance.
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'term_id' => ['required', 'exists:terms,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'day_of_week' => ['required', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday','Friday', 'Saturday', 'Sunday'])],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i' , 'after:start_time'],
            'status'      => [
                'sometimes', // only validate if present (good for updates)
                Rule::in([
                    'scheduled',
                    'not_started',
                    'ongoing',
                    'completed',
                    'canceled',
                    'postponed',
                ]),  
            ],
            'created_on'  => ['nullable', 'date'],
        ]);

        return ClassSession::create($validated);
    }
}
