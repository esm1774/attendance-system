<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'permission_id',
        'is_granted',
        'expires_at',
    ];

    /**
     * الحقول التي يجب أن تكون من نوع التاريخ
     *
     * @var array
     */
    protected $dates = [
        'expires_at',
        'created_at',
        'updated_at',
    ];

    /**
     * العلاقة: الصلاحية الخاصة تابع لمستخدم
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة: الصلاحية الخاصة تابع لصلاحية
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * التحقق إذا كانت الصلاحية منحت
     */
    public function isGranted(): bool
    {
        return $this->is_granted;
    }

    /**
     * التحقق إذا كانت الصلاحية منتهية الصلاحية
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * التحقق إذا كانت الصلاحية فعالة
     */
    public function isActive(): bool
    {
        return $this->isGranted() && !$this->isExpired();
    }

    /**
     * نطاق الاستعلام للصلاحيات الفعالة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_granted', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }
}