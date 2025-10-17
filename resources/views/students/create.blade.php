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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="student_id">الرقم الجامعي/المدرسي *</label>
                                    <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                           id="student_id" name="student_id" value="{{ old('student_id') }}" 
                                           placeholder="أدخل الرقم الجامعي" required>
                                    @error('student_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="national_id">الرقم الوطني</label>
                                    <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                           id="national_id" name="national_id" value="{{ old('national_id') }}" 
                                           placeholder="أدخل الرقم الوطني">
                                    @error('national_id')
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
                                    <label for="first_name">الاسم الأول (إنجليزي) *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                           placeholder="الاسم الأول بالإنجليزية" required>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middle_name">الاسم الأوسط (إنجليزي)</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                           id="middle_name" name="middle_name" value="{{ old('middle_name') }}" 
                                           placeholder="الاسم الأوسط بالإنجليزية">
                                    @error('middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name">الاسم الأخير (إنجليزي) *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                           placeholder="الاسم الأخير بالإنجليزية" required>
                                    @error('last_name')
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
                                    <label for="first_name_ar">الاسم الأول (عربي) *</label>
                                    <input type="text" class="form-control @error('first_name_ar') is-invalid @enderror" 
                                           id="first_name_ar" name="first_name_ar" value="{{ old('first_name_ar') }}" 
                                           placeholder="الاسم الأول بالعربية" required>
                                    @error('first_name_ar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middle_name_ar">الاسم الأوسط (عربي)</label>
                                    <input type="text" class="form-control @error('middle_name_ar') is-invalid @enderror" 
                                           id="middle_name_ar" name="middle_name_ar" value="{{ old('middle_name_ar') }}" 
                                           placeholder="الاسم الأوسط بالعربية">
                                    @error('middle_name_ar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name_ar">الاسم الأخير (عربي) *</label>
                                    <input type="text" class="form-control @error('last_name_ar') is-invalid @enderror" 
                                           id="last_name_ar" name="last_name_ar" value="{{ old('last_name_ar') }}" 
                                           placeholder="الاسم الأخير بالعربية" required>
                                    @error('last_name_ar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="birth_date">تاريخ الميلاد *</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" value="{{ old('birth_date') }}" 
                                           max="{{ date('Y-m-d') }}" required>
                                    @error('birth_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender">الجنس *</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender" required>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nationality">الجنسية *</label>
                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror" 
                                           id="nationality" name="nationality" value="{{ old('nationality', 'سعودي') }}" 
                                           placeholder="الجنسية" required>
                                    @error('nationality')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="religion">الديانة *</label>
                                    <input type="text" class="form-control @error('religion') is-invalid @enderror" 
                                           id="religion" name="religion" value="{{ old('religion', 'مسلم') }}" 
                                           placeholder="الديانة" required>
                                    @error('religion')
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
                                    <label for="guardian_name">اسم ولي الأمر *</label>
                                    <input type="text" class="form-control @error('guardian_name') is-invalid @enderror" 
                                           id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}" 
                                           placeholder="اسم ولي الأمر" required>
                                    @error('guardian_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_relation">صلة القرابة *</label>
                                    <input type="text" class="form-control @error('guardian_relation') is-invalid @enderror" 
                                           id="guardian_relation" name="guardian_relation" value="{{ old('guardian_relation') }}" 
                                           placeholder="الأب، الأم، الوصي" required>
                                    @error('guardian_relation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guardian_phone">هاتف ولي الأمر *</label>
                                    <input type="text" class="form-control @error('guardian_phone') is-invalid @enderror" 
                                           id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone') }}" 
                                           placeholder="هاتف ولي الأمر" required>
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
                                    <label for="guardian_email">بريد ولي الأمر</label>
                                    <input type="email" class="form-control @error('guardian_email') is-invalid @enderror" 
                                           id="guardian_email" name="guardian_email" value="{{ old('guardian_email') }}" 
                                           placeholder="بريد ولي الأمر">
                                    @error('guardian_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_phone">هاتف الطوارئ</label>
                                    <input type="text" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                           id="emergency_phone" name="emergency_phone" value="{{ old('emergency_phone') }}" 
                                           placeholder="هاتف الطوارئ">
                                    @error('emergency_phone')
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
                                    <i class="fas fa-heartbeat"></i> المعلومات الطبية
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="blood_type">فصيلة الدم</label>
                                    <select class="form-control @error('blood_type') is-invalid @enderror" 
                                            id="blood_type" name="blood_type">
                                        <option value="">اختر فصيلة الدم</option>
                                        <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    @error('blood_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="allergies">الحساسيات</label>
                                    <input type="text" class="form-control @error('allergies') is-invalid @enderror" 
                                           id="allergies" name="allergies" value="{{ old('allergies') }}" 
                                           placeholder="الحساسيات (مفصولة بفواصل)">
                                    @error('allergies')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="medical_notes">ملاحظات طبية</label>
                            <textarea class="form-control @error('medical_notes') is-invalid @enderror" 
                                      id="medical_notes" name="medical_notes" rows="3" 
                                      placeholder="أي ملاحظات طبية مهمة">{{ old('medical_notes') }}</textarea>
                            @error('medical_notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="bg-light p-2 mb-3">
                                    <i class="fas fa-info-circle"></i> معلومات إضافية
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="enrollment_date">تاريخ التسجيل *</label>
                                    <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror" 
                                           id="enrollment_date" name="enrollment_date" value="{{ old('enrollment_date', date('Y-m-d')) }}" 
                                           required>
                                    @error('enrollment_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="enrollment_type">نوع التسجيل *</label>
                                    <select class="form-control @error('enrollment_type') is-invalid @enderror" 
                                            id="enrollment_type" name="enrollment_type" required>
                                        <option value="new" {{ old('enrollment_type') == 'new' ? 'selected' : '' }}>جديد</option>
                                        <option value="transferred" {{ old('enrollment_type') == 'transferred' ? 'selected' : '' }}>منقول</option>
                                    </select>
                                    @error('enrollment_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">حالة الطالب *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="transferred" {{ old('status') == 'transferred' ? 'selected' : '' }}>منقول</option>
                                        <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>متخرج</option>
                                        <option value="withdrawn" {{ old('status') == 'withdrawn' ? 'selected' : '' }}>منسحب</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

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

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">طالب نشط</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection