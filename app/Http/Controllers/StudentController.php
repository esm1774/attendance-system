<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
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
        return view('students.create', compact('classes'));
    }

    /**
     * تخزين طالب جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'student_id' => 'required|string|max:50|unique:students',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name_ar' => 'required|string|max:255',
            'middle_name_ar' => 'nullable|string|max:255',
            'last_name_ar' => 'required|string|max:255',
            'national_id' => 'nullable|string|max:20|unique:students',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'birth_place' => 'nullable|string|max:255',
            'nationality' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students',
            
            // بيانات ولي الأمر
            'guardian_name' => 'required|string|max:255',
            'guardian_relation' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            
            // معلومات طبية
            'medical_notes' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            
            // معلومات التسجيل
            'enrollment_date' => 'required|date',
            'enrollment_type' => 'required|in:new,transferred',
            'previous_school' => 'nullable|string|max:255',
            
            'is_active' => 'boolean',
            'status' => 'required|in:active,transferred,graduated,withdrawn',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            Student::create([
                'class_id' => $validated['class_id'],
                'student_id' => $validated['student_id'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'first_name_ar' => $validated['first_name_ar'],
                'middle_name_ar' => $validated['middle_name_ar'],
                'last_name_ar' => $validated['last_name_ar'],
                'national_id' => $validated['national_id'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'birth_place' => $validated['birth_place'],
                'nationality' => $validated['nationality'],
                'religion' => $validated['religion'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'guardian_name' => $validated['guardian_name'],
                'guardian_relation' => $validated['guardian_relation'],
                'guardian_phone' => $validated['guardian_phone'],
                'guardian_email' => $validated['guardian_email'],
                'emergency_phone' => $validated['emergency_phone'],
                'medical_notes' => $validated['medical_notes'],
                'blood_type' => $validated['blood_type'],
                'allergies' => $validated['allergies'],
                'enrollment_date' => $validated['enrollment_date'],
                'enrollment_type' => $validated['enrollment_type'],
                'previous_school' => $validated['previous_school'],
                'is_active' => $validated['is_active'] ?? true,
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);
        });

        return redirect()->route('students.index')
            ->with('success', 'تم إنشاء الطالب بنجاح.');
    }

    /**
     * عرض بيانات طالب معين
     */
    public function show(Student $student)
    {
        $student->load(['class', 'class.grade', 'attendances']);
        return view('students.show', compact('student'));
    }

    /**
     * عرض نموذج تعديل طالب
     */
    public function edit(Student $student)
    {
        $classes = SchoolClass::active()->with('grade')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * تحديث بيانات طالب في قاعدة البيانات
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'student_id' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students')->ignore($student->id),
            ],
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name_ar' => 'required|string|max:255',
            'middle_name_ar' => 'nullable|string|max:255',
            'last_name_ar' => 'required|string|max:255',
            'national_id' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('students')->ignore($student->id),
            ],
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'birth_place' => 'nullable|string|max:255',
            'nationality' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('students')->ignore($student->id),
            ],
            
            // بيانات ولي الأمر
            'guardian_name' => 'required|string|max:255',
            'guardian_relation' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            
            // معلومات طبية
            'medical_notes' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            
            // معلومات التسجيل
            'enrollment_date' => 'required|date',
            'enrollment_type' => 'required|in:new,transferred',
            'previous_school' => 'nullable|string|max:255',
            
            'is_active' => 'boolean',
            'status' => 'required|in:active,transferred,graduated,withdrawn',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $student) {
            $student->update([
                'class_id' => $validated['class_id'],
                'student_id' => $validated['student_id'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'first_name_ar' => $validated['first_name_ar'],
                'middle_name_ar' => $validated['middle_name_ar'],
                'last_name_ar' => $validated['last_name_ar'],
                'national_id' => $validated['national_id'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'birth_place' => $validated['birth_place'],
                'nationality' => $validated['nationality'],
                'religion' => $validated['religion'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'guardian_name' => $validated['guardian_name'],
                'guardian_relation' => $validated['guardian_relation'],
                'guardian_phone' => $validated['guardian_phone'],
                'guardian_email' => $validated['guardian_email'],
                'emergency_phone' => $validated['emergency_phone'],
                'medical_notes' => $validated['medical_notes'],
                'blood_type' => $validated['blood_type'],
                'allergies' => $validated['allergies'],
                'enrollment_date' => $validated['enrollment_date'],
                'enrollment_type' => $validated['enrollment_type'],
                'previous_school' => $validated['previous_school'],
                'is_active' => $validated['is_active'] ?? $student->is_active,
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);
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
            // التحقق من وجود سجلات حضور مرتبطة
            if ($student->attendances()->exists()) {
                return redirect()->route('students.index')
                    ->with('error', 'لا يمكن حذف الطالب لأنه مرتبط بسجلات حضور.');
            }

            // التحقق من وجود درجات مرتبطة
            if ($student->grades()->exists()) {
                return redirect()->route('students.index')
                    ->with('error', 'لا يمكن حذف الطالب لأنه مرتبط بدرجات.');
            }

            $student->delete();

            return redirect()->route('students.index')
                ->with('success', 'تم حذف الطالب بنجاح.');
                
        } catch (\Exception $e) {
            return redirect()->route('students.index')
                ->with('error', 'حدث خطأ أثناء محاولة حذف الطالب.');
        }
    }

    /**
     * تفعيل/تعطيل طالب
     */
    public function toggleStatus(Student $student)
    {
        $student->update([
            'is_active' => !$student->is_active
        ]);

        $status = $student->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('students.index')
            ->with('success', "تم $status الطالب بنجاح.");
    }

    /**
     * تغيير حالة الطالب
     */
    public function changeStatus(Request $request, Student $student)
    {
        $request->validate([
            'status' => 'required|in:active,transferred,graduated,withdrawn'
        ]);

        $student->update(['status' => $request->status]);

        return redirect()->route('students.show', $student)
            ->with('success', 'تم تغيير حالة الطالب بنجاح.');
    }

    /**
     * استيراد الطلاب من ملف Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));
            
            return redirect()->route('students.index')
                ->with('success', 'تم استيراد الطلاب بنجاح.');
                
        } catch (\Exception $e) {
            return redirect()->route('students.index')
                ->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    /**
     * تحميل نموذج الاستيراد
     */
    public function downloadImportTemplate()
    {
        $filePath = public_path('templates/students_import_template.xlsx');
        
        if (!file_exists($filePath)) {
            return redirect()->route('students.index')
                ->with('error', 'نموذج الاستيراد غير متوفر.');
        }

        return response()->download($filePath);
    }

    /**
     * الحصول على إحصائيات الطلاب
     */
    public function getStats()
    {
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::active()->count(),
            'male_students' => Student::where('gender', 'male')->count(),
            'female_students' => Student::where('gender', 'female')->count(),
            'new_students' => Student::where('enrollment_type', 'new')->count(),
            'transferred_students' => Student::where('enrollment_type', 'transferred')->count(),
        ];

        return response()->json($stats);
    }
}