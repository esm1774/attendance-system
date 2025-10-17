@extends('layouts.app')

@section('title', 'تفاصيل المرحلة: ' . $stage->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل المرحلة: {{ $stage->name_ar }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('stages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">المدرسة:</th>
                                    <td>
                                        <span class="badge badge-info">{{ $stage->school->name_ar }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>اسم المرحلة (إنجليزي):</th>
                                    <td>{{ $stage->name }}</td>
                                </tr>
                                <tr>
                                    <th>اسم المرحلة (عربي):</th>
                                    <td>{{ $stage->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>رمز المرحلة:</th>
                                    <td>{{ $stage->code ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>ترتيب العرض:</th>
                                    <td>
                                        <span class="badge badge-secondary">{{ $stage->order }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">الحالة:</th>
                                    <td>
                                        <span class="badge badge-{{ $stage->is_active ? 'success' : 'danger' }}">
                                            {{ $stage->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>نطاق العمر:</th>
                                    <td>{{ $stage->age_range }}</td>
                                </tr>
                                <tr>
                                    <th>عدد الصفوف:</th>
                                    <td>
                                        <span class="badge badge-primary">{{ $stage->grades_count }} صف</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $stage->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ $stage->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($stage->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">وصف المرحلة</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $stage->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- قسم الصفوف (سيتم إضافته لاحقاً) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-graduation-cap"></i> الصفوف الدراسية
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($stage->grades_count > 0)
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            تحتوي المرحلة على {{ $stage->grades_count }} صف دراسي
                                        </div>
                                        <a href="#" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i> عرض الصفوف
                                        </a>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            لا توجد صفوف دراسية مضافة لهذه المرحلة بعد.
                                        </div>
                                        <a href="#" class="btn btn-outline-primary">
                                            <i class="fas fa-plus"></i> إضافة صف دراسي
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('stages.edit', $stage) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('stages.toggle-status', $stage) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $stage->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $stage->is_active ? 'pause' : 'play' }}"></i>
                            {{ $stage->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection