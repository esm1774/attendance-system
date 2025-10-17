<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index(Request $request)
    {
        $query = User::with('role')->withCount('userPermissions');

        // البحث بالاسم أو البريد الإلكتروني
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // التصفية حسب الدور
        if ($request->has('role') && $request->role != '') {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // التصفية حسب الحالة
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::active()->get();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * عرض نموذج إنشاء مستخدم جديد
     */
    public function create()
    {
        $roles = Role::active()->get();
        $permissions = Permission::all()->groupBy('group');
        
        return view('users.create', compact('roles', 'permissions'));
    }

    /**
     * تخزين مستخدم جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // إضافة الصلاحيات الخاصة إذا وجدت
            if ($request->has('permissions')) {
                foreach ($request->permissions as $permissionId) {
                    $user->userPermissions()->create([
                        'permission_id' => $permissionId,
                        'is_granted' => true,
                    ]);
                }
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * عرض بيانات مستخدم معين
     */
    public function show(User $user)
    {
        $user->load(['role', 'userPermissions.permission', 'userPermissions' => function ($query) {
            $query->active();
        }]);
        
        return view('users.show', compact('user'));
    }

    /**
     * عرض نموذج تعديل مستخدم
     */
    public function edit(User $user)
    {
        $roles = Role::active()->get();
        $permissions = Permission::all()->groupBy('group');
        $user->load('userPermissions');
        
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * تحديث بيانات مستخدم في قاعدة البيانات
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::transaction(function () use ($validated, $request, $user) {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role_id' => $validated['role_id'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'is_active' => $validated['is_active'] ?? $user->is_active,
            ];

            // تحديث كلمة المرور فقط إذا تم تقديمها
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // تحديث الصلاحيات الخاصة
            $user->userPermissions()->delete(); // حذف القديمة
            if ($request->has('permissions')) {
                foreach ($request->permissions as $permissionId) {
                    $user->userPermissions()->create([
                        'permission_id' => $permissionId,
                        'is_granted' => true,
                    ]);
                }
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    /**
     * حذف مستخدم من قاعدة البيانات
     */
    public function destroy(User $user)
    {
        // منع حذف المستخدم الأساسي
        if ($user->email === 'admin@school.com') {
            return redirect()->route('users.index')
                ->with('error', 'لا يمكن حذف المستخدم الأساسي للنظام.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }

    /**
     * تفعيل/تعطيل مستخدم
     */
    public function toggleStatus(User $user)
    {
        // منع تعطيل المستخدم الأساسي
        if ($user->email === 'admin@school.com' && $user->is_active) {
            return redirect()->route('users.index')
                ->with('error', 'لا يمكن تعطيل المستخدم الأساسي للنظام.');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->route('users.index')
            ->with('success', "تم $status المستخدم بنجاح.");
    }

    /**
     * تحديث الصلاحيات الخاصة لمستخدم
     */
    public function updatePermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::transaction(function () use ($validated, $request, $user) {
            $user->userPermissions()->delete();
            
            if ($request->has('permissions')) {
                foreach ($request->permissions as $permissionId) {
                    $user->userPermissions()->create([
                        'permission_id' => $permissionId,
                        'is_granted' => true,
                    ]);
                }
            }
        });

        return redirect()->route('users.show', $user)
            ->with('success', 'تم تحديث الصلاحيات الخاصة بنجاح.');
    }
}