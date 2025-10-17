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
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});
// مسارات إدارة الأدوار
Route::resource('roles', RoleController::class);
Route::patch('roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');

// مسارات إدارة المستخدمين
Route::resource('users', UserController::class);
Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
Route::patch('users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions');


// لوحة التحكم
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
Route::get('teachers/{teacher}/stats', [TeacherController::class, 'getStats'])->name('teachers.stats');

// مسارات إدارة الطلاب
Route::resource('students', StudentController::class);
Route::patch('students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
Route::patch('students/{student}/change-status', [StudentController::class, 'changeStatus'])->name('students.change-status');
Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
Route::get('students/download-template', [StudentController::class, 'downloadImportTemplate'])->name('students.download-template');
Route::get('students/stats', [StudentController::class, 'getStats'])->name('students.stats');