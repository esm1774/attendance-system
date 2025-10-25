@extends('layouts.app')

@section('title', 'تفاصيل الفصل: ' . $class->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل الفصل: {{ $class->name_ar }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">رمز الفصل:</th>
                                    <td>
                                        <span class="badge badge-secondary">{{ $class->code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>اسم الفصل (إنجليزي):</th>
                                    <td>{{ $class->name }}</td>
                                </tr>
                                <tr>
                                    <th>اسم الفصل (عربي):</th>
                                    <td>{{ $class->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>الصف الدراسي:</th>
                                    <td>{{ $class->grade->name_ar ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>رقم القاعة:</th>
                                    <td>{{ $class->room_number ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">الحالة:</th>
                                    <td>
                                        <span class="badge badge-{{ $class->is_active ? 'success' : 'danger' }}">
                                            {{ $class->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>مرشد الفصل:</th>
                                    <td>
                                        @if($class->teacher)
                                            <span class="badge badge-primary">{{ $class->teacher->name }}</span>
                                        @else
                                            <span class="badge badge-secondary">غير محدد</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>سعة الفصل:</th>
                                    <td>{{ $class->capacity }} طالب</td>
                                </tr>
                                <tr>
                                    <th>عدد الطلاب الحالي:</th>
                                    <td>
                                        <span class="badge badge-{{ $class->students_count > 0 ? 'success' : 'secondary' }}">
                                            {{ $class->students_count }} طالب
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>نسبة الامتلاء:</th>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $class->occupancy_rate > 80 ? 'bg-danger' : ($class->occupancy_rate > 50 ? 'bg-warning' : 'bg-success') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $class->occupancy_rate }}%"
                                                 aria-valuenow="{{ $class->occupancy_rate }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ $class->occupancy_rate_text }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($class->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">وصف الفصل</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $class->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- قسم الطلاب -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-users"></i> الطلاب
                                    </h5>
                                    <span class="badge bg-light text-dark">{{ $class->students->count() }} طالب</span>
                                </div>
                                <div class="card-body">
                                    @if($class->students->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>اسم الطالب</th>
                                                        <th>الجنسية</th>
                                                        <th>تاريخ الميلاد</th>
                                                        <th>رقم الجوال</th>
                                                        <th>الحالة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($class->students as $index => $student)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $student->full_name }}</td>
                                                            <td>{{ $student->nationality ?? 'غير محدد' }}</td>
                                                            <td>{{ $student->birth_date ?? 'غير محدد' }}</td>
                                                            <td>{{ $student->phone ?? '-' }}</td>
                                                            <td>
                                                                <span class="badge badge-{{ $student->is_active ? 'success' : 'secondary' }}">
                                                                    {{ $student->is_active ? 'نشط' : 'غير نشط' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-warning text-center">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            لا يوجد طلاب في هذا الفصل حالياً.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- قسم المواد الدراسية -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-book"></i> المواد الدراسية
                                    </h5>
                                    <span class="badge bg-light text-dark">{{ $class->subjects->count() }} مادة</span>
                                </div>
                                <div class="card-body">
                                    @if($class->subjects->count() > 0)
                                        <div class="row">
                                            @foreach($class->subjects as $subject)
                                                <div class="col-md-4 mb-3">
                                                    <div class="card h-100 border-info">
                                                        <div class="card-body">
                                                            <h5 class="card-title text-info">
                                                                <i class="fas fa-book-open"></i> {{ $subject->name_ar }}
                                                            </h5>
                                                            <p class="mb-1"><strong>رمز المادة:</strong> {{ $subject->code }}</p>
                                                            <p class="mb-1"><strong>النوع:</strong> {{ $subject->type }}</p>
                                                            <p class="mb-0"><strong>الوصف:</strong> {{ $subject->description ?? '—' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info text-center">
                                            <i class="fas fa-info-circle"></i>
                                            لم يتم إضافة مواد دراسية لهذا الفصل بعد.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('classes.edit', $class) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('classes.toggle-status', $class) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $class->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $class->is_active ? 'pause' : 'play' }}"></i>
                            {{ $class->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
