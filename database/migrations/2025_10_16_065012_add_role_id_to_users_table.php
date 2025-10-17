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
            // إضافة الحقول الجديدة
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            
            // فهرس للحقول المضافة
            $table->index('role_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // حذف المفاتيح الخارجية أولاً
            $table->dropForeign(['role_id']);
            
            // ثم حذف الحقول
            $table->dropColumn(['role_id', 'phone', 'address', 'is_active']);
        });
    }
};