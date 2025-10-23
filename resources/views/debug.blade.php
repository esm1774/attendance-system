@extends('layouts.app')

@section('title', 'معلومات Debug')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0" style="color: #ffffff;">
                        <i class="fas fa-bug ml-2"></i>
                        معلومات Debug - التشخيص
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>ملاحظة:</strong> هذه الصفحة لأغراض التطوير فقط. يجب إزالتها في النظام النهائي.
                    </div>

                    <!-- فحص الاتصال بقاعدة البيانات -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0" style="color: #ffffff;">
                                <i class="fas fa-database ml-2"></i>
                                فحص الاتصال بقاعدة البيانات
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                try {
                                    \DB::connection()->getPdo();
                                    $dbStatus = '<span class="badge bg-success">متصل</span>';
                                    $dbName = \DB::connection()->getDatabaseName();
                                } catch (\Exception $e) {
                                    $dbStatus = '<span class="badge bg-danger">غير متصل</span>';
                                    $dbName = 'خطأ: ' . $e->getMessage();
                                }
                            @endphp
                            <p><strong>حالة الاتصال:</strong> {!! $dbStatus !!}</p>
                            <p><strong>اسم قاعدة البيانات:</strong> {{ $dbName }}</p>
                        </div>
                    </div>

                    <!-- فحص الجداول المطلوبة -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0" style="color: #ffffff;">
                                <i class="fas fa-table ml-2"></i>
                                فحص الجداول المطلوبة
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $tables = ['teachers', 'schools', 'subjects', 'school_classes', 'teacher_subject', 'teacher_school_class'];
                                $tableStatus = [];
                                foreach ($tables as $table) {
                                    try {
                                        \DB::table($table)->limit(1)->get();
                                        $tableStatus[$table] = true;
                                    } catch (\Exception $e) {
                                        $tableStatus[$table] = false;
                                    }
                                }
                            @endphp
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>اسم الجدول</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tableStatus as $table => $exists)
                                        <tr>
                                            <td><code>{{ $table }}</code></td>
                                            <td>
                                                @if($exists)
                                                    <span class="badge bg-success">موجود ✓</span>
                                                @else
                                                    <span class="badge bg-danger">غير موجود ✗</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- فحص Models -->
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0" style="color: #ffffff;">
                                <i class="fas fa-code ml-2"></i>
                                فحص Models
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $models = [
                                    'Teacher' => 'App\Models\Teacher',
                                    'School' => 'App\Models\School',
                                    'Subject' => 'App\Models\Subject',
                                    'SchoolClass' => 'App\Models\SchoolClass',
                                ];
                            @endphp
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>الحالة</th>
                                        <th>عدد السجلات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($models as $name => $class)
                                        <tr>
                                            <td><code>{{ $name }}</code></td>
                                            <td>
                                                @if(class_exists($class))
                                                    <span class="badge bg-success">موجود ✓</span>
                                                @else
                                                    <span class="badge bg-danger">غير موجود ✗</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(class_exists($class))
                                                    @php
                                                        try {
                                                            $count = $class::count();
                                                            echo $count;
                                                        } catch (\Exception $e) {
                                                            echo '<span class="text-danger">خطأ</span>';
                                                        }
                                                    @endphp
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- فحص مجلد التخزين -->
                    <div class="card mb-3">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0">
                                <i class="fas fa-folder ml-2"></i>
                                فحص مجلد التخزين
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $storagePath = storage_path('app/public/teachers');
                                $storageExists = \File::exists($storagePath);
                                $storageWritable = $storageExists ? is_writable($storagePath) : false;
                            @endphp
                            <p>
                                <strong>المسار:</strong> 
                                <code>{{ $storagePath }}</code>
                            </p>
                            <p>
                                <strong>الحالة:</strong>
                                @if($storageExists)
                                    <span class="badge bg-success">موجود ✓</span>
                                @else
                                    <span class="badge bg-danger">غير موجود ✗</span>
                                @endif
                            </p>
                            <p>
                                <strong>إمكانية الكتابة:</strong>
                                @if($storageWritable)
                                    <span class="badge bg-success">نعم ✓</span>
                                @else
                                    <span class="badge bg-danger">لا ✗</span>
                                @endif
                            </p>
                            
                            @if(!$storageExists || !$storageWritable)
                                <div class="alert alert-danger mt-3">
                                    <strong>حل المشكلة:</strong>
                                    <ol class="mb-0">
                                        <li>قم بتشغيل الأمر: <code>php artisan storage:link</code></li>
                                        <li>تأكد من صلاحيات المجلد: <code>chmod -R 775 storage</code></li>
                                    </ol>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- آخر سجلات Log -->
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0" style="color: #ffffff;">
                                <i class="fas fa-file-alt ml-2"></i>
                                آخر 20 سجل من Laravel Log
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $logFile = storage_path('logs/laravel.log');
                                if (file_exists($logFile)) {
                                    $logs = array_slice(array_reverse(file($logFile)), 0, 20);
                                } else {
                                    $logs = [];
                                }
                            @endphp
                            
                            @if(count($logs) > 0)
                                <div style="max-height: 400px; overflow-y: auto; background: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px;">
                                    @foreach($logs as $log)
                                        <div class="mb-1" style="border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                                            {{ $log }}
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-3">
                                    <a href="#" class="btn btn-sm btn-danger" onclick="if(confirm('هل أنت متأكد من مسح سجلات Log؟')) { document.getElementById('clear-log-form').submit(); }">
                                        <i class="fas fa-trash"></i> مسح سجلات Log
                                    </a>
                                    <form id="clear-log-form" action="{{ route('debug.clear-log') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i>
                                    لا توجد سجلات حالياً
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- معلومات النظام -->
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0" style="color: #ffffff;">
                                <i class="fas fa-server ml-2"></i>
                                معلومات النظام
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td width="30%"><strong>PHP Version:</strong></td>
                                    <td>{{ PHP_VERSION }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Laravel Version:</strong></td>
                                    <td>{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Environment:</strong></td>
                                    <td>{{ app()->environment() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Debug Mode:</strong></td>
                                    <td>
                                        @if(config('app.debug'))
                                            <span class="badge bg-warning">مفعّل</span>
                                        @else
                                            <span class="badge bg-success">معطّل</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection