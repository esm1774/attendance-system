<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSubject extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'subject_id',
        'class_id',
        'is_primary',
        'academic_year',
        'weekly_hours',
        'is_active',
        'notes'
    ];

    /**
     * الحقول التي يجب أن تكون من نوع معين
     *
     * @var array
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'academic_year' => 'integer',
        'weekly_hours' => 'integer'
    ];

    /**
     * العلاقة: الربط تابع لمعلم
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * العلاقة: الربط تابع لمادة
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * العلاقة: الربط تابع لفصل
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * نطاق الاستعلام للمعلمين الرئيسيين
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * نطاق الاستعلام للمعلمين المساعدين
     */
    public function scopeAssistant($query)
    {
        return $query->where('is_primary', false);
    }

    /**
     * نطاق الاستعلام حسب المعلم
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('user_id', $teacherId);
    }

    /**
     * نطاق الاستعلام حسب المادة
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * نطاق الاستعلام حسب الفصل
     */
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * نطاق الاستعلام للسنة الدراسية
     */
    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * نطاق الاستعلام للنشطين فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * الحصول على حالة المعلم كنص
     */
    public function getTeacherStatusAttribute(): string
    {
        return $this->is_primary ? 'معلم رئيسي' : 'معلم مساعد';
    }

    /**
     * الحصول على معلومات الربط الكاملة
     */
    public function getAssignmentInfoAttribute(): array
    {
        return [
            'teacher' => $this->teacher->name,
            'subject' => $this->subject->name,
            'class' => $this->class ? $this->class->name : 'غير محدد',
            'status' => $this->teacher_status,
            'academic_year' => $this->academic_year,
            'weekly_hours' => $this->weekly_hours,
            'notes' => $this->notes
        ];
    }

    /**
     * التحقق إذا كان المعلم رئيسي للمادة
     */
    public function isPrimary(): bool
    {
        return $this->is_primary;
    }

    /**
     * التحقق إذا كان الربط نشط
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * تحديث حالة المعلم
     */
    public function updateStatus($isPrimary, $notes = null)
    {
        $this->update([
            'is_primary' => $isPrimary,
            'notes' => $notes ?: $this->notes
        ]);
    }

    /**
     * تفعيل/تعطيل الربط
     */
    public function toggleActive()
    {
        $this->update([
            'is_active' => !$this->is_active
        ]);
    }

    /**
     * الحصول على المعلمين الرئيسيين لمادة معينة
     */
    public static function getPrimaryTeachers($subjectId, $classId = null)
    {
        $query = static::with('teacher')
                    ->where('subject_id', $subjectId)
                    ->primary()
                    ->active();

        if ($classId) {
            $query->where('class_id', $classId);
        }

        return $query->get()->pluck('teacher');
    }

    /**
     * الحصول على المواد التي يدرسها معلم معين
     */
    public static function getTeacherSubjects($teacherId, $classId = null)
    {
        $query = static::with('subject')
                    ->where('user_id', $teacherId)
                    ->active();

        if ($classId) {
            $query->where('class_id', $classId);
        }

        return $query->get()->pluck('subject');
    }

    /**
     * الحصول على الفصول التي يدرس فيها معلم مادة معينة
     */
    public static function getTeacherClasses($teacherId, $subjectId)
    {
        return static::with('class')
                    ->where('user_id', $teacherId)
                    ->where('subject_id', $subjectId)
                    ->active()
                    ->get()
                    ->pluck('class');
    }
}