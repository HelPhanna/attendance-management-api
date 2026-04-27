<?php

namespace App\Actions\Blacklist;

use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UpdateBlacklist
{
    public function update(Request $request, Blacklist $blacklist): Blacklist
    {
        $validated = $request->validate([
            'student_id' => ['sometimes', 'exists:students,id'],
            'term_id' => ['sometimes', 'exists:terms,id'],
            'absence_count' => ['nullable', 'integer', 'min:0'],
            'flagged_at' => ['nullable', 'date'],
        ]);

        $studentId = $validated['student_id'] ?? $blacklist->student_id;
        $termId = $validated['term_id'] ?? $blacklist->term_id;

        $exists = Blacklist::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('id', '!=', $blacklist->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'student_id' => ['The student is already blacklisted for this term.'],
            ]);
        }

        $blacklist->update($validated);

        return $blacklist->refresh();
    }
}
