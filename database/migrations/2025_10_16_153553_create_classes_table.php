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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Class name in English (Grade 1-A)
            $table->string('name_ar'); // اسم الفصل بالعربية (أول أ)
            $table->string('code')->unique(); // رمز الفصل (G1-A)
            $table->integer('capacity')->default(30); // سعة الفصل
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); // مرشد الفصل
            $table->text('description')->nullable(); // وصف الفصل
            $table->string('room_number')->nullable(); // رقم القاعة
            $table->boolean('is_active')->default(true); // حالة الفصل
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index('grade_id');
            $table->index('teacher_id');
            $table->index('code');
            $table->index('is_active');
            $table->index(['grade_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};