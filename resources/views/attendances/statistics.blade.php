@extends('layouts.app')

@section('title', 'إحصائيات الحضور والغياب')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <h3 class="card-title" style="color: #fff;">
                        <i class="fas fa-chart-line ml-2"></i>
                        إحصائيات الحضور والغياب
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('attendances.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left ml-1"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- إحصائيات اليوم -->
                    <div class="mb-5">
                        <h4 class="mb-3 text-primary">
                            <i class="fas fa-calendar-day ml-2"></i>
                            إحصائيات اليوم
                        </h4>
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-info elevation-1">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">إجمالي السجلات</span>
                                        <span class="info-box-number">{{ $stats['today']['total'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-success elevation-1">
                                        <i class="fas fa-user-check"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">الحاضرين</span>
                                        <span class="info-box-number">{{ $stats['today']['present'] }}</span>
                                        <small class="text-success">
                                            {{ $stats['today']['total'] > 0 ? number_format(($stats['today']['present'] / $stats['today']['total']) * 100, 1) : 0 }}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-danger elevation-1">
                                        <i class="fas fa-user-times"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">الغياب الإجمالي</span>
                                        <span class="info-box-number">
                                            {{ $stats['today']['absent'] + ($stats['today']['excused'] ?? 0) }}
                                        </span>
                                        <small class="text-danger">
                                            بدون عذر: {{ $stats['today']['absent'] }} | بعذر: {{ $stats['today']['excused'] ?? 0 }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-warning elevation-1">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">المتأخرين</span>
                                        <span class="info-box-number">{{ $stats['today']['late'] }}</span>
                                        <small class="text-warning">
                                            {{ $stats['today']['total'] > 0 ? number_format(($stats['today']['late'] / $stats['today']['total']) * 100, 1) : 0 }}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات الشهر -->
                    <div class="mb-5">
                        <h4 class="mb-3 text-primary">
                            <i class="fas fa-calendar-month ml-2"></i>
                            إحصائيات هذا الشهر
                        </h4>
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-info elevation-1">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">إجمالي السجلات</span>
                                        <span class="info-box-number">{{ $stats['this_month']['total'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-success elevation-1">
                                        <i class="fas fa-user-check"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">الحاضرين</span>
                                        <span class="info-box-number">{{ $stats['this_month']['present'] }}</span>
                                        <small class="text-success">
                                            {{ $stats['this_month']['total'] > 0 ? number_format(($stats['this_month']['present'] / $stats['this_month']['total']) * 100, 1) : 0 }}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-danger elevation-1">
                                        <i class="fas fa-user-times"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">الغياب الإجمالي</span>
                                        <span class="info-box-number">
                                            {{ $stats['this_month']['absent'] + ($stats['this_month']['excused'] ?? 0) }}
                                        </span>
                                        <small class="text-danger">
                                            بدون عذر: {{ $stats['this_month']['absent'] }} | بعذر: {{ $stats['this_month']['excused'] ?? 0 }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-warning elevation-1">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">المتأخرين</span>
                                        <span class="info-box-number">{{ $stats['this_month']['late'] }}</span>
                                        <small class="text-warning">
                                            {{ $stats['this_month']['total'] > 0 ? number_format(($stats['this_month']['late'] / $stats['this_month']['total']) * 100, 1) : 0 }}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات العام -->
                    <div class="mb-5">
                        <h4 class="mb-3 text-primary">
                            <i class="fas fa-calendar-alt ml-2"></i>
                            إحصائيات هذا العام
                        </h4>
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-info elevation-1">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">إجمالي السجلات</span>
                                        <span class="info-box-number">{{ $stats['this_year']['total'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-success elevation-1">
                                        <i class="fas fa-user-check"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">الحاضرين</span>
                                        <span class="info-box-number">{{ $stats['this_year']['present'] }}</span>
                                        <small class="text-success">
                                            {{ $stats['this_year']['total'] > 0 ? number_format(($stats['this_year']['present'] / $stats['this_year']['total']) * 100, 1) : 0 }}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-danger elevation-1">
                                        <i class="fas fa-user-times"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">الغياب الإجمالي</span>
                                        <span class="info-box-number">
                                            {{ $stats['this_year']['absent'] + ($stats['this_year']['excused'] ?? 0) }}
                                        </span>
                                        <small class="text-danger">
                                            بدون عذر: {{ $stats['this_year']['absent'] }} | بعذر: {{ $stats['this_year']['excused'] ?? 0 }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-warning elevation-1">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">المتأخرين</span>
                                        <span class="info-box-number">{{ $stats['this_year']['late'] }}</span>
                                        <small class="text-warning">
                                            {{ $stats['this_year']['total'] > 0 ? number_format(($stats['this_year']['late'] / $stats['this_year']['total']) * 100, 1) : 0 }}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات الأعذار -->
                    <div class="mb-5">
                        <h4 class="mb-3 text-primary">
                            <i class="fas fa-file-alt ml-2"></i>
                            إحصائيات الأعذار
                        </h4>
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-secondary elevation-1">
                                        <i class="fas fa-list"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">إجمالي الأعذار</span>
                                        <span class="info-box-number">
                                            {{ $excuseStats['pending'] + $excuseStats['approved'] + $excuseStats['rejected'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-warning elevation-1">
                                        <i class="fas fa-hourglass-half"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">أعذار معلقة</span>
                                        <span class="info-box-number">{{ $excuseStats['pending'] }}</span>
                                        <small class="text-muted">تحتاج مراجعة</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-success elevation-1">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">أعذار مقبولة</span>
                                        <span class="info-box-number">{{ $excuseStats['approved'] }}</span>
                                        <small class="text-success">تم قبولها</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box shadow-sm hover-shadow">
                                    <span class="info-box-icon bg-gradient-danger elevation-1">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">أعذار مرفوضة</span>
                                        <span class="info-box-number">{{ $excuseStats['rejected'] }}</span>
                                        <small class="text-danger">تم رفضها</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الفصول ذات أعلى معدلات الغياب -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0" style="color: #fff;">
                                        <i class="fas fa-exclamation-triangle ml-2"></i>
                                        الفصول ذات أعلى معدلات الغياب (هذا الشهر)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($absenteeismByClass->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>الفصل</th>
                                                        <th class="text-center">إجمالي السجلات</th>
                                                        <th class="text-center">الحاضرين</th>
                                                        <th class="text-center">الغياب الإجمالي</th>
                                                        <th class="text-center">بدون عذر</th>
                                                        <th class="text-center">بعذر</th>
                                                        <th class="text-center">نسبة الغياب</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($absenteeismByClass as $index => $class)
                                                        @php
                                                            $totalAbsences = $class->absences + ($class->excused ?? 0);
                                                            $absenteeismRate = $class->total_attendances > 0 
                                                                ? number_format(($totalAbsences / $class->total_attendances) * 100, 1) 
                                                                : 0;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td>
                                                                <strong>{{ $class->name_ar ?? $class->name }}</strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-info badge-pill">
                                                                    {{ $class->total_attendances }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-success badge-pill">
                                                                    {{ $class->total_attendances - $totalAbsences }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-danger badge-pill">
                                                                    {{ $totalAbsences }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-warning badge-pill">
                                                                    {{ $class->absences }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-secondary badge-pill">
                                                                    {{ $class->excused ?? 0 }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="progress" style="height: 25px;">
                                                                    <div class="progress-bar bg-danger" 
                                                                         role="progressbar" 
                                                                         style="width: {{ $absenteeismRate }}%"
                                                                         aria-valuenow="{{ $absenteeismRate }}" 
                                                                         aria-valuemin="0" 
                                                                         aria-valuemax="100">
                                                                        <strong>{{ $absenteeismRate }}%</strong>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد بيانات للعرض</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الطلاب الأكثر غياباً -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="mb-0" style="color: #fff;">
                                        <i class="fas fa-user-clock ml-2"></i>
                                        الطلاب الأكثر غياباً (هذا الشهر)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($mostAbsentStudents->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>الطالب</th>
                                                        <th class="text-center">الرقم الجامعي</th>
                                                        <th class="text-center">إجمالي السجلات</th>
                                                        <th class="text-center">الحاضر</th>
                                                        <th class="text-center">الغياب الإجمالي</th>
                                                        <th class="text-center">بدون عذر</th>
                                                        <th class="text-center">بعذر</th>
                                                        <th class="text-center">نسبة الغياب</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($mostAbsentStudents as $index => $student)
                                                        @php
                                                            $totalAbsences = $student->absences + ($student->excused ?? 0);
                                                            $absenteeismRate = $student->total_attendances > 0 
                                                                ? number_format(($totalAbsences / $student->total_attendances) * 100, 1) 
                                                                : 0;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td>
                                                                <strong>{{ $student->full_name }}</strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-secondary">
                                                                    {{ $student->student_id }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-info badge-pill">
                                                                    {{ $student->total_attendances }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-success badge-pill">
                                                                    {{ $student->total_attendances - $totalAbsences }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-danger badge-pill">
                                                                    {{ $totalAbsences }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-warning badge-pill">
                                                                    {{ $student->absences }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-secondary badge-pill">
                                                                    {{ $student->excused ?? 0 }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="progress" style="height: 25px;">
                                                                    <div class="progress-bar bg-danger" 
                                                                         role="progressbar" 
                                                                         style="width: {{ $absenteeismRate }}%"
                                                                         aria-valuenow="{{ $absenteeismRate }}" 
                                                                         aria-valuemin="0" 
                                                                         aria-valuemax="100">
                                                                        <strong>{{ $absenteeismRate }}%</strong>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد بيانات للعرض</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.info-box {
    border-radius: 10px;
    overflow: hidden;
}

.info-box-icon {
    border-radius: 10px 0 0 10px;
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    border: none;
}

.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.badge-pill {
    padding: 0.5em 0.75em;
    font-size: 0.9rem;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
    font-size: 0.85rem;
}
</style>
@endsection