<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentControllerNew;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// مسارات إدارة الأدوار
Route::resource('roles', RoleController::class);
Route::patch('roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');

// مسارات إدارة المستخدمين
Route::resource('users', UserController::class);
Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
Route::patch('users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions');

// لوحة التحكم
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.page');
Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

// مسارات إدارة المواد الدراسية
Route::resource('subjects', SubjectController::class);
Route::patch('subjects/{subject}/toggle-status', [SubjectController::class, 'toggleStatus'])->name('subjects.toggle-status');
Route::get('subjects-api', [SubjectController::class, 'getSubjects'])->name('subjects.api');

// مسارات إدارة المدارس
Route::resource('schools', SchoolController::class);
Route::patch('schools/{school}/toggle-status', [SchoolController::class, 'toggleStatus'])->name('schools.toggle-status');
Route::get('schools/{school}/stats', [SchoolController::class, 'getStats'])->name('schools.stats');

// مسارات إدارة المراحل الدراسية
Route::resource('stages', StageController::class);
Route::patch('stages/{stage}/toggle-status', [StageController::class, 'toggleStatus'])->name('stages.toggle-status');
Route::get('stages/by-school/{schoolId}', [StageController::class, 'getStagesBySchool'])->name('stages.by-school');
Route::post('stages/update-order', [StageController::class, 'updateOrder'])->name('stages.update-order');

// مسارات إدارة الصفوف الدراسية
Route::resource('grades', GradeController::class);
Route::patch('grades/{grade}/toggle-status', [GradeController::class, 'toggleStatus'])->name('grades.toggle-status');
Route::get('grades/by-stage/{stageId}', [GradeController::class, 'getGradesByStage'])->name('grades.by-stage');
Route::post('grades/update-order', [GradeController::class, 'updateOrder'])->name('grades.update-order');
Route::get('grades/{grade}/manage-subjects', [GradeController::class, 'manageSubjects'])->name('grades.manage-subjects');
Route::post('grades/{grade}/update-subjects', [GradeController::class, 'updateSubjects'])->name('grades.update-subjects');

// مسارات إدارة الفصول الدراسية
Route::resource('classes', SchoolClassController::class);
Route::patch('classes/{class}/toggle-status', [SchoolClassController::class, 'toggleStatus'])->name('classes.toggle-status');
Route::get('classes/{class}/stats', [SchoolClassController::class, 'getStats'])->name('classes.stats');
Route::get('grades/{grade}/classes', [SchoolClassController::class, 'getClassesByGrade'])->name('classes.by-grade');

// مسارات إدارة المعلمين
Route::resource('teachers', TeacherController::class);
Route::patch('teachers/{teacher}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('teachers.toggle-status');
Route::get('teachers/download/template', [TeacherController::class, 'downloadTemplate'])->name('teachers.download-template');
Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
Route::get('teachers/export', [TeacherController::class, 'export'])->name('teachers.export');

// مسارات إدارة الطلاب
Route::resource('students', StudentControllerNew::class);
Route::patch('students/{student}/toggle-status', [StudentControllerNew::class, 'toggleStatus'])->name('students.toggle-status');
Route::patch('students/{student}/change-status', [StudentControllerNew::class, 'changeStatus'])->name('students.change-status');
Route::post('students/import', [StudentControllerNew::class, 'import'])->name('students.import');
Route::get('students/stats', [StudentControllerNew::class, 'getStats'])->name('students.stats');
Route::get('students/template/download', [StudentControllerNew::class, 'downloadTemplate'])->name('students.download-template');

// مسارات نظام الحضور والغياب
Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
Route::get('attendances/class', [AttendanceController::class, 'showClassAttendanceForm'])->name('attendances.class-form');
Route::post('attendances/class', [AttendanceController::class, 'showClassAttendance'])->name('attendances.show-class');
Route::post('attendances/store', [AttendanceController::class, 'store'])->name('attendances.store');
Route::get('attendances/reports', [AttendanceController::class, 'reports'])->name('attendances.reports');
Route::post('attendances/show-report', [AttendanceController::class, 'showReport'])->name('attendances.show-report');
Route::get('attendances/excuses', [AttendanceController::class, 'excuses'])->name('attendances.excuses');
Route::get('attendances/statistics', [AttendanceController::class, 'statistics'])->name('attendances.statistics');
Route::patch('attendances/excuses/{excuse}/status', [AttendanceController::class, 'updateExcuseStatus'])->name('attendances.update-excuse-status');

// مسارات Debug (للتطوير فقط - احذفها في الإنتاج)
Route::get('/debug', function () {
    return view('debug');
})->name('debug');

Route::post('/debug/clear-log', function () {
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        file_put_contents($logFile, '');
    }
    return back()->with('success', 'تم مسح سجلات Log بنجاح');
})->name('debug.clear-log');

