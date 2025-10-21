<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // إضافة حقل full_name إن لم يكن موجودًا
            if (!Schema::hasColumn('students', 'full_name')) {
                $table->string('full_name')->after('student_id');
            }

            // إضافة حقل national_id بدون تكرار الفهرس
            if (!Schema::hasColumn('students', 'national_id')) {
                $table->string('national_id')->nullable()->after('full_name');
                // نضيف الفهرس بعد التأكد أنه غير موجود
                try {
                    $table->unique('national_id', 'students_national_id_unique');
                } catch (Exception $e) {
                    // تجاهل إذا كان الفهرس موجود مسبقًا
                }
            } else {
                // العمود موجود، فقط نتأكد أن له فهرس فريد إن لم يكن موجودًا
                $existingIndexes = DB::select("SHOW INDEX FROM students WHERE Key_name = 'students_national_id_unique'");
                if (empty($existingIndexes)) {
                    try {
                        $table->unique('national_id', 'students_national_id_unique');
                    } catch (Exception $e) {
                        // تجاهل الخطأ إذا الفهرس موجود
                    }
                }
            }

            // حذف الحقول القديمة إن وجدت
            $oldColumns = [
                'first_name', 'middle_name', 'last_name',
                'first_name_ar', 'middle_name_ar', 'last_name_ar',
                'birth_place', 'religion', 'address',
                'emergency_phone', 'medical_notes', 'blood_type',
                'allergies', 'enrollment_type', 'previous_school'
            ];

            $columnsToDrop = [];
            foreach ($oldColumns as $col) {
                if (Schema::hasColumn('students', $col)) {
                    $columnsToDrop[] = $col;
                }
            }

            if (count($columnsToDrop) > 0) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // حذف الحقول الجديدة
            if (Schema::hasColumn('students', 'full_name')) {
                $table->dropColumn('full_name');
            }
            if (Schema::hasColumn('students', 'national_id')) {
                // حذف الفهرس قبل حذف العمود لتجنب الأخطاء
                try {
                    $table->dropUnique('students_national_id_unique');
                } catch (Exception $e) {
                    // تجاهل
                }
                $table->dropColumn('national_id');
            }

            // إعادة الأعمدة القديمة
            $table->string('first_name')->after('student_id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_name');
            $table->string('first_name_ar')->after('last_name');
            $table->string('middle_name_ar')->nullable()->after('first_name_ar');
            $table->string('last_name_ar')->after('middle_name_ar');
            $table->string('birth_place')->nullable()->after('birth_date');
            $table->string('religion')->default('مسلم')->after('nationality');
            $table->text('address')->nullable()->after('religion');
            $table->string('emergency_phone')->nullable()->after('guardian_email');
            $table->text('medical_notes')->nullable()->after('emergency_phone');
            $table->string('blood_type')->nullable()->after('medical_notes');
            $table->text('allergies')->nullable()->after('blood_type');
            $table->string('enrollment_type')->default('new')->after('enrollment_date');
            $table->string('previous_school')->nullable()->after('enrollment_type');
        });
    }
};
