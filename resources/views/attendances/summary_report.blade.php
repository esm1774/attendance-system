@extends('layouts.app')

@section('title', 'تقرير ملخص للحضور والغيابة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تقرير ملخص للحضور والغيابة</h3>
                    <div class="card-tools">
                        <a href="{{ route('attendances.reports') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                        <button class="btn btn-success btn-sm" onclick="window.print()">
                            <i class="fas fa-print"></i> طباعة
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>الفصل:</strong> {{ $class->full_path }}
                        </div>
                        <div class="col-md-3">
                            <strong>المادة:</strong> {{ $subjectId ? \App\Models\Subject::find($subjectId)->name : 'جميع المواد' }}
                        </div>
                        <div class="col-md-3">
                            <strong>من تاريخ:</strong> {{ $fromDate }}
                        </div>
                        <div class="col-md-3">
                            <strong>إلى تاريخ:</strong> {{ $toDate }}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>الرقم الدراسي</th>
                                    <th>إجمالي الأيام</th>
                                    <th>أيام الحضور</th>
                                    <th>أيام الغياب</th>
                                    <th>أيام التأخير</th>
                                    <th>أيام العذر</th>
                                    <th>نسبة الحضور (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data['student']->full_name }}</td>
                                    <td>{{ $data['student']->student_id }}</td>
                                    <td>{{ $data['total_days'] }}</td>
                                    <td>{{ $data['present_days'] }}</td>
                                    <td>{{ $data['absent_days'] }}</td>
                                    <td>{{ $data['late_days'] }}</td>
                                    <td>{{ $data['excused_days'] }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar {{ $data['attendance_rate'] >= 90 ? 'bg-success' : ($data['attendance_rate'] >= 75 ? 'bg-warning' : 'bg-danger') }}" 
                                                role="progressbar" 
                                                style="width: {{ $data['attendance_rate'] }}%"
                                                aria-valuenow="{{ $data['attendance_rate'] }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ $data['attendance_rate'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="3">المجموع</th>
                                    <th>{{ array_sum(array_column($reportData, 'total_days')) }}</th>
                                    <th>{{ array_sum(array_column($reportData, 'present_days')) }}</th>
                                    <th>{{ array_sum(array_column($reportData, 'absent_days')) }}</th>
                                    <th>{{ array_sum(array_column($reportData, 'late_days')) }}</th>
                                    <th>{{ array_sum(array_column($reportData, 'excused_days')) }}</th>
                                    <th>{{ round(array_sum(array_column($reportData, 'attendance_rate')) / count($reportData), 2) }}%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .card-tools, .btn, .navbar, .sidebar, .content-header {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
        }
    }
</style>
@endsection
