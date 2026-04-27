<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blacklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'term_id',
        'absence_count',
        'flagged_at',
    ];

    protected $casts = [
        'absence_count' => 'integer',
        'flagged_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function term(): BelongsTo
    {

        return $this->belongsTo(Term::class, 'term_id', 'id');
    }
}
