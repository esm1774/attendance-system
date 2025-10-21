<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel; // ✅ تصحيح المسار
use App\Imports\StudentsImport;
use App\Exports\StudentsTemplateExport;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StudentControllerNew extends Controller
{
    /**
     * عرض قائمة الطلاب مع خيارات البحث والتصفية
     */
    public function index(Request $request)
    {
        $query = Student::with(['class', 'class.grade']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                  ->orWhere('national_id', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
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

    /**
     * صفحة إضافة طالب جديد
     */
    public function create()
    {
        $classes = SchoolClass::with('grade')->get();
        return view('students.create', compact('classes'));
    }

    /**
     * تخزين بيانات الطالب الجديد
     */
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

    /**
     * عرض تفاصيل الطالب
     */
    public function show(Student $student)
    {
        $student->load(['class', 'class.grade']);
        return view('students.show', compact('student'));
    }

    /**
     * صفحة تعديل بيانات الطالب
     */
    public function edit(Student $student)
    {
        $classes = SchoolClass::with('grade')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * تحديث بيانات الطالب
     */
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

    /**
     * حذف الطالب
     */
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

    /**
     * تفعيل/تعطيل الطالب
     */
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

    /**
     * استيراد الطلاب من ملف Excel
     */
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

    /**
     * تحميل نموذج Excel فارغ
     */
    

public function downloadTemplate(): BinaryFileResponse
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // عناوين الأعمدة بالعربية
    $headers = [
        'اسم الفصل',
        'اسم الطالب', 
        'الهوية الوطنية',
        'تاريخ الميلاد',
        'الجنس',
        'الجنسية',
        'رقم الجوال',
        'البريد الإلكتروني',
        'اسم ولي الأمر',
        'صلة ولي الأمر (الأب ..)',
        'رقم جوال ولي الأمر',
        'أيميل ولي الأمر',
        'تاريخ التسجيل',
        'الحالة',
        'ملاحظات',
        'is_active'
    ];

    // إضافة العناوين
    foreach ($headers as $index => $header) {
        $sheet->setCellValueByColumnAndRow($index + 1, 1, $header);
    }

    // تنسيق العناوين
    $sheet->getStyle('A1:P1')->getFont()->setBold(true);

    // إضافة بيانات مثال
    $exampleData = [
        'الصف الأول أ',
        'أحمد محمد',
        '1112223334',
        '2020-01-15',
        'ذكر',
        'سعودي',
        '0551234567',
        'ahmed@example.com',
        'محمد أحمد',
        'الأب',
        '0557654321',
        'parent@example.com',
        '2024-01-01',
        'نشط',
        'لا توجد ملاحظات',
        '1'
    ];

    foreach ($exampleData as $index => $data) {
        $sheet->setCellValueByColumnAndRow($index + 1, 2, $data);
    }

    // ضبط عرض الأعمدة تلقائياً
    foreach (range('A', 'P') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // حفظ الملف
    $fileName = 'student-import-template-' . date('Y-m-d') . '.xlsx';
    $tempPath = storage_path('app/temp/' . $fileName);
    
    // التأكد من وجود المجلد
    if (!is_dir(dirname($tempPath))) {
        mkdir(dirname($tempPath), 0755, true);
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($tempPath);

    return response()->download($tempPath)->deleteFileAfterSend(true);
}

}
