<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_ar',
        'code',
        'description',
        'type',
        'is_active',
    ];

    /**
     * الحقول التي يجب أن تكون من نوع معين
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة: المادة لها العديد من توزيعات الدرجات
     */
    public function grades(): HasMany
    {
        return $this->hasMany(SubjectGrade::class);
    }

    /**
     * العلاقة: المادة تدرس في العديد من الفصول
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subjects')
                    ->withTimestamps();
    }

    /**
     * العلاقة: المادة يدرسها العديد من المعلمين
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teacher_subjects')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    /**
     * العلاقة: المادة لها العديد من سجلات ربط المعلمين
     */
    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class);
    }

    /**
     * نطاق الاستعلام للمواد النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق الاستعلام للمواد الإجبارية
     */
    public function scopeMandatory($query)
    {
        return $query->where('type', 'mandatory');
    }

    /**
     * نطاق الاستعلام للمواد الاختيارية
     */
    public function scopeElective($query)
    {
        return $query->where('type', 'elective');
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
     * الحصول على الاسم حسب اللغة
     */
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name;
    }

    /**
     * التحقق إذا كانت المادة إجبارية
     */
    public function isMandatory(): bool
    {
        return $this->type === 'mandatory';
    }

    /**
     * التحقق إذا كانت المادة اختيارية
     */
    public function isElective(): bool
    {
        return $this->type === 'elective';
    }

    /**
     * الحصول على حالة المادة كنص
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'نشط' : 'معطل';
    }

    /**
     * الحصول على نوع المادة كنص
     */
    public function getTypeTextAttribute(): string
    {
        return $this->isMandatory() ? 'إجباري' : 'اختياري';
    }

    /**
     * الحصول على عدد المعلمين الذين يدرسون هذه المادة
     */
    public function getTeachersCountAttribute(): int
    {
        return $this->teachers()->count();
    }

    /**
     * الحصول على عدد الفصول التي تدرس هذه المادة
     */
    public function getClassesCountAttribute(): int
    {
        return $this->classes()->count();
    }

    /**
     * الحصول على المعلمين الرئيسيين للمادة
     */
    public function getPrimaryTeachersAttribute()
    {
        return $this->teachers()->wherePivot('is_primary', true)->get();
    }

    /**
     * الحصول على المعلمين المساعدين للمادة
     */
    public function getAssistantTeachersAttribute()
    {
        return $this->teachers()->wherePivot('is_primary', false)->get();
    }

    /**
     * التحقق إذا كانت المادة مرتبطة بمعلمين
     */
    public function hasTeachers(): bool
    {
        return $this->teachers_count > 0;
    }

    /**
     * التحقق إذا كانت المادة مرتبطة بفصول
     */
    public function hasClasses(): bool
    {
        return $this->classes_count > 0;
    }

    /**
     * إضافة معلم للمادة
     */
    public function addTeacher($teacherId, $isPrimary = false)
    {
        return $this->teachers()->attach($teacherId, ['is_primary' => $isPrimary]);
    }

    /**
     * إزالة معلم من المادة
     */
    public function removeTeacher($teacherId)
    {
        return $this->teachers()->detach($teacherId);
    }

    /**
     * تحديث حالة المعلم في المادة
     */
    public function updateTeacherStatus($teacherId, $isPrimary)
    {
        return $this->teachers()->updateExistingPivot($teacherId, ['is_primary' => $isPrimary]);
    }
}