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
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Class 1A, Class 1B, etc.
            $table->string('name_ar'); // فصل 1أ, فصل 1ب, إلخ
            $table->string('code')->unique(); // رمز الفصل
            $table->integer('capacity')->default(30); // سعة الفصل
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); // مرشد الفصل
            $table->text('description')->nullable(); // وصف الفصل
            $table->boolean('is_active')->default(true); // حالة الفصل
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index('grade_id');
            $table->index('teacher_id');
            $table->index('is_active');
            $table->index(['grade_id', 'is_active']);
            $table->unique(['grade_id', 'name']); // منع تكرار اسم الفصل في نفس الصف
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};