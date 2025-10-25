<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // جلب جميع الصلاحيات
        $allPermissions = DB::table('permissions')->pluck('id')->toArray();
        
        // الأدوار الافتراضية
        $roles = [
            [
                'name' => 'super-admin',
                'name_ar' => 'مدير النظام',
                'description' => 'صلاحيات كاملة على جميع أجزاء النظام',
                'permissions' => $allPermissions, // جميع الصلاحيات
            ],
            [
                'name' => 'school-admin',
                'name_ar' => 'مدير مدرسة',
                'description' => 'إدارة جميع أقسام المدرسة',
                'permissions' => [
                    'view-dashboard',
                    // المدارس
                    'view-schools',
                    'view-school-stats',
                    // المراحل والصفوف والفصول
                    'view-stages', 'create-stages', 'edit-stages', 'delete-stages',
                    'view-grades', 'create-grades', 'edit-grades', 'delete-grades', 'manage-grade-subjects',
                    'view-classes', 'create-classes', 'edit-classes', 'delete-classes', 'view-class-stats',
                    // المواد
                    'view-subjects', 'create-subjects', 'edit-subjects', 'delete-subjects',
                    // المعلمين
                    'view-teachers', 'create-teachers', 'edit-teachers', 'delete-teachers',
                    'import-teachers', 'export-teachers',
                    // الطلاب
                    'view-students', 'create-students', 'edit-students', 'delete-students',
                    'import-students', 'export-students', 'change-student-status',
                    // الحضور والغياب
                    'view-attendances', 'mark-attendance', 'edit-attendance',
                    'view-attendance-reports', 'view-attendance-statistics', 'manage-excuses',
                    // التقارير
                    'view-reports', 'export-reports', 'print-reports',
                ],
            ],
            [
                'name' => 'teacher',
                'name_ar' => 'معلم',
                'description' => 'صلاحيات المعلم الأساسية',
                'permissions' => [
                    'view-dashboard',
                    // عرض فقط
                    'view-classes', 'view-class-stats',
                    'view-subjects',
                    'view-students',
                    // الحضور والغياب
                    'view-attendances', 'mark-attendance',
                    'view-attendance-reports',
                    // التقارير
                    'view-reports',
                ],
            ],
            [
                'name' => 'class-teacher',
                'name_ar' => 'رائد فصل',
                'description' => 'صلاحيات رائد الفصل',
                'permissions' => [
                    'view-dashboard',
                    // الفصول والطلاب
                    'view-classes', 'view-class-stats',
                    'view-subjects',
                    'view-students', 'edit-students',
                    // الحضور والغياب
                    'view-attendances', 'mark-attendance', 'edit-attendance',
                    'view-attendance-reports', 'view-attendance-statistics', 'manage-excuses',
                    // التقارير
                    'view-reports', 'export-reports', 'print-reports',
                ],
            ],
            [
                'name' => 'receptionist',
                'name_ar' => 'موظف استقبال',
                'description' => 'صلاحيات موظف الاستقبال',
                'permissions' => [
                    'view-dashboard',
                    // الطلاب
                    'view-students', 'create-students', 'edit-students',
                    // الحضور والغياب
                    'view-attendances', 'mark-attendance',
                    'view-attendance-reports',
                    // عرض فقط
                    'view-classes',
                    'view-teachers',
                ],
            ],
            [
                'name' => 'data-entry',
                'name_ar' => 'مدخل بيانات',
                'description' => 'إدخال البيانات فقط',
                'permissions' => [
                    'view-dashboard',
                    // الطلاب
                    'view-students', 'create-students', 'edit-students',
                    'import-students',
                    // المعلمين
                    'view-teachers', 'create-teachers', 'edit-teachers',
                    'import-teachers',
                    // عرض فقط
                    'view-classes',
                    'view-subjects',
                ],
            ],
            [
                'name' => 'viewer',
                'name_ar' => 'مشاهد',
                'description' => 'عرض البيانات فقط بدون تعديل',
                'permissions' => [
                    'view-dashboard',
                    'view-schools', 'view-school-stats',
                    'view-stages',
                    'view-grades',
                    'view-classes', 'view-class-stats',
                    'view-subjects',
                    'view-teachers',
                    'view-students',
                    'view-attendances',
                    'view-attendance-reports', 'view-attendance-statistics',
                    'view-reports',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            // إنشاء الدور
            $roleId = DB::table('roles')->insertGetId([
                'name' => $roleData['name'],
                'name_ar' => $roleData['name_ar'],
                'description' => $roleData['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ربط الصلاحيات بالدور
            if (is_array($roleData['permissions'])) {
                // إذا كانت مصفوفة من أسماء الصلاحيات، نحولها إلى IDs
                if (!is_numeric($roleData['permissions'][0])) {
                    $permissionIds = DB::table('permissions')
                        ->whereIn('name', $roleData['permissions'])
                        ->pluck('id')
                        ->toArray();
                } else {
                    $permissionIds = $roleData['permissions'];
                }

                foreach ($permissionIds as $permissionId) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $this->command->info("✅ تم إنشاء دور: {$roleData['name_ar']}");
        }
    }
}