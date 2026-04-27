<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];


    // relationship

    public function term(): HasMany
    {
        return $this->hasMany(Term::class, 'academic_year_id');
    }
}
