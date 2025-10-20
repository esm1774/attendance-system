@extends('layouts.app')

@section('title', 'إضافة طالب جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0" style="color: #ffffff;">
                        <i class="fas fa-user-plus ml-2"></i>
                        إضافة طالب جديد
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('students.download-template') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-download ml-1"></i> تحميل النموذج
                        </a>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-file-import ml-1"></i> استيراد
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left ml-1"></i> رجوع
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('students.store') }}" method="POST" id="studentForm">
                    @csrf
                    <div class="card-body">
                        <!-- عرض الأخطاء -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <h6><i class="fas fa-exclamation-triangle ml-1"></i> يرجى تصحيح الأخطاء التالية:</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- المعلومات الأساسية -->
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-user-graduate ml-2"></i> المعلومات الأساسية
                            </h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="class_id" class="form-label required">الفصل الدراسي</label>
                                <select class="form-select @error('class_id') is-invalid @enderror" 
                                        id="class_id" name="class_id" required>
                                    <option value="">اختر الفصل</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->grade->name_ar }} - {{ $class->name_ar }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="national_id" class="form-label required">رقم الهوية</label>
                                <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                       id="national_id" name="national_id" value="{{ old('national_id') }}" 
                                       placeholder="مثال: 1234567890" required>
                                @error('national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="full_name" class="form-label required">اسم الطالب الكامل</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                       id="full_name" name="full_name" value="{{ old('full_name') }}" 
                                       placeholder="أدخل الاسم الكامل للطالب" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="birth_date" class="form-label">تاريخ الميلاد</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date') }}" 
                                       max="{{ date('Y-m-d') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="gender" class="form-label">الجنس</label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        id="gender" name="gender">
                                    <option value="">اختر الجنس</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="nationality" class="form-label">الجنسية</label>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror" 
                                       id="nationality" name="nationality" value="{{ old('nationality', 'سعودي') }}">
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم هاتف الطالب</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="05xxxxxxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="example@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- بيانات ولي الأمر -->
                        <div class="section-header mt-4">
                            <h5 class="section-title">
                                <i class="fas fa-user-shield ml-2"></i> بيانات ولي الأمر
                            </h5>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="guardian_name" class="form-label">اسم ولي الأمر</label>
                                <input type="text" class="form-control @error('guardian_name') is-invalid @enderror" 
                                       id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}" 
                                       placeholder="اسم ولي الأمر">
                                @error('guardian_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="guardian_relation" class="form-label">صلة القرابة</label>
                                <select class="form-select @error('guardian_relation') is-invalid @enderror" 
                                        id="guardian_relation" name="guardian_relation">
                                    <option value="">اختر صلة القرابة</option>
                                    <option value="أب" {{ old('guardian_relation') == 'أب' ? 'selected' : '' }}>الأب</option>
                                    <option value="أم" {{ old('guardian_relation') == 'أم' ? 'selected' : '' }}>الأم</option>
                                    <option value="جد" {{ old('guardian_relation') == 'جد' ? 'selected' : '' }}>الجد</option>
                                    <option value="جدة" {{ old('guardian_relation') == 'جدة' ? 'selected' : '' }}>الجدة</option>
                                    <option value="عم" {{ old('guardian_relation') == 'عم' ? 'selected' : '' }}>العم</option>
                                    <option value="خال" {{ old('guardian_relation') == 'خال' ? 'selected' : '' }}>الخال</option>
                                    <option value="وصي" {{ old('guardian_relation') == 'وصي' ? 'selected' : '' }}>وصي</option>
                                </select>
                                @error('guardian_relation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="guardian_phone" class="form-label">هاتف ولي الأمر</label>
                                <input type="text" class="form-control @error('guardian_phone') is-invalid @enderror" 
                                       id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone') }}" 
                                       placeholder="05xxxxxxxx">
                                @error('guardian_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="guardian_email" class="form-label">بريد ولي الأمر</label>
                                <input type="email" class="form-control @error('guardian_email') is-invalid @enderror" 
                                       id="guardian_email" name="guardian_email" value="{{ old('guardian_email') }}" 
                                       placeholder="example@email.com">
                                @error('guardian_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="enrollment_date" class="form-label">تاريخ الالتحاق</label>
                                <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror" 
                                       id="enrollment_date" name="enrollment_date" 
                                       value="{{ old('enrollment_date', date('Y-m-d')) }}">
                                @error('enrollment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات عامة</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>طالب نشط</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save ml-1"></i> حفظ البيانات
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-redo ml-1"></i> إعادة تعيين
                                </button>
                            </div>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times ml-1"></i> إلغاء
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal للاستيراد -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="importModalLabel" style="color: #ffffff;">
                    <i class="fas fa-file-import ml-2"></i>
                    استيراد الطلاب من Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">
                            <i class="fas fa-file-excel ml-1"></i> اختر ملف Excel
                        </label>
                        <input type="file" class="form-control" id="file" name="file" 
                               accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">
                            الملف يجب أن يكون بصيغة Excel (.xlsx, .xls) أو CSV
                        </small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle ml-1"></i>
                        تأكد من تنسيق الملف حسب النموذج المرفق. 
                        <a href="{{ route('students.download-template') }}" class="alert-link">تحميل النموذج</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times ml-1"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-upload ml-1"></i> استيراد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .section-header {
        margin: 2rem 0 1.5rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #2c3e50;
    }
    
    .section-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
    }
    
    .form-label.required::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // تأكيد قبل إعادة تعيين النموذج
    $('button[type="reset"]').on('click', function(e) {
        if (!confirm('هل أنت متأكد من إعادة تعيين جميع الحقول؟')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection