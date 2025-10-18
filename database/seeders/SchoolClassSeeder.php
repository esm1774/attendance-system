<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;
use App\Models\Grade;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التحقق من وجود الفصول
        if (SchoolClass::count() > 0) {
            return;
        }
        
        // الحصول على جميع المراحل
        $grades = Grade::all();
        
        // إنشاء فصول لكل مرحلة
        foreach ($grades as $grade) {
            // إنشاء 3 فصول لكل مرحلة
            for ($i = 1; $i <= 3; $i++) {
                SchoolClass::create([
                    'grade_id' => $grade->id,
                    'name_ar' => 'الفصل ' . $i,
                    'name_en' => 'Class ' . $i,
                    'capacity' => 30,
                    'description' => 'فصل دراسي للمرحلة ' . $grade->name_ar,
                ]);
            }
        }
    }
}
