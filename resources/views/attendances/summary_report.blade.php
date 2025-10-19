@extends('layouts.app')

@section('title', 'تقرير ملخص للحضور والغياب')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title" style="color: #ffffff;">
                        <i class="fas fa-chart-pie ml-2"></i>
                        تقرير ملخص للحضور والغياب
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('attendances.reports') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left ml-1"></i> رجوع
                        </a>
                        <button class="btn btn-success btn-sm" onclick="window.print()">
                            <i class="fas fa-print ml-1"></i> طباعة
                        </button>
                        <button class="btn btn-info btn-sm" onclick="exportToExcel()">
                            <i class="fas fa-file-excel ml-1"></i> تصدير Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- معلومات التقرير -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-3">
                                <strong><i class="fas fa-chalkboard ml-1"></i> الفصل:</strong>
                                <span class="badge badge-primary">{{ $class->full_path }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong><i class="fas fa-book ml-1"></i> المادة:</strong>
                                <span class="badge badge-secondary">
                                    {{ $subjectId ? \App\Models\Subject::find($subjectId)->name : 'جميع المواد' }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <strong><i class="fas fa-calendar-alt ml-1"></i> من تاريخ:</strong>
                                <span class="badge badge-info">{{ $fromDate }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong><i class="fas fa-calendar-check ml-1"></i> إلى تاريخ:</strong>
                                <span class="badge badge-info">{{ $toDate }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات عامة -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="info-box shadow-sm hover-effect">
                                <span class="info-box-icon bg-info elevation-1">
                                    <i class="fas fa-users"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">عدد الطلاب</span>
                                    <span class="info-box-number">{{ count($reportData) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="info-box shadow-sm hover-effect">
                                <span class="info-box-icon bg-success elevation-1">
                                    <i class="fas fa-user-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">إجمالي الحضور</span>
                                    <span class="info-box-number">
                                        {{ array_sum(array_column($reportData, 'present_days')) + array_sum(array_column($reportData, 'late_days')) }}
                                    </span>
                                    <small class="text-muted">حاضر + متأخر</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="info-box shadow-sm hover-effect">
                                <span class="info-box-icon bg-danger elevation-1">
                                    <i class="fas fa-user-times"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">إجمالي الغياب</span>
                                    <span class="info-box-number">
                                        {{ array_sum(array_column($reportData, 'absent_days')) + array_sum(array_column($reportData, 'excused_days')) }}
                                    </span>
                                    <small class="text-muted">بعذر + بدون عذر</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="info-box shadow-sm hover-effect">
                                <span class="info-box-icon bg-warning elevation-1">
                                    <i class="fas fa-percentage"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">متوسط الحضور</span>
                                    <span class="info-box-number">
                                        @php
                                            $totalDays = array_sum(array_column($reportData, 'total_days'));
                                            $presentAndLate = array_sum(array_column($reportData, 'present_days')) + array_sum(array_column($reportData, 'late_days'));
                                            $avgRate = $totalDays > 0 ? round(($presentAndLate / $totalDays) * 100, 1) : 0;
                                        @endphp
                                        {{ $avgRate }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الجدول الرئيسي -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="reportTable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>اسم الطالب</th>
                                    <th class="text-center">الرقم الجامعي</th>
                                    <th class="text-center">إجمالي الأيام</th>
                                    <th class="text-center bg-success text-white">حاضر</th>
                                    <th class="text-center bg-warning text-white">متأخر</th>
                                    <th class="text-center bg-danger text-white">غياب بدون عذر</th>
                                    <th class="text-center bg-secondary text-white">غياب بعذر</th>
                                    <th class="text-center bg-info text-white">إجمالي الغياب</th>
                                    <th class="text-center" style="min-width: 150px;">نسبة الحضور</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData as $index => $data)
                                    @php
                                        $totalAbsent = $data['absent_days'] + $data['excused_days'];
                                        $presentWithLate = $data['present_days'] + $data['late_days'];
                                        $attendanceRate = $data['total_days'] > 0 
                                            ? round(($presentWithLate / $data['total_days']) * 100, 1) 
                                            : 0;
                                        
                                        // تحديد لون النسبة
                                        if ($attendanceRate >= 90) {
                                            $rateColor = 'bg-success';
                                            $rateText = 'ممتاز';
                                        } elseif ($attendanceRate >= 75) {
                                            $rateColor = 'bg-warning';
                                            $rateText = 'جيد';
                                        } elseif ($attendanceRate >= 60) {
                                            $rateColor = 'bg-orange';
                                            $rateText = 'مقبول';
                                        } else {
                                            $rateColor = 'bg-danger';
                                            $rateText = 'ضعيف';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td><strong>{{ $data['student']->full_name }}</strong></td>
                                        <td class="text-center">
                                            <span class="badge badge-secondary">{{ $data['student']->student_id }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary badge-pill">{{ $data['total_days'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-success badge-pill">{{ $data['present_days'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-warning badge-pill">{{ $data['late_days'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-danger badge-pill">{{ $data['absent_days'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-secondary badge-pill">{{ $data['excused_days'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-dark badge-pill">{{ $totalAbsent }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 30px;">
                                                <div class="progress-bar {{ $rateColor }}" 
                                                    role="progressbar" 
                                                    style="width: {{ $attendanceRate }}%"
                                                    aria-valuenow="{{ $attendanceRate }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                    <strong>{{ $attendanceRate }}% - {{ $rateText }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <th colspan="3" class="text-center">المجموع الكلي</th>
                                    <th class="text-center">
                                        <span class="badge badge-primary badge-pill">
                                            {{ array_sum(array_column($reportData, 'total_days')) }}
                                        </span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge badge-success badge-pill">
                                            {{ array_sum(array_column($reportData, 'present_days')) }}
                                        </span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge badge-warning badge-pill">
                                            {{ array_sum(array_column($reportData, 'late_days')) }}
                                        </span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge badge-danger badge-pill">
                                            {{ array_sum(array_column($reportData, 'absent_days')) }}
                                        </span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge badge-secondary badge-pill">
                                            {{ array_sum(array_column($reportData, 'excused_days')) }}
                                        </span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge badge-dark badge-pill">
                                            {{ array_sum(array_column($reportData, 'absent_days')) + array_sum(array_column($reportData, 'excused_days')) }}
                                        </span>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge badge-info badge-pill" style="font-size: 1.1rem;">
                                            {{ $avgRate }}%
                                        </span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- ملاحظات -->
                    <div class="alert alert-light mt-4">
                        <h6><i class="fas fa-info-circle ml-1"></i> ملاحظات هامة:</h6>
                        <ul class="mb-0">
                            <li><strong>نسبة الحضور</strong> = (الحاضر + المتأخر) / إجمالي الأيام × 100</li>
                            <li><strong>إجمالي الغياب</strong> = الغياب بدون عذر + الغياب بعذر</li>
                            <li><strong>المتأخر</strong> يُحسب ضمن الحضور (لأنه حضر فعلياً)</li>
                            <li><strong>الغياب بعذر</strong> يُحسب ضمن الغياب الإجمالي</li>
                            <li>التقييم: ممتاز (90% فأكثر) | جيد (75-89%) | مقبول (60-74%) | ضعيف (أقل من 60%)</li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>
                        <i class="fas fa-calendar ml-1"></i> تاريخ إنشاء التقرير: {{ now()->format('Y-m-d H:i:s') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .card-tools, .btn, .navbar, .sidebar, .content-header, .main-footer {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .card-footer {
            border-top: 1px solid #dee2e6 !important;
        }
        body {
            background: white !important;
        }
    }
    
    .hover-effect {
        transition: all 0.3s ease;
    }
    
    .hover-effect:hover {
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
        font-weight: 600;
        vertical-align: middle;
    }
    
    .badge-pill {
        padding: 0.5em 0.75em;
        font-size: 0.9rem;
    }
    
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 10px;
        font-size: 0.85rem;
    }
    
    .bg-orange {
        background-color: #fd7e14 !important;
    }
    
    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
</style>
@endsection

@section('scripts')
<script>
function exportToExcel() {
    // تصدير الجدول إلى Excel
    var table = document.getElementById('reportTable');
    var html = table.outerHTML;
    var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    var downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = 'تقرير_الحضور_{{ date("Y-m-d") }}.xls';
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>
@endsection