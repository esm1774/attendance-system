@extends('layouts.app')

@section('title', 'إضافة فصل جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة فصل جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grade_id">الصف الدراسي</label>
                                    <select class="form-control @error('grade_id') is-invalid @enderror" 
                                            id="grade_id" name="grade_id" required>
                                        <option value="">اختر الصف الدراسي</option>
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                                {{ $grade->full_path_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grade_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id">مرشد الفصل (اختياري)</label>
                                    <select class="form-control @error('teacher_id') is-invalid @enderror" 
                                            id="teacher_id" name="teacher_id">
                                        <option value="">اختر مرشد الفصل</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} - {{ $teacher->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
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
                                    <label for="name">اسم الفصل (إنجليزي)</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="أدخل اسم الفصل بالإنجليزية" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_ar">اسم الفصل (عربي)</label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                           id="name_ar" name="name_ar" value="{{ old('name_ar') }}" 
                                           placeholder="أدخل اسم الفصل بالعربية" required>
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
                                    <label for="code">رمز الفصل</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}" 
                                           placeholder="أدخل رمز الفصل (مثال: G1-A)" required>
                                    <small class="form-text text-muted">يجب أن يكون الرمز فريداً وغير مكرر</small>
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_number">رقم القاعة</label>
                                    <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                           id="room_number" name="room_number" value="{{ old('room_number') }}" 
                                           placeholder="أدخل رقم القاعة">
                                    @error('room_number')
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
                                    <label for="capacity">سعة الفصل</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" value="{{ old('capacity', 30) }}" 
                                           min="1" max="100" required>
                                    <small class="form-text text-muted">الحد الأقصى لعدد الطلاب في الفصل</small>
                                    @error('capacity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">وصف الفصل</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="أدخل وصفاً للفصل">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">فصل نشط</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ
                        </button>
                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection