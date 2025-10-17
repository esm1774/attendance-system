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
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->integer('weekly_hours')->default(5);
            $table->integer('academic_year')->default(2024);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // فهارس
            $table->index(['school_class_id', 'subject_id']);
            $table->index('academic_year');
            $table->index('is_active');
            
            // منع التكرار
            $table->unique(['school_class_id', 'subject_id', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subjects');
    }
};