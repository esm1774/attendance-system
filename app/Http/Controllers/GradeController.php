<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    /**
     * عرض قائمة الصفوف الدراسية
     */
    public function index(Request $request)
    {
        $query = Grade::with(['stage.school']);

        // البحث بالاسم أو الوصف
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // التصفية حسب المرحلة
        if ($request->has('stage_id') && $request->stage_id != '') {
            $query->where('stage_id', $request->stage_id);
        }

        // التصفية حسب الحالة
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $grades = $query->withCount('classes')->latest()->paginate(10);
        $stages = Stage::with('school')->active()->get();

        return view('grades.index', compact('grades', 'stages'));
    }

    /**
     * عرض نموذج إنشاء صف جديد
     */
    public function create()
    {
        $stages = Stage::with('school')->active()->get();
        return view('grades.create', compact('stages'));
    }

    /**
     * تخزين صف جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'stage_id' => 'required|exists:stages,id',
            'order' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated) {
            Grade::create([
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'stage_id' => $validated['stage_id'],
                'order' => $validated['order'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
        });

        return redirect()->route('grades.index')
            ->with('success', 'تم إنشاء الصف الدراسي بنجاح.');
    }

    /**
     * عرض بيانات صف معين
     */
    public function show(Grade $grade)
    {
        $grade->load(['stage.school', 'classes.teacher']);
        return view('grades.show', compact('grade'));
    }

    /**
     * عرض نموذج تعديل صف
     */
    public function edit(Grade $grade)
    {
        $stages = Stage::with('school')->active()->get();
        return view('grades.edit', compact('grade', 'stages'));
    }

    /**
     * تحديث بيانات صف في قاعدة البيانات
     */
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'stage_id' => 'required|exists:stages,id',
            'order' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated, $grade) {
            $grade->update([
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'stage_id' => $validated['stage_id'],
                'order' => $validated['order'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? $grade->is_active,
            ]);
        });

        return redirect()->route('grades.index')
            ->with('success', 'تم تحديث الصف الدراسي بنجاح.');
    }

    /**
     * حذف صف من قاعدة البيانات
     */
    public function destroy(Grade $grade)
    {
        try {
            // التحقق من وجود فصول مرتبطة
            if ($grade->classes()->exists()) {
                return redirect()->route('grades.index')
                    ->with('error', 'لا يمكن حذف الصف لأنه يحتوي على فصول دراسية.');
            }

            $grade->delete();

            return redirect()->route('grades.index')
                ->with('success', 'تم حذف الصف الدراسي بنجاح.');
                
        } catch (\Exception $e) {
            return redirect()->route('grades.index')
                ->with('error', 'حدث خطأ أثناء محاولة حذف الصف.');
        }
    }

    /**
     * تفعيل/تعطيل صف
     */
    public function toggleStatus(Grade $grade)
    {
        $grade->update([
            'is_active' => !$grade->is_active
        ]);

        $status = $grade->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('grades.index')
            ->with('success', "تم $status الصف بنجاح.");
    }

    /**
     * الحصول على صفوف مرحلة معينة
     */
    public function getByStage(Stage $stage)
    {
        $grades = $stage->grades()->active()->get();
        
        return response()->json($grades);
    }
}