@extends('layouts.app')

@section('title', 'إدارة المعلمين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0" style="color: #ffffff;">
                        <i class="fas fa-chalkboard-teacher ml-2"></i>
                        قائمة المعلمين
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('teachers.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus ml-1"></i> إضافة معلم
                        </a>
                        <a href="{{ route('teachers.download-template') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download ml-1"></i> تحميل نموذج
                        </a>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-upload ml-1"></i> استيراد معلمين
                        </button>
                        <a href="{{ route('teachers.export', request()->query()) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-file-excel ml-1"></i> تصدير
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- نموذج البحث والتصفية -->
                    <form method="GET" action="{{ route('teachers.index') }}" class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-search ml-1"></i> بحث
                                            </label>
                                            <input type="text" name="search" class="form-control form-control-sm" 
                                                   placeholder="ابحث بالاسم، الهوية، أو الرقم الوظيفي" 
                                                   value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-school ml-1"></i> المدرسة
                                            </label>
                                            <select name="school_id" class="form-select form-select-sm">
                                                <option value="">جميع المدارس</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}" 
                                                        {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                                        {{ $school->name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-book ml-1"></i> التخصص
                                            </label>
                                            <select name="specialization" class="form-select form-select-sm">
                                                <option value="">جميع التخصصات</option>
                                                @foreach($specializations as $specialization)
                                                    <option value="{{ $specialization }}" 
                                                        {{ request('specialization') == $specialization ? 'selected' : '' }}>
                                                        {{ $specialization }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-venus-mars ml-1"></i> الجنس
                                            </label>
                                            <select name="gender" class="form-select form-select-sm">
                                                <option value="">الكل</option>
                                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-flag ml-1"></i> الحالة
                                            </label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">جميع الحالات</option>
                                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                                <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                                                <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>متقاعد</option>
                                                <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>منقول</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-file-contract ml-1"></i> العقد
                                            </label>
                                            <select name="contract_type" class="form-select form-select-sm">
                                                <option value="">الكل</option>
                                                <option value="permanent" {{ request('contract_type') == 'permanent' ? 'selected' : '' }}>دائم</option>
                                                <option value="temporary" {{ request('contract_type') == 'temporary' ? 'selected' : '' }}>مؤقت</option>
                                                <option value="substitute" {{ request('contract_type') == 'substitute' ? 'selected' : '' }}>بديل</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label small mb-1">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- عداد النتائج -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <small class="text-muted">
                                عرض {{ $teachers->firstItem() ?? 0 }} إلى {{ $teachers->lastItem() ?? 0 }} 
                                من أصل {{ $teachers->total() }} معلم
                            </small>
                        </div>
                        <div>
                            <span class="badge bg-primary">إجمالي المعلمين: {{ $teachers->total() }}</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle" id="teachersTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;" class="text-center">#</th>
                                    <th style="width: 60px;" class="text-center">الصورة</th>
                                    <th style="width: 120px;">الرقم الوظيفي</th>
                                    <th>الاسم الكامل</th>
                                    <th style="width: 120px;">التخصص</th>
                                    <th style="width: 150px;">المدرسة</th>
                                    <th style="width: 80px;" class="text-center">الجنس</th>
                                    <th style="width: 100px;" class="text-center">نوع العقد</th>
                                    <th style="width: 100px;" class="text-center">الحالة</th>
                                    <th style="width: 100px;" class="text-center">النشاط</th>
                                    <th style="width: 200px;" class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers as $teacher)
                                <tr>
                                    <td class="text-center">
                                        <small>{{ $loop->iteration + (($teachers->currentPage() - 1) * $teachers->perPage()) }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($teacher->photo)
                                            <img src="{{ $teacher->photo_url }}" 
                                                 alt="{{ $teacher->name }}" 
                                                 class="rounded-circle" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $teacher->employee_number }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $teacher->name }}</strong>
                                        <br><small class="text-muted">
                                            <i class="fas fa-id-card ml-1"></i>{{ $teacher->national_id }}
                                        </small>
                                        <br><small class="text-muted">
                                            <i class="fas fa-envelope ml-1"></i>{{ $teacher->email }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ $teacher->specialization }}</span>
                                        @if($teacher->subjects->count() > 0)
                                            <br><small class="text-muted">
                                                {{ $teacher->subjects->count() }} مواد
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $teacher->school->name_ar }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-{{ $teacher->gender == 'male' ? 'mars' : 'venus' }} ml-1"></i>
                                            {{ $teacher->gender_text }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $teacher->contract_type_text }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $teacher->status_color }}">
                                            {{ $teacher->status_text }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $teacher->is_active ? 'success' : 'danger' }}">
                                            {{ $teacher->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('teachers.show', $teacher) }}" 
                                               class="btn btn-outline-info" 
                                               title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teachers.edit', $teacher) }}" 
                                               class="btn btn-outline-primary" 
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('teachers.toggle-status', $teacher) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-outline-{{ $teacher->is_active ? 'warning' : 'success' }}" 
                                                        title="{{ $teacher->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $teacher->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger" 
                                                        title="حذف"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا المعلم؟ سيتم حذف جميع البيانات المرتبطة به.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">لا توجد بيانات للعرض</p>
                                        <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus ml-1"></i> إضافة معلم جديد
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- الترقيم المحسّن -->
                    @if($teachers->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <small class="text-muted">
                                الصفحة {{ $teachers->currentPage() }} من {{ $teachers->lastPage() }}
                            </small>
                        </div>
                        <nav aria-label="التنقل بين الصفحات">
                            {{ $teachers->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal الاستيراد -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importModalLabel" style="color: #ffffff;">
                    <i class="fas fa-file-import ml-2"></i>
                    استيراد معلمين من ملف Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('teachers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">
                            <i class="fas fa-file-excel ml-1"></i> اختر ملف Excel
                        </label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">
                            الملف يجب أن يكون بصيغة Excel (.xlsx, .xls) أو CSV
                        </small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle ml-1"></i>
                        تأكد من تنسيق الملف حسب النموذج المرفق. 
                        <a href="{{ route('teachers.download-template') }}" class="alert-link">تحميل النموذج</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times ml-1"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload ml-1"></i> استيراد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // تفعيل tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // إخفاء التنبيهات تلقائياً
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection

@section('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .form-select-sm, .form-control-sm {
        font-size: 0.875rem;
    }
</style>
@endsection