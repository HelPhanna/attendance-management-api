<?php

namespace App\Actions\Classes;

use App\Models\Classes;
use Illuminate\Http\Request;

class UpdateClass
{
    public function update(Request $request, Classes $class): Classes
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'grade_level_id' => ['sometimes', 'exists:grade_levels,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'room_number' => ['nullable', 'string', 'max:255'],
        ]);

        $class->update($validated);

        return $class->refresh();
    }
}
