@extends('layouts.app')

@section('title', 'إحصائيات الحضور والغيابة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إحصائيات الحضور والغيابة</h3>
                    <div class="card-tools">
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- إحصائيات اليوم -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">إجمالي الحضور اليوم</span>
                                    <span class="info-box-number">{{ $stats['today']['total'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الحاضرين</span>
                                    <span class="info-box-number">{{ $stats['today']['present'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-user-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الغائبين</span>
                                    <span class="info-box-number">{{ $stats['today']['absent'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">المتأخرين</span>
                                    <span class="info-box-number">{{ $stats['today']['late'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات الشهر -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-calendar-month"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">إجمالي الحضور هذا الشهر</span>
                                    <span class="info-box-number">{{ $stats['this_month']['total'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الحاضرين</span>
                                    <span class="info-box-number">{{ $stats['this_month']['present'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-user-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الغائبين</span>
                                    <span class="info-box-number">{{ $stats['this_month']['absent'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">المتأخرين</span>
                                    <span class="info-box-number">{{ $stats['this_month']['late'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات العام -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">إجمالي الحضور هذا العام</span>
                                    <span class="info-box-number">{{ $stats['this_year']['total'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الحاضرين</span>
                                    <span class="info-box-number">{{ $stats['this_year']['present'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-user-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الغائبين</span>
                                    <span class="info-box-number">{{ $stats['this_year']['absent'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">المتأخرين</span>
                                    <span class="info-box-number">{{ $stats['this_year']['late'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات الأعذار -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>إحصائيات الأعذار</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">أعذار معلقة</span>
                                    <span class="info-box-number">{{ $excuseStats['pending'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">أعذار مقبولة</span>
                                    <span class="info-box-number">{{ $excuseStats['approved'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">أعذار مرفوضة</span>
                                    <span class="info-box-number">{{ $excuseStats['rejected'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الفصول ذات أعلى معدلات الغياب -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>الفصول ذات أعلى معدلات الغياب (هذا الشهر)</h5>
                                </div>
                                <div class="card-body">
                                    @if($absenteeismByClass->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>الفصل</th>
                                                    <th>إجمالي الحضور</th>
                                                    <th>عدد الغيابات</th>
                                                    <th>نسبة الغياب (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($absenteeismByClass as $class)
                                                    <tr>
                                                        <td>{{ $class->name_ar ?? $class->name }}</td>
                                                        <td>{{ $class->total_attendances }}</td>
                                                        <td>{{ $class->absences }}</td>
                                                        <td>{{ $class->absenteeism_rate }}%</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted">لا توجد بيانات للعرض</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الطلاب الأكثر غياباً -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>الطلاب الأكثر غياباً (هذا الشهر)</h5>
                                </div>
                                <div class="card-body">
                                    @if($mostAbsentStudents->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>الطالب</th>
                                                    <th>الرقم الجامعي</th>
                                                    <th>إجمالي الحضور</th>
                                                    <th>عدد الغيابات</th>
                                                    <th>نسبة الغياب (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($mostAbsentStudents as $student)
                                                    <tr>
                                                        <td>{{ $student->full_name }}</td>
                                                        <td>{{ $student->student_id }}</td>
                                                        <td>{{ $student->total_attendances }}</td>
                                                        <td>{{ $student->absences }}</td>
                                                        <td>{{ $student->absenteeism_rate }}%</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-muted">لا توجد بيانات للعرض</p>
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
@endsection