Route::get('/test-teacher', function () {
    $results = [];
    
    // 1. اختبار قاعدة البيانات
    try {
        \DB::connection()->getPdo();
        $results['database'] = '✓ الاتصال بقاعدة البيانات ناجح';
    } catch (\Exception $e) {
        $results['database'] = '✗ خطأ في الاتصال: ' . $e->getMessage();
    }
    
    // 2. اختبار الجداول
    $tables = ['teachers', 'schools', 'subjects', 'school_classes', 'teacher_subject', 'teacher_school_class'];
    foreach ($tables as $table) {
        try {
            \DB::table($table)->limit(1)->get();
            $results["table_{$table}"] = "✓ جدول {$table} موجود";
        } catch (\Exception $e) {
            $results["table_{$table}"] = "✗ جدول {$table} غير موجود: " . $e->getMessage();
        }
    }
    
    // 3. اختبار Models
    try {
        $schoolCount = \App\Models\School::count();
        $results['school_model'] = "✓ Model School يعمل - عدد المدارس: {$schoolCount}";
    } catch (\Exception $e) {
        $results['school_model'] = '✗ خطأ في School Model: ' . $e->getMessage();
    }
    
    try {
        $subjectCount = \App\Models\Subject::count();
        $results['subject_model'] = "✓ Model Subject يعمل - عدد المواد: {$subjectCount}";
    } catch (\Exception $e) {
        $results['subject_model'] = '✗ خطأ في Subject Model: ' . $e->getMessage();
    }
    
    try {
        $classCount = \App\Models\SchoolClass::count();
        $results['class_model'] = "✓ Model SchoolClass يعمل - عدد الفصول: {$classCount}";
    } catch (\Exception $e) {
        $results['class_model'] = '✗ خطأ في SchoolClass Model: ' . $e->getMessage();
    }
    
    // 4. اختبار إنشاء معلم تجريبي
    try {
        $testData = [
            'name' => 'معلم تجريبي ' . time(),
            'national_id' => 'TEST' . time(),
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'nationality' => 'سعودي',
            'phone' => '0500000000',
            'email' => 'test' . time() . '@test.com',
            'employee_number' => 'EMP' . time(),
            'specialization' => 'اختبار',
            'qualification' => 'بكالوريوس',
            'hire_date' => now(),
            'contract_type' => 'permanent',
            'school_id' => \App\Models\School::first()->id ?? 1,
            'status' => 'active',
            'is_active' => true,
        ];
        
        $teacher = \App\Models\Teacher::create($testData);
        $results['create_teacher'] = "✓ تم إنشاء معلم تجريبي بنجاح - ID: {$teacher->id}";
        
        // حذف المعلم التجريبي
        $teacher->delete();
        $results['delete_teacher'] = "✓ تم حذف المعلم التجريبي بنجاح";
        
    } catch (\Exception $e) {
        $results['create_teacher'] = '✗ فشل إنشاء معلم تجريبي: ' . $e->getMessage();
    }
    
    // 5. اختبار مجلد التخزين
    $storagePath = storage_path('app/public/teachers');
    if (!\File::exists($storagePath)) {
        \File::makeDirectory($storagePath, 0775, true);
        $results['storage'] = "✓ تم إنشاء مجلد التخزين: {$storagePath}";
    } else {
        $writable = is_writable($storagePath);
        $results['storage'] = $writable 
            ? "✓ مجلد التخزين موجود وقابل للكتابة" 
            : "✗ مجلد التخزين موجود لكنه غير قابل للكتابة";
    }
    
    // عرض النتائج
    return view('test-results', compact('results'));
})->name('test.teacher');

Route::get('/view-log', function () {
    $logFile = storage_path('logs/laravel.log');
    
    if (!file_exists($logFile)) {
        return '<h1>لا يوجد ملف Log</h1>';
    }
    
    $content = file_get_contents($logFile);
    
    return '<html><head><title>Laravel Log</title><style>
        body { font-family: monospace; background: #1e1e1e; color: #d4d4d4; padding: 20px; }
        .error { color: #f48771; }
        .info { color: #4ec9b0; }
        .warning { color: #dcdcaa; }
        pre { white-space: pre-wrap; word-wrap: break-word; }
    </style></head><body><h1>Laravel Log File</h1><pre>' . 
    htmlspecialchars($content) . 
    '</pre></body></html>';
})->name('view.log');

Route::get('/clear-log', function () {
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        file_put_contents($logFile, '');
        return redirect()->back()->with('success', 'تم مسح Log بنجاح');
    }
    return redirect()->back()->with('error', 'ملف Log غير موجود');
})->name('clear.log');