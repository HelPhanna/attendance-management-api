<?php

namespace App\Actions\Term;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UpdateTerm
{
    /**
     * Create a new class instance.
     */
    protected $term;
    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    public function update (Request $request){
        $validated = $request->validate([
            'name' => 'required|string',
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',           
        ]);

        $academicYearId = $validated['academic_year_id'] ?? $this->term->academic_year_id;

        $haveTerm = Term::where('academic_year_id',$academicYearId)->where('start_date',$this->term->start_date)->where('id','!=',$this->term->id)->exists();

        if($haveTerm){
            throw ValidationException::withMessages([
                'duplicate'=>['This term already exists for the academic year.']
            ]);
        }

        $this->term->update($validated);

        return $this->term->refresh();
    }
}
