@extends('layouts.app')

@section('title', 'تفاصيل المعلم')

@section('content')
<div class="container-fluid">
    <!-- معلومات المعلم الأساسية -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0" style="color: #ffffff;">
                        <i class="fas fa-user-circle ml-2"></i>
                        بطاقة المعلم
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('teachers.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
                        </a>
                        <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit ml-1"></i> تعديل
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash ml-1"></i> حذف
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- الصورة والمعلومات الأساسية -->
                        <div class="col-md-3 text-center border-end">
                            <img src="{{ $teacher->photo_url }}" 
                                 alt="{{ $teacher->name }}" 
                                 class="rounded-circle mb-3"
                                 style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #ddd;">
                            <h4 class="mb-2">{{ $teacher->name }}</h4>
                            <p class="text-muted mb-1">
                                <i class="fas fa-briefcase ml-1"></i>
                                {{ $teacher->specialization }}
                            </p>
                            <p class="mb-3">
                                <span class="badge bg-{{ $teacher->status_color }} fs-6">
                                    {{ $teacher->status_text }}
                                </span>
                            </p>
                            
                            <!-- حالة النشاط -->
                            <div class="mb-3">
                                @if($teacher->is_active)
                                    <span class="badge bg-success p-2">
                                        <i class="fas fa-check-circle"></i> حساب نشط
                                    </span>
                                @else
                                    <span class="badge bg-danger p-2">
                                        <i class="fas fa-times-circle"></i> حساب معطل
                                    </span>
                                @endif
                            </div>

                            <!-- معلومات سريعة -->
                            <div class="d-grid gap-2">
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted d-block">العمر</small>
                                    <strong>{{ $teacher->age }} سنة</strong>
                                </div>
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted d-block">سنوات الخدمة</small>
                                    <strong>{{ $teacher->years_of_service }} سنة</strong>
                                </div>
                            </div>
                        </div>

                        <!-- المعلومات التفصيلية -->
                        <div class="col-md-9">
                            <div class="row">
                                <!-- المعلومات الشخصية -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-user text-primary ml-2"></i>
                                        المعلومات الشخصية
                                    </h5>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" width="40%"><i class="fas fa-id-card ml-1"></i> رقم الهوية:</td>
                                            <td><strong>{{ $teacher->national_id }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-calendar ml-1"></i> تاريخ الميلاد:</td>
                                            <td><strong>{{ $teacher->birth_date->format('Y-m-d') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-venus-mars ml-1"></i> الجنس:</td>
                                            <td><strong>{{ $teacher->gender_text }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-flag ml-1"></i> الجنسية:</td>
                                            <td><strong>{{ $teacher->nationality }}</strong></td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- معلومات التواصل -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-address-book text-info ml-2"></i>
                                        معلومات التواصل
                                    </h5>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" width="40%"><i class="fas fa-phone ml-1"></i> الجوال:</td>
                                            <td><strong><a href="tel:{{ $teacher->phone }}" class="text-decoration-none">{{ $teacher->phone }}</a></strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-envelope ml-1"></i> البريد:</td>
                                            <td><strong><a href="mailto:{{ $teacher->email }}" class="text-decoration-none">{{ $teacher->email }}</a></strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-map-marker-alt ml-1"></i> العنوان:</td>
                                            <td><strong>{{ $teacher->address ?? 'غير محدد' }}</strong></td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- المعلومات الوظيفية -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-briefcase text-success ml-2"></i>
                                        المعلومات الوظيفية
                                    </h5>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" width="40%"><i class="fas fa-id-badge ml-1"></i> الرقم الوظيفي:</td>
                                            <td><strong>{{ $teacher->employee_number }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-school ml-1"></i> المدرسة:</td>
                                            <td><strong>{{ $teacher->school->name_ar }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-graduation-cap ml-1"></i> المؤهل:</td>
                                            <td><strong>{{ $teacher->qualification }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-calendar-check ml-1"></i> تاريخ التعيين:</td>
                                            <td><strong>{{ $teacher->hire_date->format('Y-m-d') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-file-contract ml-1"></i> نوع العقد:</td>
                                            <td><strong>{{ $teacher->contract_type_text }}</strong></td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- معلومات إضافية -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-info-circle text-warning ml-2"></i>
                                        معلومات إضافية
                                    </h5>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" width="40%"><i class="fas fa-sitemap ml-1"></i> القسم:</td>
                                            <td><strong>{{ $teacher->department ?? 'غير محدد' }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-money-bill-wave ml-1"></i> الراتب:</td>
                                            <td><strong>{{ $teacher->salary ? number_format($teacher->salary, 2) . ' ريال' : 'غير محدد' }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-calendar-plus ml-1"></i> تاريخ التسجيل:</td>
                                            <td><strong>{{ $teacher->created_at->format('Y-m-d') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-calendar-edit ml-1"></i> آخر تحديث:</td>
                                            <td><strong>{{ $teacher->updated_at->format('Y-m-d') }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- الملاحظات -->
                            @if($teacher->notes)
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="fas fa-sticky-note ml-1"></i> الملاحظات:</h6>
                                <p class="mb-0">{{ $teacher->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- المواد والفصول -->
    <div class="row mb-4">
        <!-- المواد التي يدرسها -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0" style="color: #ffffff;">
                        <i class="fas fa-book ml-2"></i>
                        المواد الدراسية ({{ $teacher->subjects->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($teacher->subjects->count() > 0)
                        <div class="row">
                            @foreach($teacher->subjects as $subject)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <i class="fas fa-check-circle text-success ml-2"></i>
                                        <span>{{ $subject->name_ar }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-2"></i>
                            <p>لم يتم تحديد أي مواد دراسية</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- الفصول الدراسية -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0" style="color: #ffffff;">
                        <i class="fas fa-door-open ml-2"></i>
                        الفصول الدراسية ({{ $teacher->schoolClasses->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($teacher->schoolClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>الصف</th>
                                        <th>الفصل</th>
                                        <th>المادة</th>
                                        <th class="text-center">رائد الفصل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->schoolClasses as $class)
                                        <tr>
                                            <td>{{ $class->grade->name_ar }}</td>
                                            <td>{{ $class->name_ar }}</td>
                                            <td>
                                                @if($class->pivot->subject_id)
                                                    <span class="badge bg-primary">
                                                        {{ \App\Models\Subject::find($class->pivot->subject_id)->name_ar ?? 'غير محدد' }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($class->pivot->is_class_teacher)
                                                    <i class="fas fa-star text-warning" title="رائد الفصل"></i>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-2"></i>
                            <p>لم يتم تحديد أي فصول دراسية</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- الفصول التي يكون فيها رائد فصل -->
    @if($teacher->classTeacherOf->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">
                        <i class="fas fa-star ml-2"></i>
                        رائد فصل لـ ({{ $teacher->classTeacherOf->count() }}) فصل
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($teacher->classTeacherOf as $class)
                            <div class="col-md-4 mb-2">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-door-open ml-1"></i>
                                    <strong>{{ $class->grade->name_ar }} - {{ $class->name_ar }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- إحصائيات سريعة -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fas fa-book fa-2x text-primary mb-2"></i>
                    <h3 class="mb-0">{{ $teacher->subjects->count() }}</h3>
                    <small class="text-muted">مادة دراسية</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fas fa-door-open fa-2x text-info mb-2"></i>
                    <h3 class="mb-0">{{ $teacher->schoolClasses->count() }}</h3>
                    <small class="text-muted">فصل دراسي</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fas fa-star fa-2x text-warning mb-2"></i>
                    <h3 class="mb-0">{{ $teacher->classTeacherOf->count() }}</h3>
                    <small class="text-muted">رائد فصل</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                    <h3 class="mb-0">{{ $teacher->years_of_service }}</h3>
                    <small class="text-muted">سنة خدمة</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel" style="color: #ffffff;">
                    <i class="fas fa-exclamation-triangle ml-2"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">هل أنت متأكد من حذف المعلم <strong>{{ $teacher->name }}</strong>؟</p>
                <p class="text-danger mb-0 mt-2">
                    <i class="fas fa-exclamation-circle"></i>
                    سيتم حذف جميع البيانات المرتبطة بهذا المعلم ولا يمكن التراجع عن هذا الإجراء.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times ml-1"></i> إلغاء
                </button>
                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash ml-1"></i> حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-borderless td {
        padding: 0.5rem 0.25rem;
    }
    
    .card {
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .border-end {
        border-left: 1px solid #dee2e6 !important;
    }
    
    @media print {
        .btn-group,
        .modal {
            display: none !important;
        }
    }
</style>
@endsection