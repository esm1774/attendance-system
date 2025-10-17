<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Stage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على المدرسة الأولى
        $school = School::first();

        if (!$school) {
            $this->command->info('⚠️  لا توجد مدارس، سيتم إنشاء مدرسة أولاً...');
            $school = School::create([
                'name' => 'Default School',
                'name_ar' => 'المدرسة الافتراضية',
                'code' => 'SCH-DEF',
                'is_active' => true,
            ]);
        }

        $stages = [
            [
                'school_id' => $school->id,
                'name' => 'Primary',
                'name_ar' => 'المرحلة الابتدائية',
                'code' => 'PRI',
                'description' => 'المرحلة الابتدائية من الصف الأول إلى السادس',
                'order' => 1,
                'min_age' => 6,
                'max_age' => 12,
                'is_active' => true,
            ],
            [
                'school_id' => $school->id,
                'name' => 'Intermediate',
                'name_ar' => 'المرحلة المتوسطة',
                'code' => 'INT',
                'description' => 'المرحلة المتوسطة من الصف الأول إلى الثالث',
                'order' => 2,
                'min_age' => 12,
                'max_age' => 15,
                'is_active' => true,
            ],
            [
                'school_id' => $school->id,
                'name' => 'Secondary',
                'name_ar' => 'المرحلة الثانوية',
                'code' => 'SEC',
                'description' => 'المرحلة الثانوية من الصف الأول إلى الثالث',
                'order' => 3,
                'min_age' => 15,
                'max_age' => 18,
                'is_active' => true,
            ],
        ];

        foreach ($stages as $stage) {
            Stage::create($stage);
        }

        $this->command->info('✅ تم إنشاء المراحل الدراسية بنجاح: ابتدائي، متوسطة، ثانوي');
    }
}