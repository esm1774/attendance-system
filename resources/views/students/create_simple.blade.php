@extends('layouts.app')

@section('title', 'إضافة طالب جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة طالب جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.download-template') }}" class="btn btn-info">
                            <i class="fas fa-download"></i> تحميل النموذج
                        </a>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#importModal">
                            <i class="fas fa-file-import"></i> استيراد
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
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
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->grade->name_ar }} - {{ $class->name_ar }}
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
                                    <label for="student_id">الرقم الجامعي/المدرسي</label>
                                    <input type="text" class="form-control @error('student_id') is-invalid @enderror"
                                           id="student_id" name="student_id" value="{{ old('student_id') }}"
                                           placeholder="أدخل الرقم الجامعي">
                                    @error('student_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="full_name">اسم الطالب الكامل *</label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                           id="full_name" name="full_name" value="{{ old('full_name') }}"
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
                                    <label for="national_id">رقم الهوية</label>
                                    <input type="text" class="form-control @error('national_id') is-invalid @enderror"
                                           id="national_id" name="national_id" value="{{ old('national_id') }}"
                                           placeholder="أدخل رقم الهوية">
                                    @error('national_id')
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
                                           id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                           max="{{ date('Y-m-d') }}">
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
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
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
                                           id="nationality" name="nationality" value="{{ old('nationality', 'سعودي') }}"
                                           placeholder="الجنسية">
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
                                           id="phone" name="phone" value="{{ old('phone') }}"
                                           placeholder="رقم هاتف الطالب">
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
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="البريد الإلكتروني">
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
                                           id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}"
                                           placeholder="اسم ولي الأمر">
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
                                           id="guardian_relation" name="guardian_relation" value="{{ old('guardian_relation') }}"
                                           placeholder="الأب، الأم، الوصي">
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
                                           id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone') }}"
                                           placeholder="رقم هاتف ولي الأمر">
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
                                           id="guardian_email" name="guardian_email" value="{{ old('guardian_email') }}"
                                           placeholder="البريد الإلكتروني لولي الأمر">
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
                                           id="enrollment_date" name="enrollment_date" value="{{ old('enrollment_date', date('Y-m-d')) }}">
                                    @error('enrollment_date')
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
                                              placeholder="أي ملاحظات إضافية">{{ old('notes') }}</textarea>
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
                            <i class="fas fa-save"></i> حفظ
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

<!-- Modal للاستيراد -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">استيراد الطلاب</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">اختر ملف Excel</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">الرجاء اختيار ملف Excel أو CSV بالصيغة الصحيحة</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-file-import"></i> استيراد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
