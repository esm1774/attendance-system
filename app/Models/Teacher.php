<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'national_id',
        'birth_date',
        'gender',
        'nationality',
        'photo',
        'phone',
        'email',
        'address',
        'employee_number',
        'specialization',
        'qualification',
        'hire_date',
        'contract_type',
        'salary',
        'department',
        'school_id',
        'status',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ==================== العلاقات ====================
    
    /**
     * العلاقة مع المدرسة
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * العلاقة مع المواد (Many to Many)
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')
            ->withTimestamps();
    }

    /**
     * العلاقة مع الفصول (Many to Many) - من خلال جدول teacher_school_class
     */
    public function schoolClasses()
    {
        return $this->belongsToMany(SchoolClass::class, 'teacher_school_class', 'teacher_id', 'school_class_id')
            ->withPivot('subject_id', 'is_class_teacher')
            ->withTimestamps();
    }

    /**
     * الفصول التي يكون فيها رائد فصل
     */
    public function classTeacherOf()
    {
        return $this->belongsToMany(SchoolClass::class, 'teacher_school_class', 'teacher_id', 'school_class_id')
            ->wherePivot('is_class_teacher', true)
            ->withPivot('subject_id')
            ->withTimestamps();
    }
    
    /**
     * الفصول التي يكون فيها المعلم هو المعلم الرئيسي (من جدول school_classes)
     */
    public function mainClasses()
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }

    // ==================== Accessors ====================
    
    /**
     * الحصول على العمر
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }

    /**
     * الحصول على عدد سنوات الخدمة
     */
    public function getYearsOfServiceAttribute()
    {
        return Carbon::parse($this->hire_date)->diffInYears(now());
    }

    /**
     * الحصول على نص الجنس
     */
    public function getGenderTextAttribute()
    {
        return $this->gender === 'male' ? 'ذكر' : 'أنثى';
    }

    /**
     * الحصول على نص نوع العقد
     */
    public function getContractTypeTextAttribute()
    {
        return match($this->contract_type) {
            'permanent' => 'دائم',
            'temporary' => 'مؤقت',
            'substitute' => 'بديل',
            default => 'غير محدد',
        };
    }

    /**
     * الحصول على نص الحالة
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'active' => 'نشط',
            'on_leave' => 'في إجازة',
            'retired' => 'متقاعد',
            'transferred' => 'منقول',
            default => 'غير محدد',
        };
    }

    /**
     * الحصول على لون الحالة
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'on_leave' => 'warning',
            'retired' => 'secondary',
            'transferred' => 'info',
            default => 'secondary',
        };
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        
        // صورة افتراضية حسب الجنس
        return $this->gender === 'male' 
            ? asset('images/default-teacher-male.png')
            : asset('images/default-teacher-female.png');
    }

    // ==================== Scopes ====================
    
    /**
     * المعلمين النشطين فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    /**
     * البحث في المعلمين
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('national_id', 'like', "%{$search}%")
              ->orWhere('employee_number', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * فلترة حسب المدرسة
     */
    public function scopeBySchool($query, $schoolId)
    {
        if ($schoolId) {
            return $query->where('school_id', $schoolId);
        }
        return $query;
    }

    /**
     * فلترة حسب التخصص
     */
    public function scopeBySpecialization($query, $specialization)
    {
        if ($specialization) {
            return $query->where('specialization', $specialization);
        }
        return $query;
    }

    /**
     * فلترة حسب الجنس
     */
    public function scopeByGender($query, $gender)
    {
        if ($gender) {
            return $query->where('gender', $gender);
        }
        return $query;
    }

    /**
     * فلترة حسب الحالة
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * فلترة حسب نوع العقد
     */
    public function scopeByContractType($query, $contractType)
    {
        if ($contractType) {
            return $query->where('contract_type', $contractType);
        }
        return $query;
    }
}