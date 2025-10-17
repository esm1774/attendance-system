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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // الاسم بالإنجليزية (Mathematics)
            $table->string('name_ar'); // الاسم بالعربية (الرياضيات)
            $table->string('code')->unique(); // رمز المادة (MATH-001)
            $table->text('description')->nullable(); // وصف المادة
            $table->enum('type', ['mandatory', 'elective'])->default('mandatory'); // إجباري/اختياري
            $table->boolean('is_active')->default(true); // حالة المادة
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index('code');
            $table->index('type');
            $table->index('is_active');
            $table->index(['is_active', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};