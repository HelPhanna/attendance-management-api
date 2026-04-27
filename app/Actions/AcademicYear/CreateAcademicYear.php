<?php

namespace App\Actions\AcademicYear;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class CreateAcademicYear
{
    /**
     * Create a new class instance.
     */

    protected $academicYear;
    public function __construct(AcademicYear $academicYear)
    {
        $this->academicYear = $academicYear;
    }

    public function create(Request $request)  {
        
        $validated = $request->validate([
            'name' => ['required','string'],
            'start_date' => ['required','date'],
            'end_date' => ['required','date', 'after:start_date'],
        ]);

        return $this->academicYear->create($validated);

    }
}
