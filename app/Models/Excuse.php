<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Excuse extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'attendance_id',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    /**
     * الحقول التي يجب أن تكون من نوع معين
     *
     * @var array
     */
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * العلاقة: العذر تابع لطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * العلاقة: العذر تابع لسجل حضور
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * العلاقة: العذر تمت الموافقة عليه من قبل مستخدم
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * التحقق إذا كان العذر معلق
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * التحقق إذا كان العذر مقبول
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * التحقق إذا كان العذر مرفوض
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * الحصول على حالة العذر كنص
     */
    public function getStatusTextAttribute(): string
    {
        $statuses = [
            'pending' => 'معلق',
            'approved' => 'مقبول',
            'rejected' => 'مرفوض'
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
