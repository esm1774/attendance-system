<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
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
        'address',
        'phone',
        'email',
        'principal_name',
        'principal_name_ar',
        'established_year',
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
        'established_year' => 'integer'
    ];

    /**
     * العلاقة: المدرسة لها العديد من المراحل
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    /**
     * العلاقة: المدرسة لها العديد من الصفوف
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * العلاقة: المدرسة لها العديد من الفصول
     */
    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * العلاقة: المدرسة لها العديد من الطلاب
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * العلاقة: المدرسة لها العديد من المعلمين
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(User::class)->whereHas('role', function ($query) {
            $query->where('name', 'teacher');
        });
    }

    /**
     * نطاق الاستعلام للمدارس النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق الاستعلام للبحث بالاسم أو الرمز
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('name_ar', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('principal_name', 'like', "%{$search}%")
              ->orWhere('principal_name_ar', 'like', "%{$search}%");
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
     * الحصول على اسم المدير حسب اللغة
     */
    public function getPrincipalNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->principal_name_ar : $this->principal_name;
    }

    /**
     * الحصول على حالة المدرسة كنص
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'نشط' : 'معطل';
    }

    /**
     * الحصول على عدد المراحل
     */
    public function getStagesCountAttribute(): int
    {
        return $this->stages()->count();
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
     * الحصول على عدد المعلمين
     */
    public function getTeachersCountAttribute(): int
    {
        return $this->teachers()->count();
    }

    /**
     * التحقق إذا كانت المدرسة تحتوي على مراحل
     */
    public function hasStages(): bool
    {
        return $this->stages_count > 0;
    }

    /**
     * التحقق إذا كانت المدرسة تحتوي على طلاب
     */
    public function hasStudents(): bool
    {
        return $this->students_count > 0;
    }

    /**
     * الحصول على العمر المؤسسي للمدرسة
     */
    public function getSchoolAgeAttribute(): int
    {
        if (!$this->established_year) {
            return 0;
        }
        return now()->year - $this->established_year;
    }
}