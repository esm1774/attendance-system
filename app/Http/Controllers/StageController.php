<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StageController extends Controller
{
    /**
     * عرض قائمة المراحل الدراسية
     */
    public function index(Request $request)
    {
        $query = Stage::with(['school']);

        // البحث بالاسم أو الرمز
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // التصفية حسب المدرسة
        if ($request->has('school_id') && $request->school_id != '') {
            $query->where('school_id', $request->school_id);
        }

        // التصفية حسب الحالة
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $stages = $query->withCount('grades')->orderBy('order')->paginate(10);
        $schools = School::active()->get();

        return view('stages.index', compact('stages', 'schools'));
    }

    /**
     * عرض نموذج إنشاء مرحلة جديدة
     */
    public function create()
    {
        $schools = School::active()->get();
        return view('stages.create', compact('schools'));
    }

    /**
     * تخزين مرحلة جديدة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'min_age' => 'nullable|integer|min:3|max:20',
            'max_age' => 'nullable|integer|min:3|max:20',
            'is_active' => 'boolean',
        ]);

        // التحقق من أن اسم المرحلة فريد في نفس المدرسة
        $request->validate([
            'name' => [
                Rule::unique('stages')->where(function ($query) use ($request) {
                    return $query->where('school_id', $request->school_id);
                })
            ],
        ]);

        DB::transaction(function () use ($validated) {
            Stage::create([
                'school_id' => $validated['school_id'],
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'order' => $validated['order'] ?? null,
                'min_age' => $validated['min_age'],
                'max_age' => $validated['max_age'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
        });

        return redirect()->route('stages.index')
            ->with('success', 'تم إنشاء المرحلة الدراسية بنجاح.');
    }

    /**
     * عرض بيانات مرحلة معينة
     */
    public function show(Stage $stage)
    {
        $stage->load(['school', 'grades']);
        return view('stages.show', compact('stage'));
    }

    /**
     * عرض نموذج تعديل مرحلة
     */
    public function edit(Stage $stage)
    {
        $schools = School::active()->get();
        return view('stages.edit', compact('stage', 'schools'));
    }

    /**
     * تحديث بيانات مرحلة في قاعدة البيانات
     */
    public function update(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'min_age' => 'nullable|integer|min:3|max:20',
            'max_age' => 'nullable|integer|min:3|max:20',
            'is_active' => 'boolean',
        ]);

        // التحقق من أن اسم المرحلة فريد في نفس المدرسة (استثناء المرحلة الحالية)
        $request->validate([
            'name' => [
                Rule::unique('stages')->where(function ($query) use ($request, $stage) {
                    return $query->where('school_id', $request->school_id)
                                 ->where('id', '!=', $stage->id);
                })
            ],
        ]);

        DB::transaction(function () use ($validated, $stage) {
            $stage->update([
                'school_id' => $validated['school_id'],
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'order' => $validated['order'] ?? $stage->order,
                'min_age' => $validated['min_age'],
                'max_age' => $validated['max_age'],
                'is_active' => $validated['is_active'] ?? $stage->is_active,
            ]);
        });

        return redirect()->route('stages.index')
            ->with('success', 'تم تحديث المرحلة الدراسية بنجاح.');
    }

    /**
     * حذف مرحلة من قاعدة البيانات
     */
    public function destroy(Stage $stage)
    {
        try {
            // التحقق من وجود صفوف مرتبطة
            if ($stage->grades()->exists()) {
                return redirect()->route('stages.index')
                    ->with('error', 'لا يمكن حذف المرحلة لأنها تحتوي على صفوف دراسية.');
            }

            $stage->delete();

            return redirect()->route('stages.index')
                ->with('success', 'تم حذف المرحلة الدراسية بنجاح.');
                
        } catch (\Exception $e) {
            return redirect()->route('stages.index')
                ->with('error', 'حدث خطأ أثناء محاولة حذف المرحلة.');
        }
    }

    /**
     * تفعيل/تعطيل مرحلة
     */
    public function toggleStatus(Stage $stage)
    {
        $stage->update([
            'is_active' => !$stage->is_active
        ]);

        $status = $stage->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('stages.index')
            ->with('success', "تم $status المرحلة بنجاح.");
    }

    /**
     * الحصول على مراحل مدرسة محددة (للاستخدام في AJAX)
     */
    public function getStagesBySchool($schoolId)
    {
        $stages = Stage::where('school_id', $schoolId)
                      ->active()
                      ->orderBy('order')
                      ->get();

        return response()->json($stages);
    }

    /**
     * تحديث ترتيب المراحل
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'stages' => 'required|array',
            'stages.*.id' => 'required|exists:stages,id',
            'stages.*.order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->stages as $stageData) {
                Stage::where('id', $stageData['id'])->update([
                    'order' => $stageData['order']
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم تحديث الترتيب بنجاح.']);
    }
}