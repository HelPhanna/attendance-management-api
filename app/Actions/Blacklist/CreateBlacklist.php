<?php

namespace App\Actions\Blacklist;

use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CreateBlacklist
{
    public function create(Request $request): Blacklist
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'term_id' => ['required', 'exists:terms,id'],
            'absence_count' => ['nullable', 'integer', 'min:0'],
            'flagged_at' => ['nullable', 'date'],
        ]);

        $exists = Blacklist::where('student_id', $validated['student_id'])
            ->where('term_id', $validated['term_id'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'student_id' => ['The student is already blacklisted for this term.'],
            ]);
        }

        if (!array_key_exists('absence_count', $validated)) {
            $validated['absence_count'] = 0;
        }

        return Blacklist::create($validated);
    }
}
