<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'is_active',
        'teacher_id',
        'specialization',
        'qualification',
        'years_of_experience',
        'hire_date',
        'employment_type',
        'salary',
        'notes'
    ];

    /**
     * الحقول التي يجب إخفاؤها
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * الحقول التي يجب أن تكون من نوع معين
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'years_of_experience' => 'integer',
            'hire_date' => 'date',
            'salary' => 'decimal:2'
        ];
    }

    /**
     * =============================================
     * العلاقات الأساسية
     * =============================================
     */

    /**
     * العلاقة: المستخدم تابع لدور
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * العلاقة: المستخدم له العديد من الصلاحيات الخاصة
     */
    public function userPermissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }

    /**
     * العلاقة: المستخدم له العديد من الصلاحيات من خلال دورهم
     */
    public function rolePermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
                    ->wherePivot('role_id', $this->role_id);
    }

    /**
     * =============================================
     * العلاقات الأكاديمية
     * =============================================
     */

    /**
     * العلاقة: المستخدم (المعلم) له العديد من الفصول كمعلم رئيسي
     */
    public function teachingClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }

    /**
     * العلاقة: المستخدم (المراقب) يشرف على العديد من الفصول
     */
    public function supervisedClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'supervisor_id');
    }

    /**
     * العلاقة: المستخدم (المعلم) يدرس العديد من المواد
     */
    public function teachingSubjects(): HasMany
    {
        return $this->hasMany(TeacherSubject::class, 'user_id');
    }

    /**
     * العلاقة: المستخدم (المعلم) له العديد من المواد من خلال الربط
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    /**
     * العلاقة: المستخدم يسجل العديد من الحضور
     */
    public function recordedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'recorded_by');
    }

    /**
     * العلاقة: المستخدم يوافق على العديد من الأعذار
     */
    public function approvedExcuses(): HasMany
    {
        return $this->hasMany(Excuse::class, 'approved_by');
    }

    /**
     * العلاقة: المستخدم يسجل العديد من الدرجات
     */
    public function gradedRecords(): HasMany
    {
        return $this->hasMany(StudentGrade::class, 'graded_by');
    }

    /**
     * =============================================
     * نظام الصلاحيات
     * =============================================
     */

    /**
     * الحصول على جميع الصلاحيات الخاصة بالمستخدم (من الدور + الصلاحيات الخاصة)
     */
    public function getAllPermissions()
    {
        // الصلاحيات من الدور
        $rolePermissions = $this->role ? $this->role->permissions : collect();
        
        // الصلاحيات الخاصة (الفعالة فقط)
        $userPermissions = $this->userPermissions()
            ->with('permission')
            ->where('is_granted', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get()
            ->pluck('permission');

        // دمج الصلاحيات وإزالة التكرار
        return $rolePermissions->merge($userPermissions)->unique('id');
    }

    /**
     * التحقق إذا كان المستخدم لديه صلاحية معينة
     */
    public function hasPermission($permissionName): bool
    {
        return $this->getAllPermissions()->contains('name', $permissionName);
    }

    /**
     * التحقق إذا كان المستخدم لديه أي من الصلاحيات المحددة
     */
    public function hasAnyPermission(array $permissionNames): bool
    {
        return $this->getAllPermissions()->whereIn('name', $permissionNames)->isNotEmpty();
    }

    /**
     * التحقق إذا كان المستخدم لديه جميع الصلاحيات المحددة
     */
    public function hasAllPermissions(array $permissionNames): bool
    {
        $userPermissions = $this->getAllPermissions()->pluck('name')->toArray();
        
        return empty(array_diff($permissionNames, $userPermissions));
    }

    /**
     * =============================================
     * التحقق من الأدوار
     * =============================================
     */

    /**
     * التحقق إذا كان المستخدم نشط
     */
    public function isActive(): bool
    {
        return $this->is_active && ($this->role ? $this->role->is_active : true);
    }

    /**
     * التحقق إذا كان المستخدم مدير نظام
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * التحقق إذا كان المستخدم وكيل
     */
    public function isVicePrincipal(): bool
    {
        return $this->role && $this->role->name === 'vice_principal';
    }

    /**
     * التحقق إذا كان المستخدم مراقب
     */
    public function isSupervisor(): bool
    {
        return $this->role && $this->role->name === 'supervisor';
    }

    /**
     * التحقق إذا كان المستخدم معلم
     */
    public function isTeacher(): bool
    {
        return $this->role && $this->role->name === 'teacher';
    }

    /**
     * التحقق إذا كان المستخدم ولي أمر
     */
    public function isParent(): bool
    {
        return $this->role && $this->role->name === 'parent';
    }

    /**
     * =============================================
     * الدوال المساعدة
     * =============================================
     */

    /**
     * الحصول على المواد التي يدرسها المعلم
     */
    public function getTeacherSubjectsAttribute()
    {
        if (!$this->isTeacher()) {
            return collect();
        }
        return $this->teachingSubjects()->with('subject')->get();
    }

    /**
     * الحصول على الفصول التي يديرها المعلم
     */
    public function getManagedClassesAttribute()
    {
        if (!$this->isTeacher()) {
            return collect();
        }
        return $this->teachingClasses()->with('grade', 'students')->get();
    }

    /**
     * الحصول على الفصول التي يشرف عليها المستخدم (للمراقبين)
     */
    public function getSupervisedClassesAttribute()
    {
        if (!$this->isSupervisor()) {
            return collect();
        }
        return $this->supervisedClasses()->with('grade', 'students')->get();
    }

    /**
     * الحصول على جميع الفصول المرتبطة بالمستخدم (معلم رئيسي + مشرف)
     */
    public function getAllClassesAttribute()
    {
        $teachingClasses = $this->teachingClasses;
        $supervisedClasses = $this->supervisedClasses;
        
        return $teachingClasses->merge($supervisedClasses)->unique('id');
    }

    /**
     * الحصول على الاسم مع التخصص (للمعلمين)
     */
    public function getProfessionalNameAttribute()
    {
        if ($this->isTeacher() && $this->specialization) {
            return $this->name . ' - ' . $this->specialization;
        }
        return $this->name;
    }

    /**
     * الحصول على مدة الخدمة
     */
    public function getServiceDurationAttribute()
    {
        if (!$this->hire_date) {
            return 'غير محدد';
        }

        $years = now()->diffInYears($this->hire_date);
        $months = now()->diffInMonths($this->hire_date) % 12;
        
        if ($years > 0 && $months > 0) {
            return $years . ' سنة و ' . $months . ' شهر';
        } elseif ($years > 0) {
            return $years . ' سنة';
        } else {
            return $months . ' شهر';
        }
    }

    /**
     * =============================================
     * نطاقات الاستعلام
     * =============================================
     */

    /**
     * نطاق الاستعلام للمستخدمين النشطين فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->whereHas('role', function ($q) {
                        $q->where('is_active', true);
                    });
    }

    /**
     * نطاق الاستعلام للمستخدمين بدور معين
     */
    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('role', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    /**
     * نطاق الاستعلام للمعلمين فقط
     */
    public function scopeTeachers($query)
    {
        return $query->byRole('teacher');
    }

    /**
     * نطاق الاستعلام للمديرين فقط
     */
    public function scopeAdmins($query)
    {
        return $query->byRole('admin');
    }

    /**
     * نطاق الاستعلام للمراقبين فقط
     */
    public function scopeSupervisors($query)
    {
        return $query->byRole('supervisor');
    }

    /**
     * نطاق الاستعلام بالبحث في الاسم أو البريد الإلكتروني
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('teacher_id', 'like', "%{$search}%")
              ->orWhere('specialization', 'like', "%{$search}%")
              ->orWhere('qualification', 'like', "%{$search}%");
        });
    }

    /**
     * نطاق الاستعلام حسب التخصص
     */
    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    /**
     * نطاق الاستعلام حسب نوع التوظيف
     */
    public function scopeByEmploymentType($query, $employmentType)
    {
        return $query->where('employment_type', $employmentType);
    }

    /**
     * =============================================
     * الأحداث
     * =============================================
     */

    /**
     * الإعداد الأولي للنموذج
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // تعيين معرف المعلم إذا كان معلم
            if ($user->isTeacher() && empty($user->teacher_id)) {
                $user->teacher_id = 'TCH-' . str_pad(static::teachers()->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}