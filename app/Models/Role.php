<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
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
        'description',
        'is_active',
    ];

    /**
     * العلاقة: الدور له العديد من الصلاحيات
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
                    ->withTimestamps();
    }

    /**
     * العلاقة: الدور له العديد من المستخدمين
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * التحقق إذا كان الدور نشط
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * الحصول على الاسم باللغة العربية
     */
    public function getArabicName(): string
    {
        return $this->name_ar;
    }

    /**
     * نطاق الاستعلام للأدوار النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}