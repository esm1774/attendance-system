<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'name_ar' => 'مدير النظام',
                'description' => 'مدير النظام مع صلاحيات كاملة',
                'is_active' => true,
            ],
            [
                'name' => 'vice_principal',
                'name_ar' => 'الوكيل',
                'description' => 'وكيل المدرسة مع صلاحيات إدارية',
                'is_active' => true,
            ],
            [
                'name' => 'supervisor',
                'name_ar' => 'المراقب',
                'description' => 'مراقب مع صلاحيات متابعة الحضور والغياب',
                'is_active' => true,
            ],
            [
                'name' => 'teacher',
                'name_ar' => 'المعلم',
                'description' => 'معلم مع صلاحيات محدودة',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('✅ تم إنشاء الأدوار بنجاح: مدير النظام، الوكيل، المراقب، المعلم');
    }
}