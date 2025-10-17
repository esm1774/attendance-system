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
        Schema::create('excuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('attendance_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('absence_date');
            $table->enum('type', ['sick', 'personal', 'family', 'other'])->default('sick');
            $table->text('reason');
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // فهارس
            $table->index('student_id');
            $table->index('absence_date');
            $table->index('type');
            $table->index('status');
            $table->index(['student_id', 'absence_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excuses');
    }
};