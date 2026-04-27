<?php

namespace App\Actions\Classes;

use App\Models\Classes;
use Illuminate\Http\Request;

class CreateClass
{
    public function create(Request $request): Classes
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'room_number' => ['nullable', 'string', 'max:255'],
        ]);

        return Classes::create($validated);
    }
}
