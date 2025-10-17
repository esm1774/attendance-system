<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Mathematics',
                'name_ar' => 'الرياضيات',
                'code' => 'MATH-001',
                'description' => 'مادة الرياضيات تشمل الجبر والهندسة والإحصاء',
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Arabic Language',
                'name_ar' => 'اللغة العربية',
                'code' => 'ARAB-001',
                'description' => 'مادة اللغة العربية تشمل النحو والأدب والبلاغة',
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Science',
                'name_ar' => 'العلوم',
                'code' => 'SCI-001',
                'description' => 'مادة العلوم تشمل الفيزياء والكيمياء والأحياء',
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'English Language',
                'name_ar' => 'اللغة الإنجليزية',
                'code' => 'ENG-001',
                'description' => 'مادة اللغة الإنجليزية تشمل القراءة والمحادثة والقواعد',
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Islamic Education',
                'name_ar' => 'التربية الإسلامية',
                'code' => 'ISL-001',
                'description' => 'مادة التربية الإسلامية تشمل القرآن الكريم والحديث والفقه',
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Social Studies',
                'name_ar' => 'الدراسات الاجتماعية',
                'code' => 'SOC-001',
                'description' => 'مادة الدراسات الاجتماعية تشمل التاريخ والجغرافيا',
                'type' => 'mandatory',
                'is_active' => true,
            ],
            [
                'name' => 'Computer Science',
                'name_ar' => 'الحاسب الآلي',
                'code' => 'COMP-001',
                'description' => 'مادة الحاسب الآلي تشمل البرمجة والمهارات الرقمية',
                'type' => 'elective',
                'is_active' => true,
            ],
            [
                'name' => 'Art Education',
                'name_ar' => 'التربية الفنية',
                'code' => 'ART-001',
                'description' => 'مادة التربية الفنية تشمل الرسم والأشغال اليدوية',
                'type' => 'elective',
                'is_active' => true,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $this->command->info('✅ تم إنشاء المواد الدراسية بنجاح: ' . count($subjects) . ' مادة');
    }
}