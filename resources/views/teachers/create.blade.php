@extends('layouts.app')

@section('title', 'إضافة معلم جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0" style="color: #ffffff;">
                        <i class="fas fa-user-plus ml-2"></i>
                        إضافة معلم جديد
                    </h3>
                    <a href="{{ route('teachers.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>يوجد أخطاء في النموذج:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data" id="teacherForm">
                        @csrf

                        <!-- المعلومات الشخصية -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0" style="color: #ffffff;">
                                    <i class="fas fa-user ml-2"></i>
                                    المعلومات الشخصية
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3 text-center">
                                        <div class="photo-upload-container">
                                            <img id="photoPreview" 
                                                 src="{{ asset('images/default-avatar.png') }}" 
                                                 alt="صورة المعلم"
                                                 class="rounded-circle mb-2"
                                                 style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ddd;">
                                            <div>
                                                <label for="photo" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-camera ml-1"></i> اختر صورة
                                                </label>
                                                <input type="file" 
                                                       class="d-none" 
                                                       id="photo" 
                                                       name="photo" 
                                                       accept="image/*"
                                                       onchange="previewPhoto(this)">
                                                <small class="d-block text-muted mt-1">
                                                    اختياري - الحد الأقصى 2 ميجابايت (JPG, PNG)
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">الاسم الكامل</label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">رقم الهوية الوطنية</label>
                                        <input type="text" 
                                               class="form-control @error('national_id') is-invalid @enderror" 
                                               name="national_id" 
                                               value="{{ old('national_id') }}" 
                                               required>
                                        @error('national_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">تاريخ الميلاد</label>
                                        <input type="date" 
                                               class="form-control @error('birth_date') is-invalid @enderror" 
                                               name="birth_date" 
                                               value="{{ old('birth_date') }}" 
                                               required>
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">الجنس</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" 
                                                name="gender" 
                                                required>
                                            <option value="">-- اختر الجنس --</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">الجنسية</label>
                                        <input type="text" 
                                               class="form-control @error('nationality') is-invalid @enderror" 
                                               name="nationality" 
                                               value="{{ old('nationality', 'سعودي') }}" 
                                               required>
                                        @error('nationality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات التواصل -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0" style="color: #ffffff;">
                                    <i class="fas fa-address-book ml-2"></i>
                                    معلومات التواصل
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">رقم الجوال</label>
                                        <input type="text" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="05xxxxxxxx"
                                               required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">البريد الإلكتروني</label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">العنوان</label>
                                        <input type="text" 
                                               class="form-control @error('address') is-invalid @enderror" 
                                               name="address" 
                                               value="{{ old('address') }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المعلومات الوظيفية -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0" style="color: #ffffff;">
                                    <i class="fas fa-briefcase ml-2"></i>
                                    المعلومات الوظيفية
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">الرقم الوظيفي</label>
                                        <input type="text" 
                                               class="form-control @error('employee_number') is-invalid @enderror" 
                                               name="employee_number" 
                                               value="{{ old('employee_number') }}" 
                                               required>
                                        @error('employee_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">المدرسة</label>
                                        <select class="form-select @error('school_id') is-invalid @enderror" 
                                                name="school_id" 
                                                required>
                                            <option value="">-- اختر المدرسة --</option>
                                            @foreach($schools as $school)
                                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                    {{ $school->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('school_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">التخصص</label>
                                        <input type="text" 
                                               class="form-control @error('specialization') is-invalid @enderror" 
                                               name="specialization" 
                                               value="{{ old('specialization') }}" 
                                               placeholder="مثال: رياضيات، لغة عربية"
                                               required>
                                        @error('specialization')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">المؤهل العلمي</label>
                                        <select class="form-select @error('qualification') is-invalid @enderror" 
                                                name="qualification" 
                                                required>
                                            <option value="">-- اختر المؤهل --</option>
                                            <option value="دبلوم" {{ old('qualification') == 'دبلوم' ? 'selected' : '' }}>دبلوم</option>
                                            <option value="بكالوريوس" {{ old('qualification') == 'بكالوريوس' ? 'selected' : '' }}>بكالوريوس</option>
                                            <option value="ماجستير" {{ old('qualification') == 'ماجستير' ? 'selected' : '' }}>ماجستير</option>
                                            <option value="دكتوراه" {{ old('qualification') == 'دكتوراه' ? 'selected' : '' }}>دكتوراه</option>
                                        </select>
                                        @error('qualification')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">تاريخ التعيين</label>
                                        <input type="date" 
                                               class="form-control @error('hire_date') is-invalid @enderror" 
                                               name="hire_date" 
                                               value="{{ old('hire_date') }}" 
                                               required>
                                        @error('hire_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">نوع العقد</label>
                                        <select class="form-select @error('contract_type') is-invalid @enderror" 
                                                name="contract_type" 
                                                required>
                                            <option value="">-- اختر نوع العقد --</option>
                                            <option value="permanent" {{ old('contract_type') == 'permanent' ? 'selected' : '' }}>دائم</option>
                                            <option value="temporary" {{ old('contract_type') == 'temporary' ? 'selected' : '' }}>مؤقت</option>
                                            <option value="substitute" {{ old('contract_type') == 'substitute' ? 'selected' : '' }}>بديل</option>
                                        </select>
                                        @error('contract_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">القسم/الشعبة</label>
                                        <input type="text" 
                                               class="form-control @error('department') is-invalid @enderror" 
                                               name="department" 
                                               value="{{ old('department') }}">
                                        @error('department')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الراتب الأساسي</label>
                                        <input type="number" 
                                               class="form-control @error('salary') is-invalid @enderror" 
                                               name="salary" 
                                               value="{{ old('salary') }}" 
                                               step="0.01"
                                               placeholder="0.00">
                                        @error('salary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المواد والفصول -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="fas fa-book-open ml-2"></i>
                                    المواد والفصول الدراسية
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- المواد -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">المواد التي يدرسها:</label>
                                    <div class="row">
                                        @foreach($subjects as $subject)
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           name="subjects[]" 
                                                           value="{{ $subject->id }}" 
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

                                <!-- الفصول -->
                                <div>
                                    <label class="form-label fw-bold">الفصول الدراسية:</label>
                                    <small class="text-muted d-block mb-2">اختر الفصول التي يدرس فيها المعلم مع تحديد المادة لكل فصل</small>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50" class="text-center">اختيار</th>
                                                    <th>الصف</th>
                                                    <th>الفصل</th>
                                                    <th>المادة</th>
                                                    <th width="120" class="text-center">رائد الفصل</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($schoolClasses as $class)
                                                    <tr>
                                                        <td class="text-center">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   name="classes[]" 
                                                                   value="{{ $class->id }}"
                                                                   {{ in_array($class->id, old('classes', [])) ? 'checked' : '' }}>
                                                        </td>
                                                        <td>{{ $class->grade->name_ar }}</td>
                                                        <td>{{ $class->name_ar }}</td>
                                                        <td>
                                                            <select class="form-select form-select-sm" 
                                                                    name="class_subject_{{ $class->id }}">
                                                                <option value="">-- اختر المادة --</option>
                                                                @foreach($subjects as $subject)
                                                                    <option value="{{ $subject->id }}">
                                                                        {{ $subject->name_ar }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   name="is_class_teacher_{{ $class->id }}" 
                                                                   value="1">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الحالة والملاحظات -->
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0" style="color: #ffffff;">
                                    <i class="fas fa-cog ml-2"></i>
                                    الحالة والملاحظات
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">الحالة</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                name="status" 
                                                required>
                                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                                            <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>متقاعد</option>
                                            <option value="transferred" {{ old('status') == 'transferred' ? 'selected' : '' }}>منقول</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">النشاط</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="is_active" 
                                                   value="1" 
                                                   id="is_active"
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                المعلم نشط
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">الملاحظات</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                  name="notes" 
                                                  rows="3"
                                                  placeholder="أي ملاحظات إضافية عن المعلم...">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times ml-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save ml-1"></i> حفظ المعلم
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // معاينة الصورة قبل الرفع
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        // إخفاء التنبيهات تلقائياً
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection

@section('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .photo-upload-container {
        padding: 20px;
    }
</style>
@endsection