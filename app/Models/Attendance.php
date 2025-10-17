<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'class_id',
        'attendance_date',
        'status',
        'arrival_time',
        'departure_time',
        'notes',
        'recorded_by'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'arrival_time' => 'datetime',
        'departure_time' => 'datetime'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function excuse()
    {
        return $this->hasOne(Excuse::class, 'attendance_id');
    }
}