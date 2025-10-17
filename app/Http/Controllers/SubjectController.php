<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * عرض قائمة المواد الدراسية
     */
    public function index(Request $request)
    {
        $query = Subject::query();

        // البحث بالاسم أو الرمز
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // التصفية حسب النوع
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // التصفية حسب الحالة
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $subjects = $query->latest()->paginate(10);

        return view('subjects.index', compact('subjects'));
    }

    /**
     * عرض نموذج إنشاء مادة جديدة
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * تخزين مادة جديدة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects',
            'description' => 'nullable|string',
            'type' => 'required|in:mandatory,elective',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated) {
            Subject::create([
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
        });

        return redirect()->route('subjects.index')
            ->with('success', 'تم إنشاء المادة الدراسية بنجاح.');
    }

    /**
     * عرض بيانات مادة معينة
     */
    /**
 * عرض بيانات مادة معينة
 */
        public function show(Subject $subject)
        {
            // تحميل العلاقات الأساسية فقط (لا تحميل teachers أو classes)
            // $subject->load(['teachers', 'classes']); // علق هذا السطر
            
            return view('subjects.show', compact('subject'));
        }

    /**
     * عرض نموذج تعديل مادة
     */
    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    /**
     * تحديث بيانات مادة في قاعدة البيانات
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects')->ignore($subject->id),
            ],
            'description' => 'nullable|string',
            'type' => 'required|in:mandatory,elective',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated, $subject) {
            $subject->update([
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'is_active' => $validated['is_active'] ?? $subject->is_active,
            ]);
        });

        return redirect()->route('subjects.index')
            ->with('success', 'تم تحديث المادة الدراسية بنجاح.');
    }

    /**
     * حذف مادة من قاعدة البيانات
     */
 /**
 * حذف مادة من قاعدة البيانات
 */
public function destroy(Subject $subject)
{
    try {
        // محاولة الحذف - إذا فشلت سترمي استثناء
        $subject->delete();
        
        return redirect()->route('subjects.index')
            ->with('success', 'تم حذف المادة الدراسية بنجاح.');
            
    } catch (\Illuminate\Database\QueryException $e) {
        // إذا كان الخطأ بسبب وجود علاقات (مفاتيح خارجية)
        $errorCode = $e->errorInfo[1];
        
        if ($errorCode == 1451) { // خطأ مفتاح خارجي في MySQL
            return redirect()->route('subjects.index')
                ->with('error', 'لا يمكن حذف المادة لأنها مرتبطة ببيانات أخرى في النظام.');
        }
        
        // لأي خطأ آخر
        return redirect()->route('subjects.index')
            ->with('error', 'حدث خطأ أثناء محاولة حذف المادة.');
            
    } catch (\Exception $e) {
        // لأي استثناء آخر
        return redirect()->route('subjects.index')
            ->with('error', 'حدث خطأ غير متوقع: ' . $e->getMessage());
    }
}
    /**
     * تفعيل/تعطيل مادة
     */
    public function toggleStatus(Subject $subject)
    {
        $subject->update([
            'is_active' => !$subject->is_active
        ]);

        $status = $subject->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('subjects.index')
            ->with('success', "تم $status المادة بنجاح.");
    }

    /**
     * الحصول على بيانات المواد للتضمين (للاستخدام في selects)
     */
    public function getSubjects(Request $request)
    {
        $subjects = Subject::active()->get();
        
        if ($request->expectsJson()) {
            return response()->json($subjects);
        }

        return $subjects;
    }

    /**
     * الحصول على المواد الإجبارية فقط
     */
    public function getMandatorySubjects()
    {
        return Subject::active()->mandatory()->get();
    }

    /**
     * الحصول على المواد الاختيارية فقط
     */
    public function getElectiveSubjects()
    {
        return Subject::active()->elective()->get();
    }
}