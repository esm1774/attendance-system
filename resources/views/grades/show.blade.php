@extends('layouts.app')

@section('title', 'تفاصيل الصف: ' . $grade->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل الصف: {{ $grade->name_ar }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">اسم الصف (إنجليزي):</th>
                                    <td>{{ $grade->name }}</td>
                                </tr>
                                <tr>
                                    <th>اسم الصف (عربي):</th>
                                    <td>{{ $grade->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>المرحلة الدراسية:</th>
                                    <td>
                                        <span class="badge badge-info">{{ $grade->stage->name_ar }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>المدرسة:</th>
                                    <td>{{ $grade->stage->school->name_ar }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">ترتيب الصف:</th>
                                    <td>
                                        <span class="badge badge-secondary">{{ $grade->order }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>
                                        <span class="badge badge-{{ $grade->is_active ? 'success' : 'danger' }}">
                                            {{ $grade->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>عدد الفصول:</th>
                                    <td>
                                        <span class="badge badge-primary">{{ $grade->classes_count }} فصل</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $grade->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($grade->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">وصف الصف</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $grade->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- قسم الفصول الدراسية -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-door-open"></i> الفصول الدراسية
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($grade->classes_count > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>اسم الفصل</th>
                                                        <th>السعة</th>
                                                        <th>المعلم المسؤول</th>
                                                        <th>الحالة</th>
                                                        <th>الإجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($grade->classes as $class)
                                                    <tr>
                                                        <td>{{ $class->name_ar }}</td>
                                                        <td>
                                                            <span class="badge badge-secondary">{{ $class->capacity }} طالب</span>
                                                        </td>
                                                        <td>
                                                            @if($class->teacher)
                                                                {{ $class->teacher->name }}
                                                            @else
                                                                <span class="text-muted">غير محدد</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-{{ $class->is_active ? 'success' : 'danger' }}">
                                                                {{ $class->is_active ? 'نشط' : 'غير نشط' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info text-center">
                                            <i class="fas fa-info-circle"></i>
                                            لا توجد فصول دراسية مضافة لهذا الصف بعد.
                                        </div>
                                        <div class="text-center">
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="fas fa-plus"></i> إضافة فصل دراسي
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('grades.edit', $grade) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('grades.toggle-status', $grade) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $grade->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $grade->is_active ? 'pause' : 'play' }}"></i>
                            {{ $grade->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-door-open"></i> إدارة الفصول
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection