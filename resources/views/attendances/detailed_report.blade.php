@extends('layouts.app')

@section('title', 'تقرير مفصل للحضور والغيابة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تقرير مفصل للحضور والغيابة</h3>
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
                                    <th>التاريخ</th>
                                    <th>اسم الطالب</th>
                                    <th>الرقم الدراسي</th>
                                    <th>المادة</th>
                                    <th>حالة الحضور</th>
                                    <th>وقت الوصول</th>
                                    <th>وقت المغادرة</th>
                                    <th>ملاحظات</th>
                                    <th>سجل بواسطة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $index => $attendance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $attendance->attendance_date }}</td>
                                    <td>{{ $attendance->student->full_name }}</td>
                                    <td>{{ $attendance->student->student_id }}</td>
                                    <td>{{ $attendance->subject->name }}</td>
                                    <td>
                                        @switch($attendance->status)
                                            @case('present')
                                                <span class="badge badge-success">حاضر</span>
                                                @break
                                            @case('absent')
                                                <span class="badge badge-danger">غائب</span>
                                                @break
                                            @case('late')
                                                <span class="badge badge-warning">متأخر</span>
                                                @break
                                            @case('excused')
                                                <span class="badge badge-info">معتذر</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $attendance->arrival_time ?? '-' }}</td>
                                    <td>{{ $attendance->departure_time ?? '-' }}</td>
                                    <td>{{ $attendance->notes ?? '-' }}</td>
                                    <td>{{ $attendance->recordedBy->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
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
