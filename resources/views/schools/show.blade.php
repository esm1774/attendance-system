@extends('layouts.app')

@section('title', 'تفاصيل المدرسة: ' . $school->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل المدرسة: {{ $school->name_ar }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('schools.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">رمز المدرسة:</th>
                                    <td>
                                        <span class="badge badge-secondary">{{ $school->code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>اسم المدرسة (إنجليزي):</th>
                                    <td>{{ $school->name }}</td>
                                </tr>
                                <tr>
                                    <th>اسم المدرسة (عربي):</th>
                                    <td>{{ $school->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>اسم المدير:</th>
                                    <td>{{ $school->principal_name ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>سنة التأسيس:</th>
                                    <td>{{ $school->established_year ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">الحالة:</th>
                                    <td>
                                        <span class="badge badge-{{ $school->is_active ? 'success' : 'danger' }}">
                                            {{ $school->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>رقم الهاتف:</th>
                                    <td>{{ $school->phone ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>البريد الإلكتروني:</th>
                                    <td>{{ $school->email ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>عدد المراحل:</th>
                                    <td>
                                        <span class="badge badge-info">{{ $school->stages_count }} مرحلة</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>عدد المستخدمين:</th>
                                    <td>
                                        <span class="badge badge-primary">{{ $school->users_count }} مستخدم</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($school->address)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-map-marker-alt"></i> العنوان
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $school->address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($school->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle"></i> وصف المدرسة
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $school->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- قسم المراحل (سيتم إضافته لاحقاً) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-layer-group"></i> المراحل الدراسية
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($school->stages_count > 0)
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            تحتوي المدرسة على {{ $school->stages_count }} مرحلة دراسية
                                        </div>
                                        <a href="#" class="btn btn-outline-info">
                                            <i class="fas fa-eye"></i> عرض المراحل
                                        </a>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            لا توجد مراحل دراسية مضافة لهذه المدرسة بعد.
                                        </div>
                                        <a href="#" class="btn btn-outline-primary">
                                            <i class="fas fa-plus"></i> إضافة مرحلة دراسية
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- قسم المستخدمين (سيتم إضافته لاحقاً) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-users"></i> المستخدمون
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($school->users_count > 0)
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            يوجد {{ $school->users_count }} مستخدم مرتبط بهذه المدرسة
                                        </div>
                                        <a href="#" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i> عرض المستخدمين
                                        </a>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            لا يوجد مستخدمون مرتبطون بهذه المدرسة حالياً.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('schools.edit', $school) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('schools.toggle-status', $school) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $school->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $school->is_active ? 'pause' : 'play' }}"></i>
                            {{ $school->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection