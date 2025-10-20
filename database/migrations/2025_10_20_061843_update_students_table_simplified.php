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
            // حذف الأعمدة غير المطلوبة
            $columnsToRemove = [
                'student_id',  // سنستخدم national_id بدلاً منه
                'birth_place',
                'address',
                'previous_school',
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // جعل national_id مطلوب وفريد
            if (Schema::hasColumn('students', 'national_id')) {
                $table->string('national_id', 20)->unique()->change();
            }
            
            // التأكد من وجود جميع الأعمدة المطلوبة
            if (!Schema::hasColumn('students', 'full_name')) {
                $table->string('full_name')->after('class_id');
            }
            
            if (!Schema::hasColumn('students', 'national_id')) {
                $table->string('national_id', 20)->unique()->after('full_name');
            }
            
            if (!Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('national_id');
            }
            
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            }
            
            if (!Schema::hasColumn('students', 'nationality')) {
                $table->string('nationality', 100)->default('سعودي')->after('gender');
            }
            
            if (!Schema::hasColumn('students', 'religion')) {
                $table->string('religion', 100)->nullable()->after('nationality');
            }
            
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone', 20)->nullable()->after('religion');
            }
            
            if (!Schema::hasColumn('students', 'email')) {
                $table->string('email')->nullable()->unique()->after('phone');
            }
            
            // بيانات ولي الأمر
            if (!Schema::hasColumn('students', 'guardian_name')) {
                $table->string('guardian_name')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('students', 'guardian_relation')) {
                $table->string('guardian_relation', 100)->nullable()->after('guardian_name');
            }
            
            if (!Schema::hasColumn('students', 'guardian_phone')) {
                $table->string('guardian_phone', 20)->nullable()->after('guardian_relation');
            }
            
            if (!Schema::hasColumn('students', 'guardian_email')) {
                $table->string('guardian_email')->nullable()->after('guardian_phone');
            }
            
            if (!Schema::hasColumn('students', 'emergency_phone')) {
                $table->string('emergency_phone', 20)->nullable()->after('guardian_email');
            }
            
            // المعلومات الطبية
            if (!Schema::hasColumn('students', 'blood_type')) {
                $table->string('blood_type', 10)->nullable()->after('emergency_phone');
            }
            
            if (!Schema::hasColumn('students', 'allergies')) {
                $table->text('allergies')->nullable()->after('blood_type');
            }
            
            if (!Schema::hasColumn('students', 'medical_notes')) {
                $table->text('medical_notes')->nullable()->after('allergies');
            }
            
            // معلومات التسجيل
            if (!Schema::hasColumn('students', 'enrollment_date')) {
                $table->date('enrollment_date')->nullable()->after('medical_notes');
            }
            
            if (!Schema::hasColumn('students', 'enrollment_type')) {
                $table->enum('enrollment_type', ['new', 'transferred'])->default('new')->after('enrollment_date');
            }
            
            if (!Schema::hasColumn('students', 'status')) {
                $table->enum('status', ['active', 'transferred', 'graduated', 'withdrawn'])->default('active')->after('enrollment_type');
            }
            
            if (!Schema::hasColumn('students', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
            }
            
            if (!Schema::hasColumn('students', 'notes')) {
                $table->text('notes')->nullable()->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // إعادة الأعمدة المحذوفة
            $table->string('student_id', 50)->unique()->after('full_name');
            $table->string('birth_place')->nullable()->after('gender');
            $table->string('address')->nullable()->after('religion');
            $table->string('previous_school')->nullable()->after('enrollment_type');
        });
    }
};