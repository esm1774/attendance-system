@extends('layouts.app')

@section('title', 'إضافة صف جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة صف جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <form action="{{ route('grades.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">اسم الصف (إنجليزي)</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="أدخل اسم الصف بالإنجليزية" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_ar">اسم الصف (عربي)</label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                           id="name_ar" name="name_ar" value="{{ old('name_ar') }}" 
                                           placeholder="أدخل اسم الصف بالعربية" required>
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
                                    <label for="stage_id">المرحلة الدراسية</label>
                                    <select class="form-control @error('stage_id') is-invalid @enderror" 
                                            id="stage_id" name="stage_id" required>
                                        <option value="">اختر المرحلة الدراسية</option>
                                        @foreach($stages as $stage)
                                            <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>
                                                {{ $stage->name_ar }} - {{ $stage->school->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('stage_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">ترتيب الصف</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order') }}" 
                                           min="1" max="12" 
                                           placeholder="أدخل ترتيب الصف (1-12)" required>
                                    <small class="form-text text-muted">يحدد ترتيب العرض (1 للصف الأول، 2 للثاني، إلخ)</small>
                                    @error('order')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">وصف الصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="أدخل وصفاً للصف">{{ old('description') }}</textarea>
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
                                <label class="form-check-label" for="is_active">صف نشط</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ
                        </button>
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection