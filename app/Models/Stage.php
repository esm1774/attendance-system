<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'name',
        'name_ar',
        'code',
        'description',
        'order',
        'min_age',
        'max_age',
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
        'min_age' => 'integer',
        'max_age' => 'integer'
    ];

    /**
     * العلاقة: المرحلة تابعة لمدرسة
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * العلاقة: المرحلة لها العديد من الصفوف
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * العلاقة: المرحلة لها العديد من الفصول
     */
    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * العلاقة: المرحلة لها العديد من الطلاب
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * نطاق الاستعلام للمراحل النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق الاستعلام للمراحل مرتبة
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
     * الحصول على حالة المرحلة كنص
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'نشط' : 'معطل';
    }

    /**
     * الحصول على عدد الصفوف
     */
    public function getGradesCountAttribute(): int
    {
        return $this->grades()->count();
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
     * الحصول على نطاق العمر كنص
     */
    public function getAgeRangeAttribute(): string
    {
        if ($this->min_age && $this->max_age) {
            return $this->min_age . ' - ' . $this->max_age . ' سنة';
        } elseif ($this->min_age) {
            return 'من ' . $this->min_age . ' سنة';
        } elseif ($this->max_age) {
            return 'حتى ' . $this->max_age . ' سنة';
        }
        return 'غير محدد';
    }

    /**
     * التحقق إذا كانت المرحلة تحتوي على صفوف
     */
    public function hasGrades(): bool
    {
        return $this->grades_count > 0;
    }

    /**
     * التحقق إذا كانت المرحلة تحتوي على طلاب
     */
    public function hasStudents(): bool
    {
        return $this->students_count > 0;
    }

    /**
     * الحصول على الصفوف المرتبة
     */
    public function getOrderedGradesAttribute()
    {
        return $this->grades()->orderBy('order')->get();
    }
}