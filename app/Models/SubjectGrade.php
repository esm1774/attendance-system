<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'grade_type',
        'grade_type_ar',
        'min_grade',
        'max_grade',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_grade' => 'decimal:2',
        'max_grade' => 'decimal:2',
        'order' => 'integer'
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function studentGrades(): HasMany
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}