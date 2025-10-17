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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->string('student_id')->unique(); // الرقم الجامعي/المدرسي
            $table->string('first_name'); // الاسم الأول
            $table->string('middle_name')->nullable(); // الاسم الأوسط
            $table->string('last_name'); // الاسم الأخير
            $table->string('first_name_ar'); // الاسم الأول عربي
            $table->string('middle_name_ar')->nullable(); // الاسم الأوسط عربي
            $table->string('last_name_ar'); // الاسم الأخير عربي
            $table->string('national_id')->unique()->nullable(); // الرقم الوطني
            $table->date('birth_date'); // تاريخ الميلاد
            $table->enum('gender', ['male', 'female']); // الجنس
            $table->string('birth_place')->nullable(); // مكان الميلاد
            $table->string('nationality')->default('سعودي'); // الجنسية
            $table->string('religion')->default('مسلم'); // الديانة
            $table->text('address')->nullable(); // العنوان
            $table->string('phone')->nullable(); // هاتف الطالب
            $table->string('email')->nullable()->unique(); // البريد الإلكتروني
            
            // بيانات ولي الأمر
            $table->string('guardian_name'); // اسم ولي الأمر
            $table->string('guardian_relation'); // صلة القرابة
            $table->string('guardian_phone'); // هاتف ولي الأمر
            $table->string('guardian_email')->nullable(); // بريد ولي الأمر
            $table->string('emergency_phone')->nullable(); // هاتف الطوارئ
            
            // معلومات طبية
            $table->text('medical_notes')->nullable(); // ملاحظات طبية
            $table->string('blood_type')->nullable(); // فصيلة الدم
            $table->text('allergies')->nullable(); // الحساسيات
            
            // معلومات التسجيل
            $table->date('enrollment_date'); // تاريخ التسجيل
            $table->string('enrollment_type')->default('new'); // جديد، منقول
            $table->string('previous_school')->nullable(); // المدرسة السابقة
            
            // الحالة
            $table->boolean('is_active')->default(true); // نشط/غير نشط
            $table->enum('status', ['active', 'transferred', 'graduated', 'withdrawn'])->default('active');
            $table->text('notes')->nullable(); // ملاحظات عامة
            
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index('class_id');
            $table->index('student_id');
            $table->index('national_id');
            $table->index('gender');
            $table->index('is_active');
            $table->index('status');
            $table->index(['class_id', 'is_active']);
            $table->index(['gender', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};