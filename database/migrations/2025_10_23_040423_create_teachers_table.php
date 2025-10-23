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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            
            // المعلومات الشخصية
            $table->string('name'); // الاسم الكامل
            $table->string('national_id')->unique(); // رقم الهوية
            $table->date('birth_date'); // تاريخ الميلاد
            $table->enum('gender', ['male', 'female']); // الجنس
            $table->string('nationality')->default('سعودي'); // الجنسية
            $table->string('photo')->nullable(); // الصورة الشخصية
            
            // معلومات التواصل
            $table->string('phone'); // رقم الجوال
            $table->string('email')->unique(); // البريد الإلكتروني
            $table->text('address')->nullable(); // العنوان
            
            // المعلومات الوظيفية
            $table->string('employee_number')->unique(); // الرقم الوظيفي
            $table->string('specialization'); // التخصص
            $table->string('qualification'); // المؤهل العلمي
            $table->date('hire_date'); // تاريخ التعيين
            $table->enum('contract_type', ['permanent', 'temporary', 'substitute'])->default('permanent'); // نوع العقد
            $table->decimal('salary', 10, 2)->nullable(); // الراتب الأساسي
            $table->string('department')->nullable(); // القسم/الشعبة
            
            // العلاقة مع المدرسة
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            
            // الحالة
            $table->enum('status', ['active', 'on_leave', 'retired', 'transferred'])->default('active');
            $table->boolean('is_active')->default(true);
            
            // ملاحظات
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // للحذف الناعم
            
            // الفهارس
            $table->index('school_id');
            $table->index('status');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};