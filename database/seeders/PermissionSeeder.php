<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // صلاحيات لوحة التحكم
            [
                'name' => 'view_dashboard',
                'name_ar' => 'عرض لوحة التحكم',
                'group' => 'dashboard',
                'description' => 'القدرة على عرض لوحة التحكم الرئيسية',
            ],
            [
                'name' => 'view_statistics',
                'name_ar' => 'عرض الإحصائيات',
                'group' => 'dashboard',
                'description' => 'القدرة على عرض الإحصائيات والتقارير',
            ],

            // صلاحيات إدارة المستخدمين
            [
                'name' => 'manage_users',
                'name_ar' => 'إدارة المستخدمين',
                'group' => 'users',
                'description' => 'القدرة على إضافة وتعديل وحذف المستخدمين',
            ],
            [
                'name' => 'view_users',
                'name_ar' => 'عرض المستخدمين',
                'group' => 'users',
                'description' => 'القدرة على عرض قائمة المستخدمين',
            ],
            [
                'name' => 'create_users',
                'name_ar' => 'إنشاء مستخدمين',
                'group' => 'users',
                'description' => 'القدرة على إنشاء مستخدمين جدد',
            ],
            [
                'name' => 'edit_users',
                'name_ar' => 'تعديل المستخدمين',
                'group' => 'users',
                'description' => 'القدرة على تعديل بيانات المستخدمين',
            ],
            [
                'name' => 'delete_users',
                'name_ar' => 'حذف المستخدمين',
                'group' => 'users',
                'description' => 'القدرة على حذف المستخدمين',
            ],

            // صلاحيات إدارة الطلاب
            [
                'name' => 'manage_students',
                'name_ar' => 'إدارة الطلاب',
                'group' => 'students',
                'description' => 'القدرة على إدارة الطلاب',
            ],
            [
                'name' => 'view_students',
                'name_ar' => 'عرض الطلاب',
                'group' => 'students',
                'description' => 'القدرة على عرض قائمة الطلاب',
            ],
            [
                'name' => 'create_students',
                'name_ar' => 'إضافة طلاب',
                'group' => 'students',
                'description' => 'القدرة على إضافة طلاب جدد',
            ],
            [
                'name' => 'edit_students',
                'name_ar' => 'تعديل الطلاب',
                'group' => 'students',
                'description' => 'القدرة على تعديل بيانات الطلاب',
            ],
            [
                'name' => 'delete_students',
                'name_ar' => 'حذف الطلاب',
                'group' => 'students',
                'description' => 'القدرة على حذف الطلاب',
            ],

            // صلاحيات الحضور والغياب
            [
                'name' => 'manage_attendance',
                'name_ar' => 'إدارة الحضور',
                'group' => 'attendance',
                'description' => 'القدرة على إدارة الحضور والغياب',
            ],
            [
                'name' => 'take_attendance',
                'name_ar' => 'تسجيل الحضور',
                'group' => 'attendance',
                'description' => 'القدرة على تسجيل الحضور والغياب',
            ],
            [
                'name' => 'view_attendance',
                'name_ar' => 'عرض الحضور',
                'group' => 'attendance',
                'description' => 'القدرة على عرض سجلات الحضور',
            ],
            [
                'name' => 'manage_excuses',
                'name_ar' => 'إدارة الأعذار',
                'group' => 'attendance',
                'description' => 'القدرة على إدارة أعذار الغياب',
            ],

            // صلاحيات التقارير
            [
                'name' => 'view_reports',
                'name_ar' => 'عرض التقارير',
                'group' => 'reports',
                'description' => 'القدرة على عرض التقارير',
            ],
            [
                'name' => 'export_reports',
                'name_ar' => 'تصدير التقارير',
                'group' => 'reports',
                'description' => 'القدرة على تصدير التقارير',
            ],

            // صلاحيات الإعدادات
            [
                'name' => 'manage_settings',
                'name_ar' => 'إدارة الإعدادات',
                'group' => 'settings',
                'description' => 'القدرة على تعديل إعدادات النظام',
            ],
            [
                'name' => 'manage_roles',
                'name_ar' => 'إدارة الأدوار',
                'group' => 'settings',
                'description' => 'القدرة على إدارة الأدوار والصلاحيات',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info('✅ تم إنشاء الصلاحيات بنجاح: ' . count($permissions) . ' صلاحية');
    }
}