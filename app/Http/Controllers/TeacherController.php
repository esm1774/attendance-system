<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\School;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
    public function store(StoreTeacherRequest $request)
    {
        DB::beginTransaction();
        
        try {
            // 1. التحقق من البيانات المستلمة
            Log::info('=== بدء عملية إضافة معلم جديد ===');
            Log::info('البيانات المستلمة:', $request->all());

            $data = $request->validated();
            Log::info('البيانات بعد التحقق:', $data);

            // 2. رفع الصورة
            if ($request->hasFile('photo')) {
                try {
                    Log::info('بدء رفع الصورة...');
                    $data['photo'] = $request->file('photo')->store('teachers', 'public');
                    Log::info('تم رفع الصورة بنجاح: ' . $data['photo']);
                } catch (\Exception $e) {
                    Log::error('خطأ في رفع الصورة: ' . $e->getMessage());
                    throw new \Exception('فشل رفع الصورة: ' . $e->getMessage());
                }
            }

            // 3. إنشاء المعلم
            try {
                Log::info('بدء إنشاء سجل المعلم...');
                $teacher = Teacher::create($data);
                Log::info('تم إنشاء المعلم بنجاح - ID: ' . $teacher->id);
            } catch (\Exception $e) {
                Log::error('خطأ في إنشاء المعلم: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                throw new \Exception('فشل إنشاء المعلم: ' . $e->getMessage());
            }

            // 4. ربط المواد
            if ($request->has('subjects') && is_array($request->subjects)) {
                try {
                    Log::info('بدء ربط المواد...');
                    Log::info('المواد المحددة:', $request->subjects);
                    $teacher->subjects()->attach($request->subjects);
                    Log::info('تم ربط المواد بنجاح');
                } catch (\Exception $e) {
                    Log::error('خطأ في ربط المواد: ' . $e->getMessage());
                    throw new \Exception('فشل ربط المواد: ' . $e->getMessage());
                }
            } else {
                Log::info('لم يتم تحديد أي مواد');
            }

            // 5. ربط الفصول
            if ($request->has('classes') && is_array($request->classes)) {
                try {
                    Log::info('بدء ربط الفصول...');
                    Log::info('الفصول المحددة:', $request->classes);
                    
                    foreach ($request->classes as $classId) {
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
            } else {
                Log::info('لم يتم تحديد أي فصول');
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
        $teacher->load(['school', 'subjects', 'schoolClasses.grade', 'classTeacherOf']);
        
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
        
        // الفصول المرتبطة بالمعلم مع معلومات المادة
        $teacherClasses = $teacher->schoolClasses()
            ->withPivot('subject_id', 'is_class_teacher')
            ->get()
            ->keyBy('id');
        
        return view('teachers.edit', compact('teacher', 'schools', 'subjects', 'schoolClasses', 'teacherClasses'));
    }

    /**
     * تحديث بيانات المعلم
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        DB::beginTransaction();
        
        try {
            Log::info('=== بدء عملية تحديث المعلم ID: ' . $teacher->id . ' ===');
            Log::info('البيانات المستلمة:', $request->all());

            $data = $request->validated();

            // رفع الصورة الجديدة
            if ($request->hasFile('photo')) {
                try {
                    // حذف الصورة القديمة
                    if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                        Storage::disk('public')->delete($teacher->photo);
                        Log::info('تم حذف الصورة القديمة');
                    }
                    $data['photo'] = $request->file('photo')->store('teachers', 'public');
                    Log::info('تم رفع الصورة الجديدة: ' . $data['photo']);
                } catch (\Exception $e) {
                    Log::error('خطأ في رفع الصورة: ' . $e->getMessage());
                    throw new \Exception('فشل رفع الصورة: ' . $e->getMessage());
                }
            }

            // تحديث بيانات المعلم
            try {
                $teacher->update($data);
                Log::info('تم تحديث بيانات المعلم بنجاح');
            } catch (\Exception $e) {
                Log::error('خطأ في تحديث المعلم: ' . $e->getMessage());
                throw new \Exception('فشل تحديث المعلم: ' . $e->getMessage());
            }

            // تحديث المواد
            try {
                if ($request->has('subjects')) {
                    $teacher->subjects()->sync($request->subjects);
                    Log::info('تم تحديث المواد بنجاح');
                } else {
                    $teacher->subjects()->detach();
                    Log::info('تم إزالة جميع المواد');
                }
            } catch (\Exception $e) {
                Log::error('خطأ في تحديث المواد: ' . $e->getMessage());
                throw new \Exception('فشل تحديث المواد: ' . $e->getMessage());
            }

            // تحديث الفصول
            try {
                $syncData = [];
                if ($request->has('classes')) {
                    foreach ($request->classes as $classId) {
                        $subjectId = $request->input("class_subject_{$classId}");
                        $isClassTeacher = $request->input("is_class_teacher_{$classId}") ? true : false;
                        
                        $syncData[$classId] = [
                            'subject_id' => $subjectId,
                            'is_class_teacher' => $isClassTeacher,
                        ];
                    }
                }
                $teacher->schoolClasses()->sync($syncData);
                Log::info('تم تحديث الفصول بنجاح');
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

    /**
     * تحميل نموذج الاستيراد
     */
    public function downloadTemplate()
    {
        return back()->with('info', 'هذه الميزة قيد التطوير');
    }

    /**
     * استيراد المعلمين من Excel
     */
    public function import(Request $request)
    {
        return back()->with('info', 'هذه الميزة قيد التطوير');
    }

    /**
     * تصدير المعلمين إلى Excel
     */
    public function export(Request $request)
    {
        return back()->with('info', 'هذه الميزة قيد التطوير');
    }
}