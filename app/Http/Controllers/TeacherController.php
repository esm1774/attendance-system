<?php
namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\School;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    /**
     * عرض قائمة المعلمين
     */
    public function index(Request $request)
    {
        $teachers = Teacher::with(['school', 'subjects'])
            ->search($request->search)
            ->bySchool($request->school_id)
            ->bySpecialization($request->specialization)
            ->byGender($request->gender)
            ->byStatus($request->status)
            ->byContractType($request->contract_type)
            ->when($request->is_active !== null, function($query) use ($request) {
                if ($request->is_active == 'active') {
                    $query->where('is_active', true);
                } elseif ($request->is_active == 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // للفلاتر
        $schools = School::where('is_active', true)->get();
        $specializations = Teacher::select('specialization')->distinct()->pluck('specialization');

        return view('teachers.index', compact('teachers', 'schools', 'specializations'));
    }

    /**
     * عرض نموذج إضافة معلم جديد
     */
    public function create()
    {
        $schools = School::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $schoolClasses = SchoolClass::with('grade')->where('is_active', true)->get();
        
        return view('teachers.create', compact('schools', 'subjects', 'schoolClasses'));
    }

    /**
     * حفظ المعلم الجديد
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            Log::info('=== بدء عملية إضافة معلم جديد ===');
            Log::info('البيانات المستلمة:', $request->all());

            // التحقق من البيانات يدوياً
            $validator = Validator::make($request->all(), [
                // المعلومات الشخصية
                'name' => 'required|string|max:255',
                'national_id' => 'required|string|max:20|unique:teachers,national_id',
                'birth_date' => 'required|date|before:today',
                'gender' => 'required|in:male,female',
                'nationality' => 'required|string|max:100',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
                
                // معلومات التواصل
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:teachers,email',
                'address' => 'nullable|string',
                
                // المعلومات الوظيفية
                'employee_number' => 'required|string|max:50|unique:teachers,employee_number',
                'specialization' => 'required|string|max:255',
                'qualification' => 'required|string|max:255',
                'hire_date' => 'required|date',
                'contract_type' => 'required|in:permanent,temporary,substitute',
                'salary' => 'nullable|numeric|min:0',
                'department' => 'nullable|string|max:255',
                
                // العلاقات
                'school_id' => 'required|exists:schools,id',
                'subjects' => 'nullable|array',
                'subjects.*' => 'exists:subjects,id',
                'classes' => 'nullable|array',
                'classes.*' => 'exists:school_classes,id',
                
                // الحالة
                'status' => 'required|in:active,on_leave,retired,transferred',
                'is_active' => 'nullable|boolean',
                'notes' => 'nullable|string',
            ], [
                'name.required' => 'الاسم الكامل مطلوب',
                'national_id.required' => 'رقم الهوية مطلوب',
                'national_id.unique' => 'رقم الهوية مسجل مسبقاً',
                'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
                'employee_number.unique' => 'الرقم الوظيفي مسجل مسبقاً',
                'school_id.exists' => 'المدرسة المحددة غير موجودة',
                'birth_date.before' => 'تاريخ الميلاد يجب أن يكون قبل اليوم',
            ]);

            if ($validator->fails()) {
                Log::error('فشل التحقق من البيانات:', $validator->errors()->toArray());
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'يرجى التحقق من البيانات المدخلة');
            }

            $data = $validator->validated();
            
            // تحويل is_active إلى boolean
            $data['is_active'] = $request->has('is_active') ? true : false;
            
            Log::info('البيانات بعد التحقق:', $data);

            // رفع الصورة
            if ($request->hasFile('photo')) {
                try {
                    Log::info('بدء رفع الصورة...');
                    $photo = $request->file('photo');
                    $photoPath = $photo->store('teachers', 'public');
                    $data['photo'] = $photoPath;
                    Log::info('تم رفع الصورة بنجاح: ' . $photoPath);
                } catch (\Exception $e) {
                    Log::error('خطأ في رفع الصورة: ' . $e->getMessage());
                    throw new \Exception('فشل رفع الصورة: ' . $e->getMessage());
                }
            }

            // إزالة البيانات الإضافية من $data
            $subjects = $data['subjects'] ?? [];
            $classes = $request->input('classes', []);
            unset($data['subjects']);
            unset($data['classes']);

            // إنشاء المعلم
            try {
                Log::info('بدء إنشاء سجل المعلم...');
                $teacher = Teacher::create($data);
                Log::info('تم إنشاء المعلم بنجاح - ID: ' . $teacher->id);
            } catch (\Exception $e) {
                Log::error('خطأ في إنشاء المعلم: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                throw new \Exception('فشل إنشاء المعلم: ' . $e->getMessage());
            }

            // ربط المواد في جدول teacher_subject
            if (!empty($subjects) && is_array($subjects)) {
                try {
                    Log::info('بدء ربط المواد...');
                    Log::info('المواد المحددة:', $subjects);
                    $teacher->subjects()->attach($subjects);
                    Log::info('تم ربط المواد بنجاح');
                } catch (\Exception $e) {
                    Log::error('خطأ في ربط المواد: ' . $e->getMessage());
                    throw new \Exception('فشل ربط المواد: ' . $e->getMessage());
                }
            }

            // ربط الفصول في جدول teacher_school_class
            if (!empty($classes) && is_array($classes)) {
                try {
                    Log::info('بدء ربط الفصول...');
                    Log::info('الفصول المحددة:', $classes);
                    
                    foreach ($classes as $classId) {
                        $subjectId = $request->input("class_subject_{$classId}");
                        $isClassTeacher = $request->input("is_class_teacher_{$classId}") ? true : false;
                        
                        Log::info("ربط الفصل {$classId} - المادة: {$subjectId} - رائد فصل: " . ($isClassTeacher ? 'نعم' : 'لا'));
                        
                        $teacher->schoolClasses()->attach($classId, [
                            'subject_id' => $subjectId,
                            'is_class_teacher' => $isClassTeacher,
                        ]);
                    }
                    Log::info('تم ربط الفصول بنجاح');
                } catch (\Exception $e) {
                    Log::error('خطأ في ربط الفصول: ' . $e->getMessage());
                    throw new \Exception('فشل ربط الفصول: ' . $e->getMessage());
                }
            }

            DB::commit();
            Log::info('=== تمت عملية إضافة المعلم بنجاح ===');

            return redirect()
                ->route('teachers.index')
                ->with('success', 'تم إضافة المعلم بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // حذف الصورة إذا تم رفعها
            if (isset($data['photo']) && Storage::disk('public')->exists($data['photo'])) {
                Storage::disk('public')->delete($data['photo']);
            }

            Log::error('=== فشلت عملية إضافة المعلم ===');
            Log::error('رسالة الخطأ: ' . $e->getMessage());
            Log::error('السطر: ' . $e->getLine());
            Log::error('الملف: ' . $e->getFile());

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة المعلم: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل المعلم
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['school', 'subjects', 'schoolClasses']);
        
        return view('teachers.show', compact('teacher'));
    }

    /**
     * عرض نموذج تعديل المعلم
     */
    public function edit(Teacher $teacher)
    {
        $schools = School::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $schoolClasses = SchoolClass::with('grade')->where('is_active', true)->get();
        
        return view('teachers.edit', compact('teacher', 'schools', 'subjects', 'schoolClasses'));
    }

    /**
     * تحديث بيانات المعلم
     */
    public function update(Request $request, Teacher $teacher)
{
    DB::beginTransaction();
    
    try {
        Log::info('=== بدء عملية تحديث المعلم ID: ' . $teacher->id . ' ===');
        Log::info('البيانات المستلمة:', $request->all());

        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:20|unique:teachers,national_id,' . $teacher->id,
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:teachers,email,' . $teacher->id,
            'address' => 'nullable|string',
            'employee_number' => 'required|string|max:50|unique:teachers,employee_number,' . $teacher->id,
            'specialization' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'contract_type' => 'required|in:permanent,temporary,substitute',
            'salary' => 'nullable|numeric|min:0',
            'department' => 'nullable|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:school_classes,id',
            'status' => 'required|in:active,on_leave,retired,transferred',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('فشل التحقق من البيانات:', $validator->errors()->toArray());
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة');
        }

        $data = $validator->validated();
        $data['is_active'] = $request->has('is_active') ? true : false;

        // رفع الصورة الجديدة إن وجدت
        if ($request->hasFile('photo')) {
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
                Log::info('تم حذف الصورة القديمة');
            }

            $photoPath = $request->file('photo')->store('teachers', 'public');
            $data['photo'] = $photoPath;
            Log::info('تم رفع الصورة الجديدة: ' . $photoPath);
        }

        // استخراج العلاقات
        $subjects = $data['subjects'] ?? [];
        $classes = $request->input('classes', []);
        unset($data['subjects'], $data['classes']);

        // تحديث بيانات المعلم الأساسية
        $teacher->update($data);
        Log::info('تم تحديث بيانات المعلم بنجاح');

        /** ======================
         * تحديث المواد الدراسية
         * ====================== */
        try {
            $teacher->subjects()->sync($subjects);
            Log::info('تم تحديث المواد الدراسية');
        } catch (\Exception $e) {
            Log::error('خطأ في تحديث المواد: ' . $e->getMessage());
            throw new \Exception('فشل تحديث المواد: ' . $e->getMessage());
        }

        /** ======================
         * تحديث الفصول الدراسية
         * ====================== */
        try {
            $pivotData = [];
            foreach ($classes as $classId) {
                $pivotData[$classId] = [
                    'subject_id' => $request->input("class_subject_{$classId}"),
                    'is_class_teacher' => $request->has("is_class_teacher_{$classId}") ? 1 : 0,
                ];
            }
            $teacher->schoolClasses()->sync($pivotData);
            Log::info('تم تحديث الفصول الدراسية بنجاح');
        } catch (\Exception $e) {
            Log::error('خطأ في تحديث الفصول: ' . $e->getMessage());
            throw new \Exception('فشل تحديث الفصول: ' . $e->getMessage());
        }

        DB::commit();
        Log::info('=== تمت عملية التحديث بنجاح ===');

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تم تحديث بيانات المعلم بنجاح');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('=== فشلت عملية تحديث المعلم ===');
        Log::error('رسالة الخطأ: ' . $e->getMessage());

        return back()
            ->withInput()
            ->with('error', 'حدث خطأ أثناء تحديث المعلم: ' . $e->getMessage());
    }
}


    /**
     * حذف المعلم
     */
    public function destroy(Teacher $teacher)
    {
        try {
            Log::info('=== بدء عملية حذف المعلم ID: ' . $teacher->id . ' ===');

            // حذف الصورة
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
                Log::info('تم حذف الصورة');
            }

            // حذف المعلم (Soft Delete)
            $teacher->delete();
            Log::info('تم حذف المعلم بنجاح');

            return redirect()
                ->route('teachers.index')
                ->with('success', 'تم حذف المعلم بنجاح');

        } catch (\Exception $e) {
            Log::error('=== فشلت عملية حذف المعلم ===');
            Log::error('رسالة الخطأ: ' . $e->getMessage());

            return back()
                ->with('error', 'حدث خطأ أثناء حذف المعلم: ' . $e->getMessage());
        }
    }

    /**
     * تبديل حالة النشاط
     */
    public function toggleStatus(Teacher $teacher)
    {
        try {
            $teacher->update([
                'is_active' => !$teacher->is_active
            ]);

            $status = $teacher->is_active ? 'تفعيل' : 'تعطيل';

            return redirect()
                ->route('teachers.index')
                ->with('success', "تم {$status} المعلم بنجاح");

        } catch (\Exception $e) {
            Log::error('خطأ في تغيير حالة المعلم: ' . $e->getMessage());
            return back()
                ->with('error', 'حدث خطأ أثناء تغيير الحالة: ' . $e->getMessage());
        }
    }
}
