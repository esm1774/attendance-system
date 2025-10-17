<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
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
        'group',
        'description',
    ];

    /**
     * العلاقة: الصلاحية تابعة للعديد من الأدوار
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
                    ->withTimestamps();
    }

    /**
     * العلاقة: الصلاحية لها العديد من الصلاحيات الخاصة بالمستخدمين
     */
    public function userPermissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }

    /**
     * الحصول على الاسم باللغة العربية
     */
    public function getArabicName(): string
    {
        return $this->name_ar;
    }

    /**
     * نطاق الاستعلام لصلاحيات مجموعة معينة
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * نطاق الاستعلام للصلاحيات النشطة
     */
    public function scopeActive($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('is_active', true);
        });
    }
}