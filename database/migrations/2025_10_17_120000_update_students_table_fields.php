<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // إضافة الحقول الجديدة فقط إذا لم تكن موجودة
            if (!Schema::hasColumn('students', 'full_name')) {
                $table->string('full_name')->after('student_id');
            }
            if (!Schema::hasColumn('students', 'national_id')) {
                $table->string('national_id')->unique()->nullable()->after('full_name');
            }

            // حذف الحقول القديمة فقط إذا كانت موجودة
            $columnsToDrop = [];
            
            if (Schema::hasColumn('students', 'first_name')) {
                $columnsToDrop[] = 'first_name';
            }
            if (Schema::hasColumn('students', 'middle_name')) {
                $columnsToDrop[] = 'middle_name';
            }
            if (Schema::hasColumn('students', 'last_name')) {
                $columnsToDrop[] = 'last_name';
            }
            if (Schema::hasColumn('students', 'first_name_ar')) {
                $columnsToDrop[] = 'first_name_ar';
            }
            if (Schema::hasColumn('students', 'middle_name_ar')) {
                $columnsToDrop[] = 'middle_name_ar';
            }
            if (Schema::hasColumn('students', 'last_name_ar')) {
                $columnsToDrop[] = 'last_name_ar';
            }
            if (Schema::hasColumn('students', 'birth_place')) {
                $columnsToDrop[] = 'birth_place';
            }
            if (Schema::hasColumn('students', 'religion')) {
                $columnsToDrop[] = 'religion';
            }
            if (Schema::hasColumn('students', 'address')) {
                $columnsToDrop[] = 'address';
            }
            if (Schema::hasColumn('students', 'emergency_phone')) {
                $columnsToDrop[] = 'emergency_phone';
            }
            if (Schema::hasColumn('students', 'medical_notes')) {
                $columnsToDrop[] = 'medical_notes';
            }
            if (Schema::hasColumn('students', 'blood_type')) {
                $columnsToDrop[] = 'blood_type';
            }
            if (Schema::hasColumn('students', 'allergies')) {
                $columnsToDrop[] = 'allergies';
            }
            if (Schema::hasColumn('students', 'enrollment_type')) {
                $columnsToDrop[] = 'enrollment_type';
            }
            if (Schema::hasColumn('students', 'previous_school')) {
                $columnsToDrop[] = 'previous_school';
            }
            ]);

            // تحديث الفهارس
            if (Schema::hasIndex('students', 'students_national_id_index')) {
                $table->dropIndex(['national_id']);
            }
            if (!Schema::hasIndex('students', 'students_national_id_index')) {
                $table->index('national_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // استعادة الحقول القديمة
            $table->string('first_name')->after('student_id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_name');
            $table->string('first_name_ar')->after('last_name');
            $table->string('middle_name_ar')->nullable()->after('first_name_ar');
            $table->string('last_name_ar')->after('middle_name_ar');
            $table->string('national_id')->unique()->nullable()->after('last_name_ar');
            $table->string('birth_place')->nullable()->after('birth_date');
            $table->string('religion')->default('مسلم')->after('nationality');
            $table->text('address')->nullable()->after('religion');
            $table->string('emergency_phone')->nullable()->after('guardian_email');
            $table->text('medical_notes')->nullable()->after('emergency_phone');
            $table->string('blood_type')->nullable()->after('medical_notes');
            $table->text('allergies')->nullable()->after('blood_type');
            $table->string('enrollment_type')->default('new')->after('enrollment_date');
            $table->string('previous_school')->nullable()->after('enrollment_type');

            // حذف الحقول الجديدة
            if (Schema::hasColumn('students', 'full_name')) {
                $table->dropColumn('full_name');
            }
            if (Schema::hasColumn('students', 'national_id')) {
                $table->dropColumn('national_id');
            }

            // تحديث الفهارس
            if (Schema::hasIndex('students', 'students_national_id_index')) {
                $table->dropIndex(['national_id']);
            }
            if (!Schema::hasIndex('students', 'students_national_id_index')) {
                $table->index('national_id');
            }
        });
    }
};
