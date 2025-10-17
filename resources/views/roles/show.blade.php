@extends('layouts.app')

@section('title', 'عرض الدور: ' . $role->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل الدور: {{ $role->name_ar }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">اسم الدور (إنجليزي):</th>
                                    <td>
                                        <span class="badge badge-info">{{ $role->name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>اسم الدور (عربي):</th>
                                    <td>{{ $role->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>الوصف:</th>
                                    <td>{{ $role->description ?? 'لا يوجد وصف' }}</td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>
                                        <span class="badge badge-{{ $role->is_active ? 'success' : 'danger' }}">
                                            {{ $role->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $role->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ $role->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>المستخدمون التابعون لهذا الدور</h5>
                            @if($role->users->count() > 0)
                                <div class="list-group">
                                    @foreach($role->users as $user)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                            <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                                {{ $user->is_active ? 'نشط' : 'معطل' }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    لا يوجد مستخدمون تابعون لهذا الدور.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>الصلاحيات الممنوحة</h5>
                            <div class="row">
                                @foreach($role->permissions->groupBy('group') as $group => $groupPermissions)
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
                                                <br>
                                                <small class="text-muted">{{ $permission->name }}</small>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('roles.toggle-status', $role) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $role->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $role->is_active ? 'pause' : 'play' }}"></i>
                            {{ $role->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection