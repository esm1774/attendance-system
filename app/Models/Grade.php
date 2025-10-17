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
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * العلاقة: الصف له العديد من الفصول
     */
    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * العلاقة: الصف له العديد من الطلاب
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * نطاق الاستعلام للصفوف النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق الاستعلام للصفوف مرتبة
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
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
}