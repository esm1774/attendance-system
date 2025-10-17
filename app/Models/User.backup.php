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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
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
     * العلاقة: المستخدم (المعلم) له العديد من الفصول كمعلم رئيسي
     */
    public function teachingClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
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
     * نطاق الاستعلام بالبحث في الاسم أو البريد الإلكتروني
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('teacher_id', 'like', "%{$search}%");
        });
    }
}