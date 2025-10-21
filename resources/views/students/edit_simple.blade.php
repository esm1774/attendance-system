@extends('layouts.app')

@section('title', 'تعديل بيانات الطالب')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل بيانات الطالب</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="bg-light p-2 mb-3">
                                    <i class="fas fa-user-graduate"></i> المعلومات الأساسية
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_id">الفصل الدراسي *</label>
                                    <select class="form-control @error('class_id') is-invalid @enderror"
                                            id="class_id" name="class_id" required>
                                        <option value="">اختر الفصل</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', $student->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                                {{ $class->grade->name_ar ?? 'غير محدد' }} - {{ $class->name_ar ?? 'غير محدد' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="full_name">اسم الطالب الكامل *</label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                           id="full_name" name="full_name"
                                           value="{{ old('full_name', $student->full_name ?? '') }}"
                                           placeholder="أدخل اسم الطالب الكامل" required>
                                    @error('full_name')
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
                                    <label for="identity_number">رقم الهوية</label>
                                    <input type="text" class="form-control @error('identity_number') is-invalid @enderror"
                                           id="identity_number" name="identity_number"
                                           value="{{ old('identity_number', $student->identity_number ?? '') }}"
                                           placeholder="أدخل رقم الهوية">
                                    @if(empty($student->identity_number))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('identity_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="birth_date">تاريخ الميلاد</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                           id="birth_date" name="birth_date"
                                           value="{{ old('birth_date', $student->birth_date ?? '') }}"
                                           max="{{ date('Y-m-d') }}">
                                    @if(empty($student->birth_date))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('birth_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">الجنس</label>
                                    <select class="form-control @error('gender') is-invalid @enderror"
                                            id="gender" name="gender">
                                        <option value="">اختر الجنس</option>
                                        <option value="male" {{ old('gender', $student->gender ?? '') == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                    @if(empty($student->gender))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('gender')
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
                                    <label for="nationality">الجنسية</label>
                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror"
                                           id="nationality" name="nationality"
                                           value="{{ old('nationality', $student->nationality ?? '') }}"
                                           placeholder="الجنسية">
                                    @if(empty($student->nationality))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('nationality')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">رقم هاتف الطالب</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone"
                                           value="{{ old('phone', $student->phone ?? '') }}"
                                           placeholder="رقم هاتف الطالب">
                                    @if(empty($student->phone))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">البريد الإلكتروني للطالب</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email"
                                           value="{{ old('email', $student->email ?? '') }}"
                                           placeholder="البريد الإلكتروني">
                                    @if(empty($student->email))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="bg-light p-2 mb-3">
                                    <i class="fas fa-user-shield"></i> بيانات ولي الأمر
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_name">اسم ولي الأمر</label>
                                    <input type="text" class="form-control @error('guardian_name') is-invalid @enderror"
                                           id="guardian_name" name="guardian_name"
                                           value="{{ old('guardian_name', $student->guardian_name ?? '') }}"
                                           placeholder="اسم ولي الأمر">
                                    @if(empty($student->guardian_name))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('guardian_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_relation">صلة القرابة</label>
                                    <input type="text" class="form-control @error('guardian_relation') is-invalid @enderror"
                                           id="guardian_relation" name="guardian_relation"
                                           value="{{ old('guardian_relation', $student->guardian_relation ?? '') }}"
                                           placeholder="الأب، الأم، الوصي">
                                    @if(empty($student->guardian_relation))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('guardian_relation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_phone">رقم هاتف ولي الأمر</label>
                                    <input type="text" class="form-control @error('guardian_phone') is-invalid @enderror"
                                           id="guardian_phone" name="guardian_phone"
                                           value="{{ old('guardian_phone', $student->guardian_phone ?? '') }}"
                                           placeholder="رقم هاتف ولي الأمر">
                                    @if(empty($student->guardian_phone))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('guardian_phone')
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
                                    <label for="guardian_email">البريد الإلكتروني لولي الأمر</label>
                                    <input type="email" class="form-control @error('guardian_email') is-invalid @enderror"
                                           id="guardian_email" name="guardian_email"
                                           value="{{ old('guardian_email', $student->guardian_email ?? '') }}"
                                           placeholder="البريد الإلكتروني لولي الأمر">
                                    @if(empty($student->guardian_email))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('guardian_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="enrollment_date">تاريخ الالتحاق بالمدرسة</label>
                                    <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror"
                                           id="enrollment_date" name="enrollment_date"
                                           value="{{ old('enrollment_date', $student->enrollment_date ?? '') }}">
                                    @if(empty($student->enrollment_date))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('enrollment_date')
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
                                    <label for="status">الحالة</label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="">اختر الحالة</option>
                                        <option value="active" {{ old('status', $student->status ?? '') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="transferred" {{ old('status', $student->status ?? '') == 'transferred' ? 'selected' : '' }}>منقول</option>
                                        <option value="graduated" {{ old('status', $student->status ?? '') == 'graduated' ? 'selected' : '' }}>متخرج</option>
                                        <option value="withdrawn" {{ old('status', $student->status ?? '') == 'withdrawn' ? 'selected' : '' }}>منسحب</option>
                                    </select>
                                    @if(empty($student->status))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">الحالة النشطة</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input @error('is_active') is-invalid @enderror"
                                               type="checkbox" id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $student->is_active ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            نشط
                                        </label>
                                    </div>
                                    @error('is_active')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">ملاحظات عامة</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              id="notes" name="notes" rows="3"
                                              placeholder="أي ملاحظات إضافية">{{ old('notes', $student->notes ?? '') }}</textarea>
                                    @if(empty($student->notes))
                                        <small class="text-muted">غير محدد</small>
                                    @endif
                                    @error('notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ التغييرات
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
