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
        Schema::create('subject_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('grade_type'); // participation, projects, homework, etc.
            $table->string('grade_type_ar'); // مشاركة، مشاريع، واجبات، إلخ
            $table->decimal('min_grade', 5, 2)->default(0);
            $table->decimal('max_grade', 5, 2);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // فهارس
            $table->index('subject_id');
            $table->index('is_active');
            $table->index(['subject_id', 'is_active']);
            
            // منع التكرار في نوع الدرجة لنفس المادة
            $table->unique(['subject_id', 'grade_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_grades');
    }
};