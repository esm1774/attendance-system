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
                            <i class="fas fa-plus ml-1"></i> إضافة معلم جديد
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
                                                   placeholder="ابحث بالاسم أو البريد أو الرقم" 
                                                   value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-graduation-cap ml-1"></i> التخصص
                                            </label>
                                            <select name="specialization" class="form-select form-select-sm">
                                                <option value="">جميع التخصصات</option>
                                                @foreach($specializations as $spec)
                                                    <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>
                                                        {{ $spec }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-briefcase ml-1"></i> نوع التعيين
                                            </label>
                                            <select name="employment_type" class="form-select form-select-sm">
                                                <option value="">جميع الأنواع</option>
                                                <option value="full_time" {{ request('employment_type') == 'full_time' ? 'selected' : '' }}>دوام كامل</option>
                                                <option value="part_time" {{ request('employment_type') == 'part_time' ? 'selected' : '' }}>دوام جزئي</option>
                                                <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>عقد</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-toggle-on ml-1"></i> الحالة
                                            </label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">جميع الحالات</option>
                                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>معطل</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small mb-1">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-search ml-1"></i> بحث
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
                                    <th style="width: 120px;">الرقم الوظيفي</th>
                                    <th>اسم المعلم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th style="width: 120px;">التخصص</th>
                                    <th style="width: 120px;">المؤهل العلمي</th>
                                    <th style="width: 100px;" class="text-center">نوع التعيين</th>
                                    <th style="width: 80px;" class="text-center">المواد</th>
                                    <th style="width: 80px;" class="text-center">الفصول</th>
                                    <th style="width: 100px;" class="text-center">الحالة</th>
                                    <th style="width: 120px;">تاريخ التعيين</th>
                                    <th style="width: 180px;" class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers as $teacher)
                                <tr>
                                    <td class="text-center">
                                        <small>{{ $loop->iteration + (($teachers->currentPage() - 1) * $teachers->perPage()) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ $teacher->teacher_id }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $teacher->name }}</strong>
                                        @if($teacher->phone)
                                            <br><small class="text-muted">
                                                <i class="fas fa-phone ml-1"></i>{{ $teacher->phone }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $teacher->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $teacher->specialization }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $teacher->qualification ?? 'غير محدد' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $teacher->employment_type == 'full_time' ? 'success' : ($teacher->employment_type == 'part_time' ? 'warning' : 'info') }}">
                                            {{ $teacher->employment_type_text }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">0</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-white">0</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $teacher->is_active ? 'success' : 'danger' }}">
                                            {{ $teacher->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : 'غير محدد' }}</small>
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
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا المعلم؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">لا توجد بيانات للعرض</p>
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
@endsection

@section('styles')
<style>
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* تصغير أسهم التنقل */
    .pagination {
        margin-bottom: 0;
    }
    
    .pagination .page-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        margin: 0 2px;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        border-color: #2c3e50;
    }
    
    .pagination .page-link:hover {
        background-color: #f8f9fa;
        color: #2c3e50;
    }
    
    /* تحسين مظهر الجدول */
    .table thead th {
        font-weight: 600;
        font-size: 0.875rem;
        white-space: nowrap;
    }
    
    .table tbody td {
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* تحسين نموذج البحث */
    .form-label.small {
        font-weight: 600;
        color: #495057;
        font-size: 0.8rem;
    }
    
    .form-control-sm, .form-select-sm {
        font-size: 0.875rem;
    }
    
    /* تصميم responsive للأجهزة الصغيرة */
    @media (max-width: 768px) {
        .btn-group-sm {
            display: flex;
            flex-direction: column;
        }
        
        .btn-group-sm .btn {
            margin: 2px 0;
            width: 100%;
        }
        
        .table {
            font-size: 0.75rem;
        }
        
        .pagination .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
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