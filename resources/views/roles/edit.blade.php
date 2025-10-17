@extends('layouts.app')

@section('title', 'تعديل الدور')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل الدور: {{ $role->name_ar }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">اسم الدور (إنجليزي)</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $role->name) }}" 
                                           placeholder="أدخل اسم الدور بالإنجليزية" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_ar">اسم الدور (عربي)</label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                           id="name_ar" name="name_ar" value="{{ old('name_ar', $role->name_ar) }}" 
                                           placeholder="أدخل اسم الدور بالعربية" required>
                                    @error('name_ar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">وصف الدور</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="أدخل وصفاً للدور">{{ old('description', $role->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">حالة الدور</label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" 
                                            id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', $role->is_active) ? 'selected' : '' }}>نشط</option>
                                        <option value="0" {{ !old('is_active', $role->is_active) ? 'selected' : '' }}>معطل</option>
                                    </select>
                                    @error('is_active')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>الصلاحيات</label>
                            <div class="row">
                                @foreach($permissions as $group => $groupPermissions)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="card-title mb-0">
                                                <strong>{{ $group }}</strong>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($groupPermissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="permissions[]" value="{{ $permission->id }}" 
                                                       id="permission_{{ $permission->id }}"
                                                       {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->name_ar }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> تحديث
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection