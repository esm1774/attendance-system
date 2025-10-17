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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Grade 1, Grade 2, etc.
            $table->string('name_ar'); // الصف الأول، الصف الثاني، إلخ
            $table->string('code')->nullable(); // رمز الصف
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // ترتيب العرض
            $table->integer('level')->default(1); // مستوى الصف (1, 2, 3, ...)
            $table->decimal('min_grade', 5, 2)->default(0); // الحد الأدنى للنجاح
            $table->decimal('max_grade', 5, 2)->default(100); // الحد الأقصى للدرجة
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index('stage_id');
            $table->index('is_active');
            $table->index(['stage_id', 'is_active']);
            $table->index('order');
            $table->index('level');
            
            // منع التكرار في اسم الصف لنفس المرحلة
            $table->unique(['stage_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};