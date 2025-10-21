<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\SchoolClass;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Log;

class StudentsImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation,
    SkipsOnError
{
    use SkipsErrors;

    /**
     * تحويل كل صف إلى نموذج Student
     */
    public function model(array $row)
    {
        // البحث عن الفصل
        $class = SchoolClass::where('name_ar', $row['asm_alfsl'])
            ->orWhere('name', $row['asm_alfsl'])
            ->first();

        if (!$class) {
            Log::warning('Class not found: ' . $row['asm_alfsl']);
            return null;
        }

        // التحقق من عدم وجود رقم هوية مكرر
        if (!empty($row['alhoy_alotny']) && Student::where('national_id', $row['alhoy_alotny'])->exists()) {
            Log::warning('Duplicate national_id: ' . $row['alhoy_alotny']);
            return null;
        }

        // تحويل الجنس
        $gender = null;
        if (!empty($row['algns'])) {
            $genderText = trim($row['algns']);
            if (in_array($genderText, ['ذكر', 'male', 'Male', 'MALE'])) {
                $gender = 'male';
            } elseif (in_array($genderText, ['أنثى', 'انثى', 'female', 'Female', 'FEMALE'])) {
                $gender = 'female';
            }
        }

        // تحويل الحالة
        $status = 'active';
        if (!empty($row['alhal'])) {
            $statusText = trim($row['alhal']);
            if (in_array($statusText, ['نشط', 'active'])) {
                $status = 'active';
            } elseif (in_array($statusText, ['منقول', 'transferred'])) {
                $status = 'transferred';
            } elseif (in_array($statusText, ['متخرج', 'graduated'])) {
                $status = 'graduated';
            } elseif (in_array($statusText, ['منسحب', 'withdrawn'])) {
                $status = 'withdrawn';
            }
        }

        return new Student([
            'class_id' => $class->id,
            'full_name' => $row['asm_altalb'],
            'national_id' => $row['alhoy_alotny'] ?? null,
            'birth_date' => !empty($row['tarykh_almylad']) ? $row['tarykh_almylad'] : null,
            'gender' => $gender,
            'nationality' => $row['algnsy'] ?? 'سعودي',
            'phone' => $row['rqm_algoal'] ?? null,
            'email' => $row['albr الإلكتروني'] ?? null,
            'guardian_name' => $row['asm_oly_alamr'] ?? null,
            'guardian_relation' => $row['sl_oly_alamr_alاب'] ?? 'الأب',
            'guardian_phone' => $row['rqm_goal_oly_alamr'] ?? null,
            'guardian_email' => $row['aymyl_oly_alamr'] ?? null,
            'enrollment_date' => !empty($row['tarykh_altsgyl']) ? $row['tarykh_altsgyl'] : now(),
            'status' => $status,
            'notes' => $row['mlahthat'] ?? null,
            'is_active' => !empty($row['is_active']) ? (int)$row['is_active'] : 1,
        ]);
    }

    /**
     * قواعد التحقق
     */
    public function rules(): array
    {
        return [
            'asm_alfsl' => 'required',
            'asm_altalb' => 'required',
            'alhoy_alotny' => 'nullable|unique:students,national_id',
        ];
    }

    /**
     * رسائل الخطأ
     */
    public function customValidationMessages()
    {
        return [
            'asm_alfsl.required' => 'اسم الفصل مطلوب',
            'asm_altalb.required' => 'اسم الطالب مطلوب',
            'alhoy_alotny.unique' => 'الهوية الوطنية موجودة مسبقاً',
        ];
    }

    /**
     * رقم صف العناوين
     */
    public function headingRow(): int
    {
        return 1;
    }
}