<?php

namespace Database\Seeders;

use App\Models\Stage;
use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على جميع المراحل
        $stages = Stage::all();

        if ($stages->isEmpty()) {
            $this->command->info('⚠️  لا توجد مراحل، سيتم إنشاء مراحل أولاً...');
            return;
        }

        $gradesData = [];

        foreach ($stages as $stage) {
            if ($stage->name_ar == 'المرحلة الابتدائية') {
                $gradesData = array_merge($gradesData, [
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 1',
                        'name_ar' => 'الصف الأول',
                        'code' => 'G1',
                        'description' => 'الصف الأول الابتدائي',
                        'order' => 1,
                        'level' => 1,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 2',
                        'name_ar' => 'الصف الثاني',
                        'code' => 'G2',
                        'description' => 'الصف الثاني الابتدائي',
                        'order' => 2,
                        'level' => 2,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 3',
                        'name_ar' => 'الصف الثالث',
                        'code' => 'G3',
                        'description' => 'الصف الثالث الابتدائي',
                        'order' => 3,
                        'level' => 3,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 4',
                        'name_ar' => 'الصف الرابع',
                        'code' => 'G4',
                        'description' => 'الصف الرابع الابتدائي',
                        'order' => 4,
                        'level' => 4,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 5',
                        'name_ar' => 'الصف الخامس',
                        'code' => 'G5',
                        'description' => 'الصف الخامس الابتدائي',
                        'order' => 5,
                        'level' => 5,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 6',
                        'name_ar' => 'الصف السادس',
                        'code' => 'G6',
                        'description' => 'الصف السادس الابتدائي',
                        'order' => 6,
                        'level' => 6,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                ]);
            } elseif ($stage->name_ar == 'المرحلة المتوسطة') {
                $gradesData = array_merge($gradesData, [
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 7',
                        'name_ar' => 'الصف الأول متوسط',
                        'code' => 'G7',
                        'description' => 'الصف الأول المتوسط',
                        'order' => 1,
                        'level' => 7,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 8',
                        'name_ar' => 'الصف الثاني متوسط',
                        'code' => 'G8',
                        'description' => 'الصف الثاني المتوسط',
                        'order' => 2,
                        'level' => 8,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 9',
                        'name_ar' => 'الصف الثالث متوسط',
                        'code' => 'G9',
                        'description' => 'الصف الثالث المتوسط',
                        'order' => 3,
                        'level' => 9,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                ]);
            } elseif ($stage->name_ar == 'المرحلة الثانوية') {
                $gradesData = array_merge($gradesData, [
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 10',
                        'name_ar' => 'الصف الأول ثانوي',
                        'code' => 'G10',
                        'description' => 'الصف الأول الثانوي',
                        'order' => 1,
                        'level' => 10,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 11',
                        'name_ar' => 'الصف الثاني ثانوي',
                        'code' => 'G11',
                        'description' => 'الصف الثاني الثانوي',
                        'order' => 2,
                        'level' => 11,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                    [
                        'stage_id' => $stage->id,
                        'name' => 'Grade 12',
                        'name_ar' => 'الصف الثالث ثانوي',
                        'code' => 'G12',
                        'description' => 'الصف الثالث الثانوي',
                        'order' => 3,
                        'level' => 12,
                        'min_grade' => 50,
                        'max_grade' => 100,
                        'is_active' => true,
                    ],
                ]);
            }
        }

        foreach ($gradesData as $grade) {
            Grade::create($grade);
        }

        $this->command->info('✅ تم إنشاء الصفوف الدراسية بنجاح: ' . count($gradesData) . ' صف');
    }
}