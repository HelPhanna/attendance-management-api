<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevelSubject extends Model
{
    use HasFactory;
    protected $fillable = [
        "grade_level_id",
        "subject_id",
    ];
}
