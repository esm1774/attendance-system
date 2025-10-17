<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'subject_id',
        'weekly_hours',
        'academic_year',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'weekly_hours' => 'integer',
        'academic_year' => 'integer'
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}