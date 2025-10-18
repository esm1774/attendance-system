<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'class_id',
        'student_id',
        'full_name',
        'national_id',
        'birth_date',
        'gender',
        'nationality',
        'phone',
        'email',
        'guardian_name',
        'guardian_relation',
        'guardian_phone',
        'guardian_email',
        'enrollment_date',
        'is_active',
        'status',
        'notes'
    ];

    /**
     * الحقول التي يجب أن تكون من نوع معين
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'birth_date' => 'date',
        'enrollment_date' => 'date'
    ];

    /**
     * العلاقة: الطالب تابع لفصل
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * العلاقة: الطالب له العديد من سجلات الحضور
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * العلاقة: الطالب له العديد من الدرجات
     */
    public function grades(): HasMany
    {
        return $this->hasMany(StudentGrade::class);
    }

    /**
     * العلاقة: الطالب له العديد من الأعذار
     */
    public function excuses(): HasMany
    {
        return $this->hasMany(Excuse::class);
    }

    /**
     * نطاق الاستعلام للطلاب النشطين
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    /**
     * نطاق الاستعلام للطلاب حسب الفصل
     */
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * نطاق الاستعلام للطلاب حسب الجنس
     */
    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    /**
     * نطاق الاستعلام للطلاب حسب الحالة
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * نطاق الاستعلام للبحث بالاسم أو الرقم
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
              ->orWhere('student_id', 'like', "%{$search}%")
              ->orWhere('national_id', 'like', "%{$search}%")
              ->orWhere('guardian_name', 'like', "%{$search}%")
              ->orWhere('guardian_phone', 'like', "%{$search}%");
        });
    }

    /**
     * الحصول على الاسم الكامل
     */
    public function getFullNameAttribute()
    {
        return $this->attributes['full_name'] ?? '';
    }

    /**
     * الحصول على العمر
     */
    public function getAgeAttribute()
    {
        return $this->birth_date->age;
    }

    /**
     * الحصول على حالة الطالب كنص
     */
    public function getStatusTextAttribute(): string
    {
        $statuses = [
            'active' => 'نشط',
            'transferred' => 'منقول',
            'graduated' => 'متخرج',
            'withdrawn' => 'منسحب'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * الحصول على الجنس كنص
     */
    public function getGenderTextAttribute(): string
    {
        return $this->gender === 'male' ? 'ذكر' : 'أنثى';
    }

    /**
     * الحصول على مدة الدراسة
     */
    public function getStudyDurationAttribute(): string
    {
        $years = now()->diffInYears($this->enrollment_date);
        $months = now()->diffInMonths($this->enrollment_date) % 12;
        
        if ($years > 0 && $months > 0) {
            return $years . ' سنة و ' . $months . ' شهر';
        } elseif ($years > 0) {
            return $years . ' سنة';
        } else {
            return $months . ' شهر';
        }
    }

    /**
     * التحقق إذا كان الطالب نشط
     */
    public function isActive(): bool
    {
        return $this->is_active && $this->status === 'active';
    }

    /**
     * التحقق إذا كان الطالب متخرج
     */
    public function isGraduated(): bool
    {
        return $this->status === 'graduated';
    }

    /**
     * التحقق إذا كان الطالب منقول
     */
    public function isTransferred(): bool
    {
        return $this->status === 'transferred';
    }

    /**
     * الحصول على معلومات الاتصال بولي الأمر
     */
    public function getGuardianInfoAttribute(): array
    {
        return [
            'name' => $this->guardian_name,
            'relation' => $this->guardian_relation,
            'phone' => $this->guardian_phone,
            'email' => $this->guardian_email
        ];
    }

    /**
     * تحديث حالة الطالب
     */
    public function updateStatus($status, $notes = null)
    {
        $this->update([
            'status' => $status,
            'notes' => $notes ?: $this->notes
        ]);
    }
}