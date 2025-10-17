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
        Schema::table('users', function (Blueprint $table) {
            // حقول خاصة بالمعلمين
            $table->string('teacher_id')->nullable()->unique(); // الرقم الوظيفي
            $table->string('specialization')->nullable(); // التخصص
            $table->string('qualification')->nullable(); // المؤهل العلمي
            $table->integer('years_of_experience')->nullable()->default(0); // سنوات الخبرة
            $table->date('hire_date')->nullable(); // تاريخ التعيين
            $table->enum('employment_type', ['full_time', 'part_time', 'contract'])->nullable(); // نوع التعيين
            $table->decimal('salary', 10, 2)->nullable(); // الراتب
            $table->text('notes')->nullable(); // ملاحظات إضافية
            
            // فهارس لتحسين الأداء
            $table->index('teacher_id');
            $table->index('specialization');
            $table->index('employment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'teacher_id',
                'specialization',
                'qualification',
                'years_of_experience',
                'hire_date',
                'employment_type',
                'salary',
                'notes'
            ]);
        });
    }
};