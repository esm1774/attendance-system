<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('role', function ($q) {
            $q->where('name', 'teacher');
        })->with('role');

        // البحث بالاسم أو البريد أو الرقم الوظيفي
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('teacher_id', 'like', "%{$search}%");
            });
        }

        // التصفية حسب التخصص
        if ($request->has('specialization') && $request->specialization != '') {
            $query->where('specialization', $request->specialization);
        }

        // التصفية حسب نوع التعيين
        if ($request->has('employment_type') && $request->employment_type != '') {
            $query->where('employment_type', $request->employment_type);
        }

        // التصفية حسب الحالة
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $teachers = $query->latest()->paginate(10);
        
        $specializations = User::whereHas('role', function ($q) {
            $q->where('name', 'teacher');
        })->whereNotNull('specialization')->distinct()->pluck('specialization');
        
        $employmentTypes = ['full_time', 'part_time', 'contract'];

        return view('teachers.index', compact('teachers', 'specializations', 'employmentTypes'));
    }

    /**
     * عرض نموذج إنشاء معلم جديد
     */
    public function create()
    {
        $subjects = Subject::active()->get();
        return view('teachers.create', compact('subjects'));
    }

    /**
     * تخزين معلم جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'teacher_id' => 'required|string|max:50|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'specialization' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'hire_date' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract',
            'salary' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id',
            'is_active' => 'boolean',
        ]);

        $teacherRole = Role::where('name', 'teacher')->first();

        DB::transaction(function () use ($validated, $request, $teacherRole) {
            $teacher = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $teacherRole->id,
                'teacher_id' => $validated['teacher_id'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'specialization' => $validated['specialization'],
                'qualification' => $validated['qualification'],
                'years_of_experience' => $validated['years_of_experience'],
                'hire_date' => $validated['hire_date'],
                'employment_type' => $validated['employment_type'],
                'salary' => $validated['salary'],
                'notes' => $validated['notes'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // ربط المعلم بالمواد
            if ($request->has('subjects')) {
                foreach ($request->subjects as $subjectId) {
                    // التحقق من التكرار أولاً
                    $existing = TeacherSubject::where('user_id', $teacher->id)
                        ->where('subject_id', $subjectId)
                        ->first();

                    if (!$existing) {
                        TeacherSubject::create([
                            'user_id' => $teacher->id,
                            'subject_id' => $subjectId,
                            'is_primary' => false
                        ]);
                    }
                }
            }
        });

        return redirect()->route('teachers.index')
            ->with('success', 'تم إنشاء المعلم بنجاح.');
    }

    /**
     * عرض بيانات معلم معين
     */
    public function show(User $teacher)
    {
        // التأكد أن المستخدم معلم
        if (!$teacher->role || $teacher->role->name !== 'teacher') {
            abort(404);
        }

        // تحميل البيانات الأساسية فقط (بدون العلاقات المفقودة)
        $teacher->load(['role']);
        
        // الحصول على المواد المرتبطة بالمعلم
        $teacherSubjects = TeacherSubject::where('user_id', $teacher->id)
            ->with('subject')
            ->get();
            
        // الحصول على الفصول التي يديرها المعلم
        $managedClasses = SchoolClass::where('teacher_id', $teacher->id)
            ->with(['grade', 'students'])
            ->get();

        return view('teachers.show', compact('teacher', 'teacherSubjects', 'managedClasses'));
    }

    /**
     * عرض نموذج تعديل معلم
     */
    public function edit(User $teacher)
    {
        // التأكد أن المستخدم معلم
        if (!$teacher->role || $teacher->role->name !== 'teacher') {
            abort(404);
        }

        $subjects = Subject::active()->get();
        
        // الحصول على المواد الحالية للمعلم
        $currentSubjects = TeacherSubject::where('user_id', $teacher->id)
            ->pluck('subject_id')
            ->toArray();

        return view('teachers.edit', compact('teacher', 'subjects', 'currentSubjects'));
    }

    /**
     * تحديث بيانات معلم في قاعدة البيانات
     */
    public function update(Request $request, User $teacher)
    {
        // التأكد أن المستخدم معلم
        if (!$teacher->role || $teacher->role->name !== 'teacher') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'teacher_id' => 'required|string|max:50|unique:users,teacher_id,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'specialization' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'hire_date' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract',
            'salary' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated, $request, $teacher) {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'teacher_id' => $validated['teacher_id'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'specialization' => $validated['specialization'],
                'qualification' => $validated['qualification'],
                'years_of_experience' => $validated['years_of_experience'],
                'hire_date' => $validated['hire_date'],
                'employment_type' => $validated['employment_type'],
                'salary' => $validated['salary'],
                'notes' => $validated['notes'],
                'is_active' => $validated['is_active'] ?? $teacher->is_active,
            ];

            // تحديث كلمة المرور فقط إذا تم تقديمها
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $teacher->update($updateData);

            // تحديث المواد (حذف القديم وإضافة الجديد)
            TeacherSubject::where('user_id', $teacher->id)->delete();
            
            if ($request->has('subjects')) {
                foreach ($request->subjects as $subjectId) {
                    TeacherSubject::create([
                        'user_id' => $teacher->id,
                        'subject_id' => $subjectId,
                        'is_primary' => false
                    ]);
                }
            }
        });

        return redirect()->route('teachers.index')
            ->with('success', 'تم تحديث المعلم بنجاح.');
    }

    /**
     * حذف معلم من قاعدة البيانات
     */
    public function destroy(User $teacher)
    {
        // التأكد أن المستخدم معلم
        if (!$teacher->role || $teacher->role->name !== 'teacher') {
            abort(404);
        }

        try {
            // التحقق من وجود فصول مرتبطة
            $hasClasses = SchoolClass::where('teacher_id', $teacher->id)->exists();
            if ($hasClasses) {
                return redirect()->route('teachers.index')
                    ->with('error', 'لا يمكن حذف المعلم لأنه مرتبط بفصول دراسية.');
            }

            // حذف المواد المرتبطة أولاً
            TeacherSubject::where('user_id', $teacher->id)->delete();
            
            $teacher->delete();

            return redirect()->route('teachers.index')
                ->with('success', 'تم حذف المعلم بنجاح.');
                
        } catch (\Exception $e) {
            return redirect()->route('teachers.index')
                ->with('error', 'حدث خطأ أثناء محاولة حذف المعلم: ' . $e->getMessage());
        }
    }

    /**
     * تفعيل/تعطيل معلم
     */
    public function toggleStatus(User $teacher)
    {
        // التأكد أن المستخدم معلم
        if (!$teacher->role || $teacher->role->name !== 'teacher') {
            abort(404);
        }

        $teacher->update([
            'is_active' => !$teacher->is_active
        ]);

        $status = $teacher->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('teachers.index')
            ->with('success', "تم $status المعلم بنجاح.");
    }

    /**
     * الحصول على إحصائيات المعلم
     */
    public function getStats(User $teacher)
    {
        // التأكد أن المستخدم معلم
        if (!$teacher->role || $teacher->role->name !== 'teacher') {
            return response()->json(['error' => 'المستخدم ليس معلم'], 404);
        }

        $stats = [
            'subjects_count' => TeacherSubject::where('user_id', $teacher->id)->count(),
            'classes_count' => SchoolClass::where('teacher_id', $teacher->id)->count(),
            'students_count' => SchoolClass::where('teacher_id', $teacher->id)
                ->withCount('students')
                ->get()
                ->sum('students_count'),
        ];

        return response()->json($stats);
    }

    /**
     * إضافة مادة للمعلم
     */
    public function addSubject(Request $request, User $teacher)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'is_primary' => 'boolean'
        ]);

        $existing = TeacherSubject::where('user_id', $teacher->id)
            ->where('subject_id', $validated['subject_id'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'المادة مضافة already للمعلم.');
        }

        TeacherSubject::create([
            'user_id' => $teacher->id,
            'subject_id' => $validated['subject_id'],
            'is_primary' => $validated['is_primary'] ?? false
        ]);

        return redirect()->back()
            ->with('success', 'تم إضافة المادة للمعلم بنجاح.');
    }

    /**
     * إزالة مادة من المعلم
     */
    public function removeSubject(User $teacher, Subject $subject)
    {
        TeacherSubject::where('user_id', $teacher->id)
            ->where('subject_id', $subject->id)
            ->delete();

        return redirect()->back()
            ->with('success', 'تم إزالة المادة من المعلم بنجاح.');
    }
}