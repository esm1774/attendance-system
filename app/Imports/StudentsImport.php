<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\SchoolClass;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \App\Models\Student|null
     */
    public function model(array $row)
    {
        // البحث عن الفصل الدراسي
        $class = null;
        if (!empty($row['class_name'])) {
            $class = SchoolClass::where('name_ar', 'like', '%' . $row['class_name'] . '%')
                ->orWhere('name', 'like', '%' . $row['class_name'] . '%')
                ->first();
        }

        // إذا لم يتم العثور على الفصل، استخدام الفصل الأول
        if (!$class) {
            $class = SchoolClass::first();
        }

        // إنشاء رقم جامعي تلقائي إذا لم يتم تقديمه
        $studentId = !empty($row['student_id']) ? $row['student_id'] : 'STU-' . str_pad(Student::count() + 1, 5, '0', STR_PAD_LEFT);

        return new Student([
            'class_id' => $class ? $class->id : null,
            'student_id' => $studentId,
            'full_name' => $row['full_name'],
            'identity_number' => $row['identity_number'] ?? null,
            'birth_date' => !empty($row['birth_date']) ? date('Y-m-d', strtotime($row['birth_date'])) : null,
            'gender' => $row['gender'] ?? null,
            'nationality' => $row['nationality'] ?? null,
            'phone' => $row['phone'] ?? null,
            'email' => $row['email'] ?? null,
            'guardian_name' => $row['guardian_name'] ?? null,
            'guardian_relation' => $row['guardian_relation'] ?? null,
            'guardian_phone' => $row['guardian_phone'] ?? null,
            'guardian_email' => $row['guardian_email'] ?? null,
            'enrollment_date' => !empty($row['enrollment_date']) ? date('Y-m-d', strtotime($row['enrollment_date'])) : now(),
            'is_active' => true,
            'status' => 'active',
            'notes' => $row['notes'] ?? null,
        ]);
    }

    /**
     * قواعد التحقق من البيانات
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
            'identity_number' => 'nullable|string|max:20|unique:students,identity_number',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,ذكر,أنثى',
            'nationality' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'enrollment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * رسائل الخطأ المخصصة
     *
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'full_name.required' => 'حقل اسم الطالب مطلوب',
            'class_name.required' => 'حقل اسم الفصل مطلوب',
            'identity_number.unique' => 'رقم الهوية موجود بالفعل',
            'email.unique' => 'البريد الإلكتروني موجود بالفعل',
            'gender.in' => 'قيمة الجنس غير صالحة',
        ];
    }
}
