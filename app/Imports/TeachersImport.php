<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeachersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('🟢 Row Data:', $row);

        // التحقق من أن الأعمدة موجودة وليست فارغة
        if (
            empty($row['alasm_alkaml']) &&
            empty($row['rkm_alhoy_alotny']) &&
            empty($row['rkm_algoal']) &&
            empty($row['albryd_alalktrony'])
        ) {
            return null;
        }

        // إنشاء المعلم
        $teacher = Teacher::create([
            'name'         => $row['alasm_alkaml'] ?? '',
            'national_id'  => $row['rkm_alhoy_alotny'] ?? '',
            'phone'        => $row['rkm_algoal'] ?? '',
            'email'        => $row['albryd_alalktrony'] ?? '',
        ]);

        // إنشاء المستخدم المرتبط بالمعلم
        $user = User::create([
            'name'       => $teacher->name,
            'email'      => $teacher->email,
            'password'   => Hash::make('123456'), // كلمة مرور افتراضية
            'teacher_id' => $teacher->id,
        ]);

        // تعيين صلاحية المعلم (في حال استخدام Spatie)
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('teacher');
        }

        Log::info("✅ تم إنشاء المعلم {$teacher->name} والمستخدم {$user->email}");

        return $teacher;
    }

    public function headingRow(): int
    {
        return 4; // حسب موضع رؤوس الأعمدة في ملف الإكسل
    }
}
