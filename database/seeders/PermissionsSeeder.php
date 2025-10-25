<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تعطيل foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // مسح الصلاحيات القديمة
        DB::table('permissions')->truncate();
        
        // تفعيل foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permissions = [
            // ==================== لوحة التحكم ====================
            [
                'name' => 'view-dashboard',
                'name_ar' => 'عرض لوحة التحكم',
                'description' => 'القدرة على عرض لوحة التحكم والإحصائيات',
                'group' => 'dashboard',
            ],

            // ==================== إدارة المستخدمين ====================
            [
                'name' => 'view-users',
                'name_ar' => 'عرض المستخدمين',
                'description' => 'القدرة على عرض قائمة المستخدمين',
                'group' => 'users',
            ],
            [
                'name' => 'create-users',
                'name_ar' => 'إضافة مستخدم',
                'description' => 'القدرة على إضافة مستخدم جديد',
                'group' => 'users',
            ],
            [
                'name' => 'edit-users',
                'name_ar' => 'تعديل مستخدم',
                'description' => 'القدرة على تعديل بيانات المستخدم',
                'group' => 'users',
            ],
            [
                'name' => 'delete-users',
                'name_ar' => 'حذف مستخدم',
                'description' => 'القدرة على حذف مستخدم',
                'group' => 'users',
            ],
            [
                'name' => 'manage-user-permissions',
                'name_ar' => 'إدارة صلاحيات المستخدمين',
                'description' => 'القدرة على تعديل صلاحيات المستخدمين',
                'group' => 'users',
            ],

            // ==================== إدارة الأدوار ====================
            [
                'name' => 'view-roles',
                'name_ar' => 'عرض الأدوار',
                'description' => 'القدرة على عرض قائمة الأدوار',
                'group' => 'roles',
            ],
            [
                'name' => 'create-roles',
                'name_ar' => 'إضافة دور',
                'description' => 'القدرة على إضافة دور جديد',
                'group' => 'roles',
            ],
            [
                'name' => 'edit-roles',
                'name_ar' => 'تعديل دور',
                'description' => 'القدرة على تعديل الأدوار',
                'group' => 'roles',
            ],
            [
                'name' => 'delete-roles',
                'name_ar' => 'حذف دور',
                'description' => 'القدرة على حذف دور',
                'group' => 'roles',
            ],

            // ==================== إدارة المدارس ====================
            [
                'name' => 'view-schools',
                'name_ar' => 'عرض المدارس',
                'description' => 'القدرة على عرض قائمة المدارس',
                'group' => 'schools',
            ],
            [
                'name' => 'create-schools',
                'name_ar' => 'إضافة مدرسة',
                'description' => 'القدرة على إضافة مدرسة جديدة',
                'group' => 'schools',
            ],
            [
                'name' => 'edit-schools',
                'name_ar' => 'تعديل مدرسة',
                'description' => 'القدرة على تعديل بيانات المدرسة',
                'group' => 'schools',
            ],
            [
                'name' => 'delete-schools',
                'name_ar' => 'حذف مدرسة',
                'description' => 'القدرة على حذف مدرسة',
                'group' => 'schools',
            ],
            [
                'name' => 'view-school-stats',
                'name_ar' => 'عرض إحصائيات المدرسة',
                'description' => 'القدرة على عرض إحصائيات المدرسة',
                'group' => 'schools',
            ],

            // ==================== إدارة المراحل الدراسية ====================
            [
                'name' => 'view-stages',
                'name_ar' => 'عرض المراحل',
                'description' => 'القدرة على عرض قائمة المراحل الدراسية',
                'group' => 'stages',
            ],
            [
                'name' => 'create-stages',
                'name_ar' => 'إضافة مرحلة',
                'description' => 'القدرة على إضافة مرحلة دراسية جديدة',
                'group' => 'stages',
            ],
            [
                'name' => 'edit-stages',
                'name_ar' => 'تعديل مرحلة',
                'description' => 'القدرة على تعديل بيانات المرحلة',
                'group' => 'stages',
            ],
            [
                'name' => 'delete-stages',
                'name_ar' => 'حذف مرحلة',
                'description' => 'القدرة على حذف مرحلة',
                'group' => 'stages',
            ],

            // ==================== إدارة الصفوف الدراسية ====================
            [
                'name' => 'view-grades',
                'name_ar' => 'عرض الصفوف',
                'description' => 'القدرة على عرض قائمة الصفوف الدراسية',
                'group' => 'grades',
            ],
            [
                'name' => 'create-grades',
                'name_ar' => 'إضافة صف',
                'description' => 'القدرة على إضافة صف دراسي جديد',
                'group' => 'grades',
            ],
            [
                'name' => 'edit-grades',
                'name_ar' => 'تعديل صف',
                'description' => 'القدرة على تعديل بيانات الصف',
                'group' => 'grades',
            ],
            [
                'name' => 'delete-grades',
                'name_ar' => 'حذف صف',
                'description' => 'القدرة على حذف صف',
                'group' => 'grades',
            ],
            [
                'name' => 'manage-grade-subjects',
                'name_ar' => 'إدارة مواد الصف',
                'description' => 'القدرة على تحديد المواد لكل صف دراسي',
                'group' => 'grades',
            ],

            // ==================== إدارة الفصول ====================
            [
                'name' => 'view-classes',
                'name_ar' => 'عرض الفصول',
                'description' => 'القدرة على عرض قائمة الفصول',
                'group' => 'classes',
            ],
            [
                'name' => 'create-classes',
                'name_ar' => 'إضافة فصل',
                'description' => 'القدرة على إضافة فصل جديد',
                'group' => 'classes',
            ],
            [
                'name' => 'edit-classes',
                'name_ar' => 'تعديل فصل',
                'description' => 'القدرة على تعديل بيانات الفصل',
                'group' => 'classes',
            ],
            [
                'name' => 'delete-classes',
                'name_ar' => 'حذف فصل',
                'description' => 'القدرة على حذف فصل',
                'group' => 'classes',
            ],
            [
                'name' => 'view-class-stats',
                'name_ar' => 'عرض إحصائيات الفصل',
                'description' => 'القدرة على عرض إحصائيات الفصل',
                'group' => 'classes',
            ],

            // ==================== إدارة المواد الدراسية ====================
            [
                'name' => 'view-subjects',
                'name_ar' => 'عرض المواد',
                'description' => 'القدرة على عرض قائمة المواد الدراسية',
                'group' => 'subjects',
            ],
            [
                'name' => 'create-subjects',
                'name_ar' => 'إضافة مادة',
                'description' => 'القدرة على إضافة مادة دراسية جديدة',
                'group' => 'subjects',
            ],
            [
                'name' => 'edit-subjects',
                'name_ar' => 'تعديل مادة',
                'description' => 'القدرة على تعديل بيانات المادة',
                'group' => 'subjects',
            ],
            [
                'name' => 'delete-subjects',
                'name_ar' => 'حذف مادة',
                'description' => 'القدرة على حذف مادة',
                'group' => 'subjects',
            ],

            // ==================== إدارة المعلمين ====================
            [
                'name' => 'view-teachers',
                'name_ar' => 'عرض المعلمين',
                'description' => 'القدرة على عرض قائمة المعلمين',
                'group' => 'teachers',
            ],
            [
                'name' => 'create-teachers',
                'name_ar' => 'إضافة معلم',
                'description' => 'القدرة على إضافة معلم جديد',
                'group' => 'teachers',
            ],
            [
                'name' => 'edit-teachers',
                'name_ar' => 'تعديل معلم',
                'description' => 'القدرة على تعديل بيانات المعلم',
                'group' => 'teachers',
            ],
            [
                'name' => 'delete-teachers',
                'name_ar' => 'حذف معلم',
                'description' => 'القدرة على حذف معلم',
                'group' => 'teachers',
            ],
            [
                'name' => 'import-teachers',
                'name_ar' => 'استيراد معلمين',
                'description' => 'القدرة على استيراد المعلمين من Excel',
                'group' => 'teachers',
            ],
            [
                'name' => 'export-teachers',
                'name_ar' => 'تصدير معلمين',
                'description' => 'القدرة على تصدير بيانات المعلمين',
                'group' => 'teachers',
            ],

            // ==================== إدارة الطلاب ====================
            [
                'name' => 'view-students',
                'name_ar' => 'عرض الطلاب',
                'description' => 'القدرة على عرض قائمة الطلاب',
                'group' => 'students',
            ],
            [
                'name' => 'create-students',
                'name_ar' => 'إضافة طالب',
                'description' => 'القدرة على إضافة طالب جديد',
                'group' => 'students',
            ],
            [
                'name' => 'edit-students',
                'name_ar' => 'تعديل طالب',
                'description' => 'القدرة على تعديل بيانات الطالب',
                'group' => 'students',
            ],
            [
                'name' => 'delete-students',
                'name_ar' => 'حذف طالب',
                'description' => 'القدرة على حذف طالب',
                'group' => 'students',
            ],
            [
                'name' => 'import-students',
                'name_ar' => 'استيراد طلاب',
                'description' => 'القدرة على استيراد الطلاب من Excel',
                'group' => 'students',
            ],
            [
                'name' => 'export-students',
                'name_ar' => 'تصدير طلاب',
                'description' => 'القدرة على تصدير بيانات الطلاب',
                'group' => 'students',
            ],
            [
                'name' => 'change-student-status',
                'name_ar' => 'تغيير حالة الطالب',
                'description' => 'القدرة على تغيير حالة الطالب (نشط، منقول، متخرج، منسحب)',
                'group' => 'students',
            ],

            // ==================== نظام الحضور والغياب ====================
            [
                'name' => 'view-attendances',
                'name_ar' => 'عرض الحضور والغياب',
                'description' => 'القدرة على عرض سجلات الحضور والغياب',
                'group' => 'attendances',
            ],
            [
                'name' => 'mark-attendance',
                'name_ar' => 'تسجيل الحضور',
                'description' => 'القدرة على تسجيل حضور وغياب الطلاب',
                'group' => 'attendances',
            ],
            [
                'name' => 'edit-attendance',
                'name_ar' => 'تعديل الحضور',
                'description' => 'القدرة على تعديل سجلات الحضور',
                'group' => 'attendances',
            ],
            [
                'name' => 'view-attendance-reports',
                'name_ar' => 'عرض تقارير الحضور',
                'description' => 'القدرة على عرض تقارير الحضور والغياب',
                'group' => 'attendances',
            ],
            [
                'name' => 'view-attendance-statistics',
                'name_ar' => 'عرض إحصائيات الحضور',
                'description' => 'القدرة على عرض إحصائيات الحضور والغياب',
                'group' => 'attendances',
            ],
            [
                'name' => 'manage-excuses',
                'name_ar' => 'إدارة الأعذار',
                'description' => 'القدرة على قبول أو رفض أعذار الغياب',
                'group' => 'attendances',
            ],

            // ==================== التقارير ====================
            [
                'name' => 'view-reports',
                'name_ar' => 'عرض التقارير',
                'description' => 'القدرة على عرض جميع التقارير',
                'group' => 'reports',
            ],
            [
                'name' => 'export-reports',
                'name_ar' => 'تصدير التقارير',
                'description' => 'القدرة على تصدير التقارير إلى Excel/PDF',
                'group' => 'reports',
            ],
            [
                'name' => 'print-reports',
                'name_ar' => 'طباعة التقارير',
                'description' => 'القدرة على طباعة التقارير',
                'group' => 'reports',
            ],

            // ==================== الإعدادات ====================
            [
                'name' => 'manage-settings',
                'name_ar' => 'إدارة الإعدادات',
                'description' => 'القدرة على تعديل إعدادات النظام',
                'group' => 'settings',
            ],
            [
                'name' => 'view-logs',
                'name_ar' => 'عرض السجلات',
                'description' => 'القدرة على عرض سجلات النظام',
                'group' => 'settings',
            ],
            [
                'name' => 'manage-backups',
                'name_ar' => 'إدارة النسخ الاحتياطي',
                'description' => 'القدرة على إنشاء واستعادة النسخ الاحتياطية',
                'group' => 'settings',
            ],
        ];

        // إدراج الصلاحيات
        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission['name'],
                'name_ar' => $permission['name_ar'],
                'description' => $permission['description'],
                'group' => $permission['group'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ تم إضافة ' . count($permissions) . ' صلاحية بنجاح');
    }
}