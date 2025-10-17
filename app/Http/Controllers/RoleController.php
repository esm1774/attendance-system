<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * عرض قائمة الأدوار
     */
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * عرض نموذج إنشاء دور جديد
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('roles.create', compact('permissions'));
    }

    /**
     * تخزين دور جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles|max:255',
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::transaction(function () use ($validated, $request) {
            $role = Role::create([
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'description' => $validated['description'],
                'is_active' => true,
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }
        });

        return redirect()->route('roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح.');
    }

    /**
     * عرض بيانات دور معين
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        return view('roles.show', compact('role'));
    }

    /**
     * عرض نموذج تعديل دور
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('group');
        $role->load('permissions');
        
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * تحديث بيانات دور في قاعدة البيانات
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::transaction(function () use ($validated, $request, $role) {
            $role->update([
                'name' => $validated['name'],
                'name_ar' => $validated['name_ar'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? $role->is_active,
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }
        });

        return redirect()->route('roles.index')
            ->with('success', 'تم تحديث الدور بنجاح.');
    }

    /**
     * حذف دور من قاعدة البيانات
     */
    public function destroy(Role $role)
    {
        // منع حذف الأدوار الأساسية
        $protectedRoles = ['admin', 'vice_principal', 'supervisor', 'teacher'];
        if (in_array($role->name, $protectedRoles)) {
            return redirect()->route('roles.index')
                ->with('error', 'لا يمكن حذف الأدوار الأساسية للنظام.');
        }

        // التحقق إذا كان الدور مرتبط بمستخدمين
        if ($role->users()->exists()) {
            return redirect()->route('roles.index')
                ->with('error', 'لا يمكن حذف الدور لأنه مرتبط بمستخدمين.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'تم حذف الدور بنجاح.');
    }

    /**
     * تفعيل/تعطيل دور
     */
    public function toggleStatus(Role $role)
    {
        $role->update([
            'is_active' => !$role->is_active
        ]);

        $status = $role->is_active ? 'مفعل' : 'معطل';
        
        return redirect()->route('roles.index')
            ->with('success', "تم $status الدور بنجاح.");
    }
}