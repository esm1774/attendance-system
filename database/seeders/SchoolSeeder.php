<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Al-Noor International School',
                'name_ar' => 'مدرسة النور العالمية',
                'code' => 'SCH-001',
                'address' => 'شارع الملك فهد، الرياض',
                'phone' => '+966112345678',
                'email' => 'info@alnoor.edu.sa',
                'principal_name' => 'Dr. Ahmed Al-Mansour',
                'principal_name_ar' => 'د. أحمد المنصور',
                'established_year' => 1995,
                'description' => 'مدرسة النور العالمية تقدم تعليماً متميزاً يواكب متطلبات العصر',
                'is_active' => true,
            ],
        ];

        foreach ($schools as $school) {
            School::create($school);
        }

        $this->command->info('✅ تم إنشاء بيانات المدرسة بنجاح');
    }
}