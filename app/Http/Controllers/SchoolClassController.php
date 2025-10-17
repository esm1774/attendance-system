<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    /**
     * عرض قائمة الفصول الدراسية
     */
    public function index(Request $request)
    {
        $query = SchoolClass::with(['grade', 'teacher']);

        // البحث بالاسم أو الرمز
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // التصفية حسب الصف
        if ($request->has('grade_id') && $request->grade_id != '') {
            $query->where('grade_id', $request->grade_id);
        }

        // التصفية حسب الحالة
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $classes = $query->withCount(['students'])->latest()->paginate(10);
        $grades = Grade::active()->get();
        $teachers = User::whereHas('role', function($q) {
            $q->where('name', 'teacher');
        })->active()->get();

        return view('classes.index', compact('classes', 'grades', 'teachers'));
    }

    /**
     * عرض نموذج إنشاء فصل جديد
     */
    public function create()
    {
        $grades = Grade::active()->get();
        $teachers = User::whereHas('role', function($q) {
            $q->where('name', 'teacher');
        })->active()->get();

        return view('classes.create', compact('grades', 'teachers'));
    }

    /**
     * تخزين فصل جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:school_classes',
            'capacity' => 'required|integer|min:1|max:100',
            'teacher_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated) {
            SchoolClass::create([
                'grade_id' => $validated['grade_id'],
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'code' => $validated['code'],
                'capacity' => $validated['capacity'],
                'teacher_id' => $validated['teacher_id'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
        });

        return redirect()->route('classes.index')
            ->with('success', 'تم إنشاء الفصل الدراسي بنجاح.');
    }

    public function show(SchoolClass $class)
{
    // جلب أي بيانات إضافية تحتاج عرضها
    return view('classes.show', compact('class'));
}



    /**
     * عرض نموذج تعديل الفصل
     */
    public function edit($id)
    {
        // جلب بيانات الفصل
        $class = SchoolClass::findOrFail($id);

        // جلب الصفوف النشطة
        $grades = Grade::where('is_active', 1)->get();

        // جلب المعلمين النشطين
        $teachers = User::whereHas('role', function ($q) {
            $q->where('name', 'teacher')->where('is_active', 1);
        })->get();

        // إرسال البيانات للـ Blade
        return view('classes.edit', compact('class', 'grades', 'teachers'));
    }

    /**
     * تحديث بيانات الفصل
     */
    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        // التحقق من صحة البيانات
        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'teacher_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:school_classes,code,' . $class->id,
            'room_number' => 'nullable|string|max:50',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        // تحديث البيانات
        $class->update([
            'grade_id' => $validated['grade_id'],
            'teacher_id' => $validated['teacher_id'] ?? null,
            'name' => $validated['name'],
            'name_ar' => $validated['name_ar'],
            'code' => $validated['code'],
            'room_number' => $validated['room_number'] ?? null,
            'capacity' => $validated['capacity'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('classes.index')
                         ->with('success', 'تم تحديث بيانات الفصل بنجاح.');
    }

    /**
     * حذف فصل من قاعدة البيانات
     */
    public function destroy(SchoolClass $class)
    {
        try {
            // التحقق من وجود طلاب مرتبطين
            if ($class->students()->exists()) {
                return redirect()->route('classes.index')
                    ->with('error', 'لا يمكن حذف الفصل لأنه يحتوي على طلاب.');
            }

            // التحقق من وجود مواد مرتبطة
            if ($class->subjects()->exists()) {
                return redirect()->route('classes.index')
                    ->with('error', 'لا يمكن حذف الفصل لأنه مرتبط بمواد دراسية.');
            }

            $class->delete();

            return redirect()->route('classes.index')
                ->with('success', 'تم حذف الفصل الدراسي بنجاح.');
                
        } catch (\Exception $e) {
            return redirect()->route('classes.index')
                ->with('error', 'حدث خطأ أثناء محاولة حذف الفصل.');
        }
    }

    /**
     * تفعيل/تعطيل فصل
     */
    public function toggleStatus(SchoolClass $class)
    {
        $class->update([
            'is_active' => !$class->is_active
        ]);

        $status = $class->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('classes.index')
            ->with('success', "تم $status الفصل بنجاح.");
    }

    /**
     * الحصول على الفصول لصف معين (للاستخدام في AJAX)
     */
    public function getClassesByGrade(Request $request)
    {
        $gradeId = $request->get('grade_id');
        
        if (!$gradeId) {
            return response()->json([]);
        }

        $class = SchoolClass::where('grade_id', $gradeId)
                            ->active()
                            ->get(['id', 'name_ar as name']);

        return response()->json($class);
    }

    /**
     * الحصول على إحصائيات الفصل
     */
    public function getStats(SchoolClass $class)
    {
        $stats = [
            'students_count' => $class->students_count,
            'subjects_count' => $class->subjects()->count(),
            'occupancy_rate' => $class->occupancy_rate,
            'occupancy_text' => $class->occupancy_text,
            'occupancy_color' => $class->occupancy_color,
            'is_full' => $class->isFull(),
        ];

        return response()->json($stats);
    }
}