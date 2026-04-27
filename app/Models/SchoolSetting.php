<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'address',
        'city',
        'state_province',
        'postal_code',
        'country',
        'current_academic_year',
        'term_semester',
    ];
}
