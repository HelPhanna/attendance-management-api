<?php

namespace App\Actions\Term;

use App\Models\Term;
use Illuminate\Http\Request;

class CreateTerm
{
    /**
     * Create a new class instance.
     */

    protected $term;
    public function __construct(Term $term)
    {
        $this->term = $term; 
    }

    public function create(Request $request){
        $validated = $request->validate([
            'name' => 'required|string',
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        return $this->term->create($validated);
    }
}
