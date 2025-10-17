<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /**
     * العلاقة: الربط تابع لدور
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * العلاقة: الربع تابع لصلاحية
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * التحقق إذا كان الربط نشط
     */
    public function isActive(): bool
    {
        return $this->role->is_active && $this->permission !== null;
    }
}