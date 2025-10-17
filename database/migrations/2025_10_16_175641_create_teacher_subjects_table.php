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
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المعلم
            $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // المادة
            $table->boolean('is_primary')->default(false); // هل المادة أساسية للمعلم؟
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index('user_id');
            $table->index('subject_id');
            $table->index(['user_id', 'subject_id']);
            $table->index('is_primary');
            
            // منع التكرار - معلم واحد لا يمكنه تدريس نفس المادة مرتين
            $table->unique(['user_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subjects');
    }
};