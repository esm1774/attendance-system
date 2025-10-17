@extends('layouts.app')

@section('title', 'تفاصيل المستخدم: ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل المستخدم: {{ $user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">الصورة:</th>
                                    <td>
                                        <div class="user-avatar-large">
                                            <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>الاسم الكامل:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>البريد الإلكتروني:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>رقم الهاتف:</th>
                                    <td>{{ $user->phone ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>العنوان:</th>
                                    <td>{{ $user->address ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">الدور:</th>
                                    <td>
                                        <span class="badge badge-info">{{ $user->role->name_ar ?? 'بدون دور' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>
                                        <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                            {{ $user->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>عدد الصلاحيات الخاصة:</th>
                                    <td>
                                        <span class="badge badge-warning">{{ $user->userPermissions->count() }} صلاحية</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- الصلاحيات -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>الصلاحيات الممنوحة</h5>
                            
                            <!-- صلاحيات الدور -->
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-user-tag"></i> صلاحيات الدور: {{ $user->role->name_ar }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($user->role->permissions->groupBy('group') as $group => $groupPermissions)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="card-title mb-0">
                                                        <strong>{{ $group }}</strong>
                                                        <span class="badge badge-primary float-right">{{ $groupPermissions->count() }}</span>
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($groupPermissions as $permission)
                                                    <div class="mb-2">
                                                        <i class="fas fa-check text-success"></i>
                                                        {{ $permission->name_ar }}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- الصلاحيات الخاصة -->
                            @if($user->userPermissions->count() > 0)
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-key"></i> الصلاحيات الخاصة الإضافية
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($user->userPermissions->groupBy('permission.group') as $group => $groupPermissions)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="card-title mb-0">
                                                        <strong>{{ $group }}</strong>
                                                        <span class="badge badge-warning float-right">{{ $groupPermissions->count() }}</span>
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($groupPermissions as $userPermission)
                                                    <div class="mb-2">
                                                        <i class="fas fa-plus text-warning"></i>
                                                        {{ $userPermission->permission->name_ar }}
                                                        @if($userPermission->expires_at)
                                                            <br>
                                                            <small class="text-muted">
                                                                تنتهي في: {{ $userPermission->expires_at->format('Y-m-d') }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }}"
                                {{ $user->email === 'admin@school.com' && $user->is_active ? 'disabled' : '' }}>
                            <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                            {{ $user->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .user-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }
</style>
@endsection