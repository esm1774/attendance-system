<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Exports\StudentsTemplateExport;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['class', 'class.grade']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == 'active');
        }

        $students = $query->latest()->paginate(15);
        $classes = SchoolClass::with('grade')->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::with('grade')->get();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'full_name' => 'required|string|max:255',
            'national_id' => 'nullable|string|max:20|unique:students,national_id',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'blood_type' => 'nullable|string|max:10',
            'allergies' => 'nullable|string|max:500',
            'medical_notes' => 'nullable|string|max:1000',
            'enrollment_date' => 'nullable|date',
            'enrollment_type' => 'nullable|in:new,transferred',
            'status' => 'required|in:active,transferred,graduated,withdrawn',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $validated['is_active'] = $request->has('is_active') ? 1 : 1; // افتراضياً نشط
            $validated['status'] = $validated['status'] ?? 'active';
            $validated['enrollment_date'] = $validated['enrollment_date'] ?? now();

            Student::create($validated);

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'تم إضافة الطالب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error creating student: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الطالب: ' . $e->getMessage());
        }
    }

    public function show(Student $student)
    {
        $student->load(['class', 'class.grade']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::with('grade')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'full_name' => 'required|string|max:255',
            'national_id' => 'nullable|string|max:20|unique:students,national_id,' . $student->id,
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email,' . $student->id,
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'blood_type' => 'nullable|string|max:10',
            'allergies' => 'nullable|string|max:500',
            'medical_notes' => 'nullable|string|max:1000',
            'enrollment_date' => 'nullable|date',
            'enrollment_type' => 'nullable|in:new,transferred',
            'status' => 'required|in:active,transferred,graduated,withdrawn',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $validated['is_active'] = $request->has('is_active') ? 1 : 0;

            $student->update($validated);

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'تم تحديث بيانات الطالب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error updating student: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات الطالب: ' . $e->getMessage());
        }
    }

    public function destroy(Student $student)
    {
        try {
            $student->delete();
            
            return redirect()->route('students.index')
                ->with('success', 'تم حذف الطالب بنجاح');
                
        } catch (\Exception $e) {
            \Log::error('Error deleting student: ' . $e->getMessage());
            
            return redirect()->route('students.index')
                ->with('error', 'لا يمكن حذف الطالب لوجود سجلات مرتبطة به');
        }
    }

    public function toggleStatus(Student $student)
    {
        try {
            $student->update(['is_active' => !$student->is_active]);

            $status = $student->is_active ? 'تفعيل' : 'تعطيل';
            
            return redirect()->back()
                ->with('success', "تم {$status} الطالب بنجاح");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تغيير حالة الطالب');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file.required' => 'يرجى اختيار ملف للاستيراد',
            'file.mimes' => 'يجب أن يكون الملف بصيغة Excel (xlsx, xls) أو CSV',
            'file.max' => 'حجم الملف يجب أن لا يتجاوز 10 ميجابايت',
        ]);

        try {
            DB::beginTransaction();
            
            Excel::import(new StudentsImport, $request->file('file'));
            
            DB::commit();
            
            return redirect()->route('students.index')
                ->with('success', 'تم استيراد الطلاب بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error importing students: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء استيراد الطلاب: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        try {
            return Excel::download(new StudentsTemplateExport, 'students_template.xlsx');
        } catch (\Exception $e) {
            \Log::error('Error downloading template: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحميل النموذج: ' . $e->getMessage());
        }
    }
}