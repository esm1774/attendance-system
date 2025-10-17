@extends('layouts.app')

@section('title', 'تفاصيل المادة: ' . $subject->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل المادة: {{ $subject->name_ar }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">رمز المادة:</th>
                                    <td>
                                        <span class="badge badge-secondary">{{ $subject->code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>اسم المادة (إنجليزي):</th>
                                    <td>{{ $subject->name }}</td>
                                </tr>
                                <tr>
                                    <th>اسم المادة (عربي):</th>
                                    <td>{{ $subject->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>نوع المادة:</th>
                                    <td>
                                        <span class="badge badge-{{ $subject->type == 'mandatory' ? 'primary' : 'success' }}">
                                            {{ $subject->type_text }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">الحالة:</th>
                                    <td>
                                        <span class="badge badge-{{ $subject->is_active ? 'success' : 'danger' }}">
                                            {{ $subject->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $subject->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ $subject->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>عدد المعلمين:</th>
                                    <td>
                                        <span class="badge badge-info">0 معلم</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($subject->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">وصف المادة</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $subject->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- قسم المعلمين (سيتم إضافته لاحقاً) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chalkboard-teacher"></i> المعلمون
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        نظام ربط المعلمين سيتم إضافته في المرحلة القادمة.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('subjects.toggle-status', $subject) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $subject->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $subject->is_active ? 'pause' : 'play' }}"></i>
                            {{ $subject->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection