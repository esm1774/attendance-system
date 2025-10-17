@extends('layouts.app')

@section('title', 'إضافة معلم جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة معلم جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <form action="{{ route('teachers.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">الاسم الكامل</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="أدخل الاسم الكامل" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">البريد الإلكتروني</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="أدخل البريد الإلكتروني" required>
                                    @error('email')
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
                                    <label for="password">كلمة المرور</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           placeholder="أدخل كلمة المرور" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">تأكيد كلمة المرور</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="أعد إدخال كلمة المرور" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id">الرقم الوظيفي</label>
                                    <input type="text" class="form-control @error('teacher_id') is-invalid @enderror" 
                                           id="teacher_id" name="teacher_id" value="{{ old('teacher_id') }}" 
                                           placeholder="أدخل الرقم الوظيفي" required>
                                    <small class="form-text text-muted">يجب أن يكون الرقم فريداً وغير مكرر</small>
                                    @error('teacher_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="specialization">التخصص</label>
                                    <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                           id="specialization" name="specialization" value="{{ old('specialization') }}" 
                                           placeholder="أدخل التخصص" required>
                                    @error('specialization')
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
                                    <label for="qualification">المؤهل العلمي</label>
                                    <input type="text" class="form-control @error('qualification') is-invalid @enderror" 
                                           id="qualification" name="qualification" value="{{ old('qualification') }}" 
                                           placeholder="أدخل المؤهل العلمي" required>
                                    @error('qualification')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="years_of_experience">سنوات الخبرة</label>
                                    <input type="number" class="form-control @error('years_of_experience') is-invalid @enderror" 
                                           id="years_of_experience" name="years_of_experience" 
                                           value="{{ old('years_of_experience', 0) }}" min="0"
                                           placeholder="أدخل سنوات الخبرة" required>
                                    @error('years_of_experience')
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
                                    <label for="hire_date">تاريخ التعيين</label>
                                    <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                           id="hire_date" name="hire_date" value="{{ old('hire_date') }}" 
                                           required>
                                    @error('hire_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employment_type">نوع التعيين</label>
                                    <select class="form-control @error('employment_type') is-invalid @enderror" 
                                            id="employment_type" name="employment_type" required>
                                        <option value="">اختر نوع التعيين</option>
                                        <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>دوام كامل</option>
                                        <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>دوام جزئي</option>
                                        <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>عقد</option>
                                    </select>
                                    @error('employment_type')
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
                                    <label for="phone">رقم الهاتف</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" 
                                           placeholder="أدخل رقم الهاتف">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salary">الراتب</label>
                                    <input type="number" class="form-control @error('salary') is-invalid @enderror" 
                                           id="salary" name="salary" value="{{ old('salary') }}" 
                                           step="0.01" min="0"
                                           placeholder="أدخل الراتب">
                                    @error('salary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">العنوان</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="أدخل العنوان">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="أدخل ملاحظات إضافية">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>المواد التي يمكن تدريسها</label>
                            <div class="row">
                                @foreach($subjects as $subject)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="subjects[]" value="{{ $subject->id }}" 
                                               id="subject_{{ $subject->id }}"
                                               {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="subject_{{ $subject->id }}">
                                            {{ $subject->name_ar }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">حساب نشط</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ
                        </button>
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection