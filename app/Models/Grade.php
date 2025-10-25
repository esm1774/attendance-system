<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'stage_id',
        'name',
        'name_ar',
        'code',
        'description',
        'order',
        'level',
        'min_grade',
        'max_grade',
        'is_active'
    ];

    /**
     * الحقول التي يجب أن تكون من نوع معين
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'level' => 'integer',
        'min_grade' => 'decimal:2',
        'max_grade' => 'decimal:2'
    ];

    /**
     * العلاقة: الصف تابع لمرحلة
     */
    // public function stage(): BelongsTo
    // {
    //     return $this->belongsTo(Stage::class);
    // }

    /**
     * العلاقة: الصف له العديد من الفصول
     */
    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
 * العلاقة مع المواد
 */
// public function subjects()
// {
//     return $this->belongsToMany(Subject::class, 'grade_subject')
//         ->withPivot('is_required')
//         ->withTimestamps();
// }

    /**
     * العلاقة: الصف له العديد من الطلاب
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
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
     * الحصول على حالة الصف كنص
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'نشط' : 'معطل';
    }

    /**
     * الحصول على عدد الفصول
     */
    public function getClassesCountAttribute(): int
    {
        return $this->classes()->count();
    }

    /**
     * الحصول على عدد الطلاب
     */
    public function getStudentsCountAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * الحصول على نطاق الدرجات
     */
    public function getGradeRangeAttribute(): string
    {
        return $this->min_grade . ' - ' . $this->max_grade;
    }

    /**
     * التحقق إذا كان الصف يحتوي على فصول
     */
    public function hasClasses(): bool
    {
        return $this->classes_count > 0;
    }

    /**
     * التحقق إذا كان الصف يحتوي على طلاب
     */
    public function hasStudents(): bool
    {
        return $this->students_count > 0;
    }

    /**
     * الحصول على الفصول المرتبة
     */
    public function getOrderedClassesAttribute()
    {
        return $this->classes()->orderBy('name')->get();
    }

    /**
     * الحصول على المسار الكامل (المرحلة + الصف)
     */
    public function getFullPathAttribute(): string
    {
        return $this->stage->name . ' - ' . $this->name;
    }

    /**
     * الحصول على المسار الكامل بالعربية
     */
    public function getFullPathArAttribute(): string
    {
        return $this->stage->name_ar . ' - ' . $this->name_ar;
    }


 


    // ==================== العلاقات ====================
    
    /**
     * العلاقة مع المرحلة
     */
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * العلاقة مع الفصول
     */
    public function schoolClasses()
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * العلاقة مع المواد (Many to Many)
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'grade_subject')
            ->withPivot('is_required', 'weekly_hours')
            ->withTimestamps();
    }

    /**
     * الحصول على المواد الإجبارية فقط
     */
    public function requiredSubjects()
    {
        return $this->belongsToMany(Subject::class, 'grade_subject')
            ->wherePivot('is_required', true)
            ->withPivot('weekly_hours')
            ->withTimestamps();
    }

    /**
     * الحصول على المواد الاختيارية فقط
     */
    public function optionalSubjects()
    {
        return $this->belongsToMany(Subject::class, 'grade_subject')
            ->wherePivot('is_required', false)
            ->withPivot('weekly_hours')
            ->withTimestamps();
    }

    // ==================== Scopes ====================
    
    /**
     * الصفوف النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * فلترة حسب المرحلة
     */
    public function scopeByStage($query, $stageId)
    {
        if ($stageId) {
            return $query->where('stage_id', $stageId);
        }
        return $query;
    }

    /**
     * ترتيب حسب order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}