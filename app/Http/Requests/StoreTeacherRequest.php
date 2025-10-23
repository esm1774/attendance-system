<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // المعلومات الشخصية
            'name' => ['required', 'string', 'max:255'],
            'national_id' => ['required', 'string', 'max:20', 'unique:teachers,national_id'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'nationality' => ['required', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            
            // معلومات التواصل
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:teachers,email'],
            'address' => ['nullable', 'string'],
            
            // المعلومات الوظيفية
            'employee_number' => ['required', 'string', 'max:50', 'unique:teachers,employee_number'],
            'specialization' => ['required', 'string', 'max:255'],
            'qualification' => ['required', 'string', 'max:255'],
            'hire_date' => ['required', 'date'],
            'contract_type' => ['required', 'in:permanent,temporary,substitute'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'department' => ['nullable', 'string', 'max:255'],
            
            // العلاقات
            'school_id' => ['required', 'exists:schools,id'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
            'classes' => ['nullable', 'array'],
            'classes.*' => ['exists:school_classes,id'],
            
            // الحالة
            'status' => ['required', 'in:active,on_leave,retired,transferred'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'الاسم الكامل',
            'national_id' => 'رقم الهوية',
            'birth_date' => 'تاريخ الميلاد',
            'gender' => 'الجنس',
            'nationality' => 'الجنسية',
            'photo' => 'الصورة الشخصية',
            'phone' => 'رقم الجوال',
            'email' => 'البريد الإلكتروني',
            'address' => 'العنوان',
            'employee_number' => 'الرقم الوظيفي',
            'specialization' => 'التخصص',
            'qualification' => 'المؤهل العلمي',
            'hire_date' => 'تاريخ التعيين',
            'contract_type' => 'نوع العقد',
            'salary' => 'الراتب الأساسي',
            'department' => 'القسم',
            'school_id' => 'المدرسة',
            'subjects' => 'المواد',
            'classes' => 'الفصول',
            'status' => 'الحالة',
            'is_active' => 'النشاط',
            'notes' => 'الملاحظات',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'national_id.unique' => 'رقم الهوية مسجل مسبقاً',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'employee_number.unique' => 'الرقم الوظيفي مسجل مسبقاً',
            'photo.image' => 'يجب أن يكون الملف صورة',
            'photo.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, jpg, png',
            'photo.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت',
            'birth_date.before' => 'تاريخ الميلاد يجب أن يكون قبل اليوم',
        ];
    }
}