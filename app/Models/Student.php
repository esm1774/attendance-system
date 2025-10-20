<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';

    protected $fillable = [
        'class_id',
        'full_name',
        'national_id',
        'birth_date',
        'gender',
        'nationality',
        'religion',
        'phone',
        'email',
        'guardian_name',
        'guardian_relation',
        'guardian_phone',
        'guardian_email',
        'emergency_phone',
        'blood_type',
        'allergies',
        'medical_notes',
        'enrollment_date',
        'enrollment_type',
        'status',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * العلاقات
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Accessors
     */
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    public function getGenderTextAttribute()
    {
        return $this->gender === 'male' ? 'ذكر' : 'أنثى';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'active' => 'نشط',
            'transferred' => 'منقول',
            'graduated' => 'متخرج',
            'withdrawn' => 'منسحب',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'active' => 'success',
            'transferred' => 'info',
            'graduated' => 'primary',
            'withdrawn' => 'danger',
        ];
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Scopes
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('national_id', 'like', "%{$term}%")
              ->orWhere('full_name', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }
}