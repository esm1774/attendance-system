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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // School name in English
            $table->string('name_ar'); // اسم المدرسة بالعربية
            $table->string('code')->unique(); // رمز المدرسة
            $table->text('address')->nullable(); // العنوان
            $table->string('phone')->nullable(); // الهاتف
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->string('principal_name')->nullable(); // اسم المدير
            $table->string('principal_name_ar')->nullable(); // اسم المدير بالعربية
            $table->integer('established_year')->nullable(); // سنة التأسيس
            $table->text('description')->nullable(); // وصف المدرسة
            $table->boolean('is_active')->default(true); // حالة المدرسة
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index('code');
            $table->index('is_active');
            $table->index(['is_active', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};