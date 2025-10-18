<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Imports\StudentsImport;
use Excel;

class StudentControllerNew extends Controller
{
    /**
     * عرض قائمة الطلاب
     */
    public function index(Request $request)
    {
        $query = Student::with(['class', 'class.grade']);

        // البحث بالاسم أو الرقم
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // التصفية حسب الفصل
        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('class_id', $request->class_id);
        }

        // التصفية حسب الجنس
        if ($request->has('gender') && $request->gender != '') {
            $query->where('gender', $request->gender);
        }

        // التصفية حسب الحالة
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // التصفية حسب النشاط
        if ($request->has('is_active') && $request->is_active != '') {
            $query->where('is_active', $request->is_active == 'active');
        }

        $students = $query->latest()->paginate(15);
        $classes = SchoolClass::active()->with('grade')->get();

        return view('students.index', compact('students', 'classes'));
    }

    /**
     * عرض نموذج إنشاء طالب جديد
     */
    public function create()
    {
        $classes = SchoolClass::active()->with('grade')->get();
        return view('students.create_simple', compact('classes'));
    }

    /**
     * تخزين طالب جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'full_name' => 'required|string|max:255',
            'national_id' => 'nullable|string|max:20|unique:students',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students',

            // بيانات ولي الأمر
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',

            // معلومات التسجيل
            'enrollment_date' => 'nullable|date',

            'is_active' => 'boolean',
            'status' => 'required|in:active,transferred,graduated,withdrawn',
            'notes' => 'nullable|string',
        ]);

        // إنشاء رقم جامعي تلقائي إذا لم يتم تقديمه
        if (!isset($validated['student_id'])) {
            $validated['student_id'] = 'STU-' . str_pad(Student::count() + 1, 5, '0', STR_PAD_LEFT);
        }

        DB::transaction(function () use ($validated) {
            $createData = [
                'class_id' => $validated['class_id'],
                'student_id' => $validated['student_id'],
                'full_name' => $validated['full_name'],
                'enrollment_date' => $validated['enrollment_date'] ?? now(),
                'is_active' => $validated['is_active'] ?? true,
                'status' => $validated['status'] ?? 'active',
            ];
            
            // إضافة الحقول الاختيارية فقط إذا كانت موجودة
            $optionalFields = [
                'national_id',
                'birth_date',
                'gender',
                'nationality',
                'phone',
                'email',
                'guardian_name',
                'guardian_relation',
                'guardian_phone',
                'guardian_email',
                'notes'
            ];
            
            foreach ($optionalFields as $field) {
                if (isset($validated[$field])) {
                    $createData[$field] = $validated[$field];
                }
            }
            
            Student::create($createData);
        });

        return redirect()->route('students.index')
            ->with('success', 'تم إنشاء الطالب بنجاح.');
    }

    /**
     * عرض بيانات طالب معين
     */
    public function show(Student $student)
    {
        $student->load(['class', 'class.grade']);
        return view('students.show', compact('student'));
    }

    /**
     * عرض نموذج تعديل بيانات طالب
     */
    public function edit(Student $student)
    {
        $classes = SchoolClass::active()->with('grade')->get();
        return view('students.edit_simple', compact('student', 'classes'));
    }

    /**
     * تحديث بيانات طالب في قاعدة البيانات
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'full_name' => 'required|string|max:255',
            'national_id' => 'nullable|string|max:20|unique:students,national_id,' . $student->id,
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email,' . $student->id,

            // بيانات ولي الأمر
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',

            // معلومات التسجيل
            'enrollment_date' => 'nullable|date',

            'is_active' => 'boolean',
            'status' => 'required|in:active,transferred,graduated,withdrawn',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $student) {
            $updateData = [
                'class_id' => $validated['class_id'],
                'full_name' => $validated['full_name'],
                'is_active' => $validated['is_active'] ?? true,
                'status' => $validated['status'],
            ];
            
            // إضافة الحقول الاختيارية فقط إذا كانت موجودة
            $optionalFields = [
                'national_id',
                'birth_date',
                'gender',
                'nationality',
                'phone',
                'email',
                'guardian_name',
                'guardian_relation',
                'guardian_phone',
                'guardian_email',
                'enrollment_date',
                'notes'
            ];
            
            foreach ($optionalFields as $field) {
                if (isset($validated[$field])) {
                    $updateData[$field] = $validated[$field];
                }
            }
            
            $student->update($updateData);
        });

        return redirect()->route('students.index')
            ->with('success', 'تم تحديث بيانات الطالب بنجاح.');
    }

    /**
     * حذف طالب من قاعدة البيانات
     */
    public function destroy(Student $student)
    {
        try {
            $student->delete();
            return redirect()->route('students.index')
                ->with('success', 'تم حذف الطالب بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('students.index')
                ->with('error', 'لا يمكن حذف الطالب لوجود سجلات مرتبطة به.');
        }
    }

    /**
     * تبديل حالة نشاط الطالب
     */
    public function toggleStatus(Student $student)
    {
        $student->update(['is_active' => !$student->is_active]);

        $status = $student->is_active ? 'تفعيل' : 'تعطيل';
        return redirect()->back()
            ->with('success', "تم {$status} الطالب بنجاح.");
    }

    /**
     * تغيير حالة الطالب
     */
    public function changeStatus(Request $request, Student $student)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,transferred,graduated,withdrawn',
            'notes' => 'nullable|string',
        ]);

        $student->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $student->notes,
        ]);

        return redirect()->back()
            ->with('success', 'تم تغيير حالة الطالب بنجاح.');
    }

    /**
     * استيراد الطلاب من ملف Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // 10MB
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));
            return redirect()->back()
                ->with('success', 'تم استيراد الطلاب بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء استيراد الطلاب: ' . $e->getMessage());
        }
    }

    /**
     * تنزيل قالب استيراد الطلاب
     */
    public function downloadImportTemplate()
    {
        return Excel::download(new \App\Exports\StudentsTemplateExport, 'students_template.xlsx');
    }

    /**
     * الحصول على إحصائيات الطلاب
     */
    public function getStats()
    {
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('is_active', true)->count(),
            'inactive' => Student::where('is_active', false)->count(),
            'by_status' => [
                'active' => Student::where('status', 'active')->count(),
                'transferred' => Student::where('status', 'transferred')->count(),
                'graduated' => Student::where('status', 'graduated')->count(),
                'withdrawn' => Student::where('status', 'withdrawn')->count(),
            ],
            'by_gender' => [
                'male' => Student::where('gender', 'male')->count(),
                'female' => Student::where('gender', 'female')->count(),
            ],
        ];

        return response()->json($stats);
    }
}
