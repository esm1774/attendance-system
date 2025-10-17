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
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('subject_grade_id')->constrained()->onDelete('cascade');
            $table->decimal('grade', 5, 2)->default(0);
            $table->integer('academic_year');
            $table->string('semester'); // first, second, final
            $table->text('notes')->nullable();
            $table->foreignId('graded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // فهارس بأسماء مختصرة
            $table->index('student_id', 'student_grades_student_idx');
            $table->index('subject_id', 'student_grades_subject_idx');
            $table->index('academic_year', 'student_grades_year_idx');
            $table->index('semester', 'student_grades_semester_idx');
            $table->index(['student_id', 'subject_id', 'academic_year', 'semester'], 'student_grades_main_idx');
            
            // منع التكرار
            $table->unique(['student_id', 'subject_id', 'subject_grade_id', 'academic_year', 'semester'], 'student_grades_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};