<?php

namespace App\Actions\AcademicYear;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class UpdateAcademicYear
{
    /**
     * Create a new class instance.
     */
    protected $academicYear;
    public function __construct(AcademicYear $academicYear)
    {
        $this->academicYear = $academicYear;
    }

    public function update(Request $request){
        
        $validated = $request->validate([
            'name' => ['required','string'],
            'start_date' => ['required','date'],
            'end_date' => ['required','date', 'after:start_date'],
        ]);

        $this->academicYear->update($validated);

        return $this->academicYear->refresh();

    }
}
