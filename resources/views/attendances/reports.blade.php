@extends('layouts.app')

@section('title', 'تقارير الحضور والغياب')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title" style="color: #ffffff;">
                        <i class="fas fa-file-alt ml-2"></i>
                        تقارير الحضور والغياب
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('attendances.show-report') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="class_id">
                                        <i class="fas fa-chalkboard ml-1"></i>
                                        الفصل الدراسي <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2" id="class_id" name="class_id" required>
                                        <option value="">اختر الفصل</option>
                                        @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->full_path }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subject_id">
                                        <i class="fas fa-book ml-1"></i>
                                        المادة الدراسية
                                    </label>
                                    <select class="form-control select2" id="subject_id" name="subject_id">
                                        <option value="">جميع المواد</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="from_date">
                                        <i class="fas fa-calendar-alt ml-1"></i>
                                        من تاريخ <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="from_date" 
                                           name="from_date" 
                                           value="{{ old('from_date', now()->subDays(30)->format('Y-m-d')) }}" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="to_date">
                                        <i class="fas fa-calendar-check ml-1"></i>
                                        إلى تاريخ <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="to_date" 
                                           name="to_date" 
                                           value="{{ old('to_date', now()->format('Y-m-d')) }}" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="report_type">
                                        <i class="fas fa-list ml-1"></i>
                                        نوع التقرير <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" id="report_type" name="report_type" required>
                                        <option value="summary" {{ old('report_type') == 'summary' ? 'selected' : '' }}>ملخص</option>
                                        <option value="detailed" {{ old('report_type') == 'detailed' ? 'selected' : '' }}>مفصل</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg mt-2">
                                    <i class="fas fa-search ml-1"></i> عرض التقرير
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg mt-2">
                                    <i class="fas fa-redo ml-1"></i> إعادة تعيين
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($reportData))
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title mb-0" style="color: #ffffff;">
                        <i class="fas fa-chart-bar ml-2"></i>
                        نتائج التقرير
                    </h4>
                    <div class="card-tools">
                        <button class="btn btn-light btn-sm" onclick="window.print()">
                            <i class="fas fa-print ml-1"></i> طباعة
                        </button>
                        <a href="{{ route('attendances.export-report', request()->all()) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel ml-1"></i> تصدير Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- ملخص الإحصائيات -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon bg-info elevation-1">
                                    <i class="fas fa-users"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">إجمالي السجلات</span>
                                    <span class="info-box-number">{{ $reportData['total_records'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon bg-success elevation-1">
                                    <i class="fas fa-user-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الحاضرين والمتأخرين</span>
                                    <span class="info-box-number">{{ ($reportData['present'] ?? 0) + ($reportData['late'] ?? 0) }}</span>
                                    <small class="text-success">
                                        {{ $reportData['total_records'] > 0 ? number_format(((($reportData['present'] ?? 0) + ($reportData['late'] ?? 0)) / $reportData['total_records']) * 100, 1) : 0 }}%
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon bg-danger elevation-1">
                                    <i class="fas fa-user-times"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الغياب الإجمالي</span>
                                    <span class="info-box-number">
                                        {{ ($reportData['absent'] ?? 0) + ($reportData['excused'] ?? 0) }}
                                    </span>
                                    <small class="text-danger">
                                        بدون عذر: {{ $reportData['absent'] ?? 0 }} | بعذر: {{ $reportData['excused'] ?? 0 }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon bg-warning elevation-1">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">المتأخرين</span>
                                    <span class="info-box-number">{{ $reportData['late'] ?? 0 }}</span>
                                    <small class="text-warning">
                                        {{ $reportData['total_records'] > 0 ? number_format(($reportData['late'] / $reportData['total_records']) * 100, 1) : 0 }}%
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- التقرير الملخص -->
                    @if(request('report_type') == 'summary')
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>اسم الطالب</th>
                                        <th class="text-center">الرقم الجامعي</th>
                                        <th class="text-center">الحاضر</th>
                                        <th class="text-center">الغياب الإجمالي</th>
                                        <th class="text-center">بدون عذر</th>
                                        <th class="text-center">بعذر</th>
                                        <th class="text-center">المتأخر</th>
                                        <th class="text-center">نسبة الغياب</th>
                                        <th class="text-center">نسبة الحضور</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportData['students'] ?? [] as $index => $student)
                                        @php
                                            $totalAbsent = $student->absent + ($student->excused ?? 0);
                                            $total = $student->present + $totalAbsent + $student->late;
                                            $absentRate = $total > 0 ? number_format(($totalAbsent / $total) * 100, 1) : 0;
                                            $presentRate = $total > 0 ? number_format((($student->present + $student->late) / $total) * 100, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td><strong>{{ $student->full_name }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge badge-secondary">{{ $student->student_id }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-success badge-pill">{{ $student->present }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-danger badge-pill">{{ $totalAbsent }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-warning badge-pill">{{ $student->absent }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-secondary badge-pill">{{ $student->excused ?? 0 }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info badge-pill">{{ $student->late }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 25px; min-width: 80px;">
                                                    <div class="progress-bar bg-danger" 
                                                         role="progressbar" 
                                                         style="width: {{ $absentRate }}%"
                                                         aria-valuenow="{{ $absentRate }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        <strong>{{ $absentRate }}%</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 25px; min-width: 80px;">
                                                    <div class="progress-bar bg-success" 
                                                         role="progressbar" 
                                                         style="width: {{ $presentRate }}%"
                                                         aria-valuenow="{{ $presentRate }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        <strong>{{ $presentRate }}%</strong>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- التقرير المفصل -->
                    @if(request('report_type') == 'detailed')
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>اسم الطالب</th>
                                        <th class="text-center">الرقم الجامعي</th>
                                        <th>المادة</th>
                                        <th class="text-center">التاريخ</th>
                                        <th class="text-center">الوقت</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">الحالة التفصيلية</th>
                                        <th>ملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportData['records'] ?? [] as $index => $record)
                                        @php
                                            $statusBadge = '';
                                            $statusText = '';
                                            $detailedStatus = '';
                                            
                                            if ($record->status == 'present') {
                                                $statusBadge = 'badge-success';
                                                $statusText = 'حاضر';
                                                $detailedStatus = $record->is_late ? 'متأخر' : 'في الموعد';
                                            } elseif ($record->status == 'absent') {
                                                if ($record->excuse_status == 'approved') {
                                                    $statusBadge = 'badge-secondary';
                                                    $statusText = 'غائب';
                                                    $detailedStatus = 'غائب بعذر';
                                                } else {
                                                    $statusBadge = 'badge-danger';
                                                    $statusText = 'غائب';
                                                    $detailedStatus = 'غائب بدون عذر';
                                                }
                                            } else {
                                                $statusBadge = 'badge-warning';
                                                $statusText = 'متأخر';
                                                $detailedStatus = 'متأخر';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td><strong>{{ $record->student->full_name }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge badge-secondary">{{ $record->student->student_id }}</span>
                                            </td>
                                            <td>{{ $record->subject->name ?? 'غير محدد' }}</td>
                                            <td class="text-center">{{ $record->date }}</td>
                                            <td class="text-center">{{ $record->time ?? '-' }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info">{{ $detailedStatus }}</span>
                                            </td>
                                            <td>{{ $record->notes ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if(empty($reportData['students']) && empty($reportData['records']))
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد بيانات للفترة المحددة</h5>
                            <p class="text-muted">يرجى تعديل معايير البحث والمحاولة مرة أخرى</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.info-box {
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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
    background-color: #f8f9fa;
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

.form-group label {
    font-weight: 600;
    color: #495057;
}

.form-control {
    border-radius: 5px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

@media print {
    .card-tools,
    .btn,
    .sidebar,
    .navbar {
        display: none !important;
    }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // تفعيل Select2 إذا كان متاحاً
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    }
    
    // التحقق من صحة التواريخ
    $('#from_date, #to_date').on('change', function() {
        var fromDate = new Date($('#from_date').val());
        var toDate = new Date($('#to_date').val());
        
        if (fromDate > toDate) {
            alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
            $(this).val('');
        }
    });
});
</script>
@endpush
@endsection