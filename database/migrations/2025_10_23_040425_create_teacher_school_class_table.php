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
        Schema::create('teacher_school_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade'); // المادة التي يدرسها في هذا الفصل
            $table->boolean('is_class_teacher')->default(false); // هل هو معلم الفصل (رائد الفصل)
            $table->timestamps();
            
            // منع التكرار
            $table->unique(['teacher_id', 'school_class_id', 'subject_id'],
        'tchr_sch_cls_subj_unique');
            
            // الفهارس
            $table->index('teacher_id');
            $table->index('school_class_id');
            $table->index('subject_id');
            $table->index('is_class_teacher');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_school_class');
    }
};