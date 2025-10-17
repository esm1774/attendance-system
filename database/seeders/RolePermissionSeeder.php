<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على جميع الأدوار
        $adminRole = Role::where('name', 'admin')->first();
        $vicePrincipalRole = Role::where('name', 'vice_principal')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $teacherRole = Role::where('name', 'teacher')->first();

        // الحصول على جميع الصلاحيات
        $allPermissions = Permission::all();

        // مدير النظام: جميع الصلاحيات
        if ($adminRole && $allPermissions->isNotEmpty()) {
            $adminRole->permissions()->sync($allPermissions->pluck('id'));
            $this->command->info('✅ تم منح جميع الصلاحيات لمدير النظام');
        }

        // الوكيل: صلاحيات إدارية محدودة
        if ($vicePrincipalRole) {
            $vicePrincipalPermissions = Permission::whereIn('group', [
                'dashboard', 'users', 'students', 'attendance', 'reports'
            ])->whereNotIn('name', [
                'delete_users', 'delete_students', 'manage_settings', 'manage_roles'
            ])->get();

            $vicePrincipalRole->permissions()->sync($vicePrincipalPermissions->pluck('id'));
            $this->command->info('✅ تم منح الصلاحيات للوكيل');
        }

        // المراقب: صلاحيات المراقبة والمتابعة
        if ($supervisorRole) {
            $supervisorPermissions = Permission::whereIn('group', [
                'dashboard', 'students', 'attendance', 'reports'
            ])->whereNotIn('name', [
                'create_students', 'delete_students', 'manage_settings'
            ])->get();

            $supervisorRole->permissions()->sync($supervisorPermissions->pluck('id'));
            $this->command->info('✅ تم منح الصلاحيات للمراقب');
        }

        // المعلم: صلاحيات أساسية فقط
        if ($teacherRole) {
            $teacherPermissions = Permission::whereIn('name', [
                'view_dashboard',
                'view_students',
                'take_attendance',
                'view_attendance',
                'manage_excuses'
            ])->get();

            $teacherRole->permissions()->sync($teacherPermissions->pluck('id'));
            $this->command->info('✅ تم منح الصلاحيات للمعلم');
        }
    }
}