@extends('layouts.app')

@section('title', 'تعديل المرحلة: ' . $stage->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل المرحلة: {{ $stage->name_ar }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('stages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <form action="{{ route('stages.update', $stage) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school_id">المدرسة</label>
                                    <select class="form-control @error('school_id') is-invalid @enderror" 
                                            id="school_id" name="school_id" required>
                                        <option value="">اختر المدرسة</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" 
                                                {{ old('school_id', $stage->school_id) == $school->id ? 'selected' : '' }}>
                                                {{ $school->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">رمز المرحلة (اختياري)</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $stage->code) }}" 
                                           placeholder="أدخل رمز المرحلة">
                                    @error('code')
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
                                    <label for="name">اسم المرحلة (إنجليزي)</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $stage->name) }}" 
                                           placeholder="أدخل اسم المرحلة بالإنجليزية" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_ar">اسم المرحلة (عربي)</label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                           id="name_ar" name="name_ar" value="{{ old('name_ar', $stage->name_ar) }}" 
                                           placeholder="أدخل اسم المرحلة بالعربية" required>
                                    @error('name_ar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order">ترتيب العرض</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order', $stage->order) }}" 
                                           min="0" placeholder="ترتيب العرض">
                                    @error('order')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="min_age">الحد الأدنى للعمر</label>
                                    <input type="number" class="form-control @error('min_age') is-invalid @enderror" 
                                           id="min_age" name="min_age" value="{{ old('min_age', $stage->min_age) }}" 
                                           min="3" max="20" placeholder="الحد الأدنى للعمر">
                                    @error('min_age')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="max_age">الحد الأقصى للعمر</label>
                                    <input type="number" class="form-control @error('max_age') is-invalid @enderror" 
                                           id="max_age" name="max_age" value="{{ old('max_age', $stage->max_age) }}" 
                                           min="3" max="20" placeholder="الحد الأقصى للعمر">
                                    @error('max_age')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">وصف المرحلة</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="أدخل وصفاً للمرحلة">{{ old('description', $stage->description) }}</textarea>
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
                                       {{ old('is_active', $stage->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">مرحلة نشطة</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> تحديث
                        </button>
                        <a href="{{ route('stages.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection