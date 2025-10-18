<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SchoolClass extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'grade_id',
        'name',
        'name_ar',
        'code',
        'capacity',
        'teacher_id',
        'description',
        'is_active'
    ];

    /**
     * الحقول التي يجب أن تكون من نوع معين
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer'
    ];

    /**
     * العلاقة: الفصل تابع لصف
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * العلاقة: الفصل له معلم رئيسي
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * العلاقة: الفصل له العديد من الطلاب
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * العلاقة: الفصل يدرس العديد من المواد
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_subjects')
                    ->withTimestamps();
    }

    /**
     * العلاقة: الفصل له العديد من المعلمين للمواد
     */
    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class, 'class_id');
    }

    /**
     * نطاق الاستعلام للفصول النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق الاستعلام للفصول مرتبة
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }

    /**
     * نطاق الاستعلام للبحث بالاسم أو الرمز
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('name_ar', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        });
    }

    /**
     * نطاق الاستعلام للفصول حسب الصف
     */
    public function scopeByGrade($query, $gradeId)
    {
        return $query->where('grade_id', $gradeId);
    }

    /**
     * نطاق الاستعلام للفصول حسب المعلم
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * الحصول على الاسم حسب اللغة
     */
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name;
    }

    /**
     * الحصول على حالة الفصل كنص
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'نشط' : 'معطل';
    }

    /**
     * الحصول على عدد الطلاب النشطين
     */
    public function getActiveStudentsCountAttribute(): int
    {
        return $this->students()->where('is_active', true)->count();
    }

    /**
     * الحصول على عدد المواد
     */
    public function getSubjectsCountAttribute(): int
    {
        return $this->subjects()->count();
    }

    /**
     * الحصول على نسبة الامتلاء
     */
    public function getOccupancyRateAttribute(): float
    {
        if ($this->capacity == 0) return 0;
        return ($this->active_students_count / $this->capacity) * 100;
    }

    /**
     * الحصول على حالة السعة
     */
    public function getCapacityStatusAttribute(): string
    {
        $rate = $this->occupancy_rate;
        if ($rate >= 90) return 'ممتلئ';
        if ($rate >= 70) return 'شبه ممتلئ';
        if ($rate >= 50) return 'متوسط';
        return 'منخفض';
    }

    /**
     * التحقق إذا كان الفصل ممتلئ
     */
    public function isFull(): bool
    {
        return $this->active_students_count >= $this->capacity;
    }

    /**
     * التحقق إذا كان الفصل يحتوي على طلاب
     */
    public function hasStudents(): bool
    {
        return $this->active_students_count > 0;
    }

    /**
     * التحقق إذا كان الفصل يحتوي على مواد
     */
    public function hasSubjects(): bool
    {
        return $this->subjects_count > 0;
    }

    /**
 * الحصول على المسار الكامل (المرحلة + الصف + الفصل)
 */
public function getFullPathAttribute(): string
{
    return ($this->grade?->stage?->name ?? 'غير محدد') . ' - ' .
           ($this->grade?->name ?? 'غير محدد') . ' - ' .
           ($this->name ?? 'غير محدد');
}

/**
 * الحصول على المسار الكامل بالعربية
 */
public function getFullPathArAttribute(): string
{
    return ($this->grade?->stage?->name_ar ?? 'غير محدد') . ' - ' .
           ($this->grade?->name_ar ?? 'غير محدد') . ' - ' .
           ($this->name_ar ?? 'غير محدد');
}


    /**
     * إضافة مادة للفصل
     */
    public function addSubject($subjectId)
    {
        return $this->subjects()->attach($subjectId);
    }

    /**
     * إزالة مادة من الفصل
     */
    public function removeSubject($subjectId)
    {
        return $this->subjects()->detach($subjectId);
    }

    /**
     * الحصول على المواد المتاحة للإضافة
     */
    public function getAvailableSubjects()
    {
        $currentSubjectIds = $this->subjects()->pluck('subjects.id');
        return Subject::active()->whereNotIn('id', $currentSubjectIds)->get();
    }
    
}