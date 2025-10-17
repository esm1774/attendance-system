@extends('layouts.app')

@section('title', 'تفاصيل المعلم: ' . $teacher->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل المعلم: {{ $teacher->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">الرقم الوظيفي:</th>
                                    <td>
                                        <span class="badge badge-info">{{ $teacher->teacher_id }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>الاسم الكامل:</th>
                                    <td>{{ $teacher->name }}</td>
                                </tr>
                                <tr>
                                    <th>البريد الإلكتروني:</th>
                                    <td>{{ $teacher->email }}</td>
                                </tr>
                                <tr>
                                    <th>رقم الهاتف:</th>
                                    <td>{{ $teacher->phone ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>التخصص:</th>
                                    <td>{{ $teacher->specialization }}</td>
                                </tr>
                                <tr>
                                    <th>المؤهل العلمي:</th>
                                    <td>{{ $teacher->qualification }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">الحالة:</th>
                                    <td>
                                        <span class="badge badge-{{ $teacher->is_active ? 'success' : 'danger' }}">
                                            {{ $teacher->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>سنوات الخبرة:</th>
                                    <td>{{ $teacher->years_of_experience }} سنة</td>
                                </tr>
                                <tr>
                                    <th>تاريخ التعيين:</th>
                                    <td>{{ $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>نوع التعيين:</th>
                                    <td>
                                        <span class="badge badge-{{ $teacher->employment_type == 'full_time' ? 'success' : 'warning' }}">
                                            {{ $teacher->employment_type_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>الراتب:</th>
                                    <td>{{ $teacher->salary ? number_format($teacher->salary, 2) . ' ريال' : 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $teacher->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($teacher->address)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-map-marker-alt"></i> العنوان
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $teacher->address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($teacher->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-sticky-note"></i> الملاحظات
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $teacher->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- قسم المواد (سيتم إضافته لاحقاً) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-book"></i> المواد الدراسية
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        نظام ربط المواد سيتم إضافته في المرحلة القادمة.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- قسم الفصول (سيتم إضافته لاحقاً) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chalkboard-teacher"></i> الفصول المشرف عليها
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        نظام ربط الفصول سيتم إضافته في المرحلة القادمة.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('teachers.toggle-status', $teacher) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $teacher->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $teacher->is_active ? 'pause' : 'play' }}"></i>
                            {{ $teacher->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection