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
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Primary, Intermediate, Secondary
            $table->string('name_ar'); // ابتدائي، متوسطة، ثانوي
            $table->string('code')->nullable(); // رمز المرحلة
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // ترتيب العرض
            $table->integer('min_age')->nullable(); // الحد الأدنى للعمر
            $table->integer('max_age')->nullable(); // الحد الأقصى للعمر
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index('school_id');
            $table->index('is_active');
            $table->index(['school_id', 'is_active']);
            $table->index('order');
            
            // منع التكرار في اسم المرحلة لنفس المدرسة
            $table->unique(['school_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};