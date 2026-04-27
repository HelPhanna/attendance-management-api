<?php

namespace App\Actions\AcademicYear;

use App\Models\AcademicYear;
use Illuminate\Validation\ValidationException;

class DeleteAcademicYear
{
    /**
     * Create a new class instance.
     */
    protected $academicYear;
    public function __construct(AcademicYear $academicYear)
    {
        $this->academicYear = $academicYear;
    }

    public function delete(AcademicYear $academicYear)
    {
        $record = $academicYear;
        $academicYear->delete();
        return $record;
    }

    public function multiDelete(array $ids)
    {
        if (empty($ids)) {
            throw ValidationException::withMessages([
                'ids' => ['No IDs provided']
            ]);
        }

        $records = AcademicYear::whereIn('id', $ids)->get();

        if ($records->isEmpty()) {
            throw ValidationException::withMessages([
                'ids' => ['No matching records found for the given IDs']
            ]);
        }

        AcademicYear::whereIn('id', $ids)->delete();

        return $records;
    }

    public function deleteAll(){
        $academicYear = AcademicYear::all();

        if($academicYear->isEmpty()){
            throw new \Exception("All record not found");
        };

        AcademicYear::query()->delete();
        return $academicYear;
    }
}
