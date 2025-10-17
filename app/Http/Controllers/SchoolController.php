<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SchoolController extends Controller
{
 /**
 * عرض قائمة المدارس
 */
public function index(Request $request)
{
    $query = School::query();

    // البحث بالاسم أو الرمز
    if ($request->has('search') && $request->search != '') {
        $query->search($request->search);
    }

    // التصفية حسب الحالة
    if ($request->has('status') && $request->status != '') {
        $query->where('is_active', $request->status == 'active');
    }

    // $schools = $query->withCount(['stages', 'users'])->latest()->paginate(10);
    $schools = $query->latest()->paginate(10); // مؤقتاً بدون withCount

    return view('schools.index', compact('schools'));
}
    /**
     * عرض نموذج إنشاء مدرسة جديدة
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * تخزين مدرسة جديدة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'principal_name' => 'nullable|string|max:255',
            'principal_name_ar' => 'nullable|string|max:255',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated) {
            School::create([
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'code' => $validated['code'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'principal_name' => $validated['principal_name'],
                'principal_name_ar' => $validated['principal_name_ar'],
                'established_year' => $validated['established_year'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
        });

        return redirect()->route('schools.index')
            ->with('success', 'تم إنشاء المدرسة بنجاح.');
    }

   /**
 * عرض بيانات مدرسة معينة
 */
public function show(School $school)
{
    // $school->load(['stages', 'users']); // علق هذا السطر مؤقتاً
    return view('schools.show', compact('school'));
}
    /**
     * عرض نموذج تعديل مدرسة
     */
    public function edit(School $school)
    {
        return view('schools.edit', compact('school'));
    }

    /**
     * تحديث بيانات مدرسة في قاعدة البيانات
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('schools')->ignore($school->id),
            ],
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'principal_name' => 'nullable|string|max:255',
            'principal_name_ar' => 'nullable|string|max:255',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated, $school) {
            $school->update([
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'code' => $validated['code'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'principal_name' => $validated['principal_name'],
                'principal_name_ar' => $validated['principal_name_ar'],
                'established_year' => $validated['established_year'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? $school->is_active,
            ]);
        });

        return redirect()->route('schools.index')
            ->with('success', 'تم تحديث المدرسة بنجاح.');
    }

    /**
 * حذف مدرسة من قاعدة البيانات
 */
public function destroy(School $school)
{
    try {
        // تسجيل محاولة الحذف
        \Log::info('Attempting to delete school:', [
            'school_id' => $school->id,
            'school_name' => $school->name,
            'school_code' => $school->code
        ]);

        // محاولة الحذف
        $school->delete();

        \Log::info('School deleted successfully:', [
            'school_id' => $school->id,
            'school_name' => $school->name
        ]);

        return redirect()->route('schools.index')
            ->with('success', 'تم حذف المدرسة بنجاح.');
            
    } catch (\Illuminate\Database\QueryException $e) {
        // الحصول على تفاصيل الخطأ من MySQL
        $errorCode = $e->errorInfo[1];
        $errorMessage = $e->errorInfo[2];
        
        \Log::error('School deletion - Database error:', [
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
            'sql_state' => $e->errorInfo[0],
            'school_id' => $school->id,
            'school_name' => $school->name,
            'full_exception' => $e->getMessage()
        ]);
        
        // رسائل خطأ محددة حسب كود الخطأ
        $userMessage = 'حدث خطأ في قاعدة البيانات أثناء محاولة الحذف.';
        
        if ($errorCode == 1451) {
            $userMessage = 'لا يمكن حذف المدرسة لأنها مرتبطة ببيانات أخرى في النظام.';
        } elseif ($errorCode == 1217) {
            $userMessage = 'لا يمكن حذف المدرسة بسبب قيود السلامة المرجعية.';
        } elseif ($errorCode == 1216) {
            $userMessage = 'خطأ في المفتاح الخارجي - لا يمكن حذف السجل الرئيسي.';
        }
        
        return redirect()->route('schools.index')
            ->with('error', $userMessage . ' (كود الخطأ: ' . $errorCode . ')');
            
    } catch (\Exception $e) {
        \Log::error('School deletion - General exception:', [
            'exception_message' => $e->getMessage(),
            'exception_type' => get_class($e),
            'school_id' => $school->id,
            'school_name' => $school->name,
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->route('schools.index')
            ->with('error', 'حدث خطأ غير متوقع: ' . $e->getMessage());
    }
}
    /**
     * تفعيل/تعطيل مدرسة
     */
    public function toggleStatus(School $school)
    {
        $school->update([
            'is_active' => !$school->is_active
        ]);

        $status = $school->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('schools.index')
            ->with('success', "تم $status المدرسة بنجاح.");
    }

    /**
     * الحصول على إحصائيات المدرسة
     */
    public function getStats(School $school)
    {
        $stats = [
            'stages_count' => $school->stages_count,
            'users_count' => $school->users_count,
            'active_stages' => $school->stages()->active()->count(),
            'active_users' => $school->users()->active()->count(),
        ];

        return response()->json($stats);
    }
}