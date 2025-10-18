<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // إذا كان العمود القديم موجوداً، قم بإنشاء عمود الاسم الكامل أولاً
            if (Schema::hasColumn('students', 'first_name') && Schema::hasColumn('students', 'last_name') && !Schema::hasColumn('students', 'full_name')) {
                // إنشاء عمود الاسم الكامل
                $table->string('full_name')->after('class_id');
            }
            
            // الآن قم بتحديث البيانات
            if (Schema::hasColumn('students', 'first_name') && Schema::hasColumn('students', 'last_name') && Schema::hasColumn('students', 'full_name')) {
                // دمج الأسماء في عمود واحد
                DB::statement('UPDATE students SET full_name = CONCAT(first_name, " ", last_name) WHERE full_name IS NULL OR full_name = ""');
            }
            
            // حذف الأعمدة القديمة بعد تحديث البيانات
            if (Schema::hasColumn('students', 'first_name')) {
                $table->dropColumn('first_name');
            }
            if (Schema::hasColumn('students', 'middle_name')) {
                $table->dropColumn('middle_name');
            }
            if (Schema::hasColumn('students', 'last_name')) {
                $table->dropColumn('last_name');
            }
            if (Schema::hasColumn('students', 'first_name_ar')) {
                $table->dropColumn('first_name_ar');
            }
            if (Schema::hasColumn('students', 'middle_name_ar')) {
                $table->dropColumn('middle_name_ar');
            }
            if (Schema::hasColumn('students', 'last_name_ar')) {
                $table->dropColumn('last_name_ar');
            }
            
            // تحديث اسم العمود من identity_number إلى national_id إذا لزم الأمر
            if (Schema::hasColumn('students', 'identity_number')) {
                $table->renameColumn('identity_number', 'national_id');
            }
            
            // تحديث نوع العمود ليكون nullable
            if (Schema::hasColumn('students', 'national_id')) {
                $table->string('national_id')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['male', 'female'])->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'nationality')) {
                $table->string('nationality')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'email')) {
                $table->string('email')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'guardian_name')) {
                $table->string('guardian_name')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'guardian_relation')) {
                $table->string('guardian_relation')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'guardian_phone')) {
                $table->string('guardian_phone')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'guardian_email')) {
                $table->string('guardian_email')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'enrollment_date')) {
                $table->date('enrollment_date')->nullable()->change();
            }
            
            if (Schema::hasColumn('students', 'notes')) {
                $table->text('notes')->nullable()->change();
            }
            
            // إضافة عمود notes إذا لم يكن موجوداً
            if (!Schema::hasColumn('students', 'notes')) {
                $table->text('notes')->nullable()->after('enrollment_date');
            }
            
            // إضافة عمود is_active إذا لم يكن موجوداً
            if (!Schema::hasColumn('students', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('enrollment_date');
            }
            
            // إضافة عمود status إذا لم يكن موجوداً
            if (!Schema::hasColumn('students', 'status')) {
                $table->enum('status', ['active', 'transferred', 'graduated', 'withdrawn'])->default('active')->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // إعادة الأعمدة القديمة
            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->after('class_id');
                $table->string('middle_name')->nullable()->after('first_name');
                $table->string('last_name')->after('middle_name');
                $table->string('first_name_ar')->after('last_name');
                $table->string('middle_name_ar')->nullable()->after('first_name_ar');
                $table->string('last_name_ar')->after('middle_name_ar');
            }
            
            // تقسيم الاسم الكامل إلى أسماء مفصولة
            if (Schema::hasColumn('students', 'full_name')) {
                DB::statement('UPDATE students SET first_name = SUBSTRING_INDEX(full_name, " ", 1), last_name = SUBSTRING_INDEX(full_name, " ", -1) WHERE first_name IS NULL OR first_name = ""');
                $table->dropColumn('full_name');
            }
            
            // إعادة تسمية العمود
            if (Schema::hasColumn('students', 'national_id')) {
                $table->renameColumn('national_id', 'identity_number');
            }
            
            // حذف الأعمدة الجديدة
            if (Schema::hasColumn('students', 'is_active')) {
                $table->dropColumn('is_active');
            }
            
            if (Schema::hasColumn('students', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
