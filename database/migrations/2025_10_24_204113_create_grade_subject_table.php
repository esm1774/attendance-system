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
        Schema::create('grade_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->boolean('is_required')->default(true)->comment('هل المادة إجبارية');
            $table->integer('weekly_hours')->nullable()->comment('عدد الحصص الأسبوعية');
            $table->timestamps();
            
            // منع التكرار
            $table->unique(['grade_id', 'subject_id']);
            
            // الفهارس
            $table->index('grade_id');
            $table->index('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_subject');
    }
};