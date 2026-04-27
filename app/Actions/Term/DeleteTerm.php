<?php

namespace App\Actions\Term;

use App\Models\Term;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class DeleteTerm
{
    /**
     * Create a new class instance.
     */
    protected $term;
    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    public function delete(Term $term) {

        return $this->term->delete($term);
    }

    public function multiDelete(array $ids)
    {

    if (empty($ids)) {
        throw ValidationException::withMessages([
            'ids' => ['No IDs provided']
        ]);
    }

    $terms = Term::whereIn('id', $ids)->get();

    if (empty($term)) {
        throw ValidationException::withMessages([
            'ids' => ['No matching records found for the given IDs']
        ]);
    }


    Term::whereIn('id', $ids)->delete();

    return $terms;
}
    public function deleteAll() {

        $term = Term::all();

        if($term->isEmpty()){
            throw new \Exception("All record not found");
        };
        Term::query()->delete();
        return $term;
    }
}
