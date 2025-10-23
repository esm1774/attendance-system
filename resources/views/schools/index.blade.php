@extends('layouts.app')

@section('title', 'إدارة المدارس')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0" style="color: #ffffff;">
                        <i class="fas fa-school ml-2"></i>
                        قائمة المدارس
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('schools.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus ml-1"></i> إضافة مدرسة
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
                    <form method="GET" action="{{ route('schools.index') }}" class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-search ml-1"></i> بحث
                                            </label>
                                            <input type="text" name="search" class="form-control form-control-sm" 
                                                   placeholder="ابحث بالاسم" 
                                                   value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-flag ml-1"></i> الحالة
                                            </label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">جميع الحالات</option>
                                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label small mb-1">
                                                <i class="fas fa-sort ml-1"></i> الترتيب
                                            </label>
                                            <select name="sort" class="form-select form-select-sm">
                                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small mb-1">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-search"></i> بحث
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
                                عرض {{ $schools->firstItem() ?? 0 }} إلى {{ $schools->lastItem() ?? 0 }} 
                                من أصل {{ $schools->total() }} مدرسة
                            </small>
                        </div>
                        <div>
                            <span class="badge bg-primary">إجمالي المدارس: {{ $schools->total() }}</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle" id="schoolsTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;" class="text-center">#</th>
                                    <th>اسم المدرسة (عربي)</th>
                                    <th>اسم المدرسة (إنجليزي)</th>
                                    <th style="width: 150px;">رمز المدرسة</th>
                                    <th style="width: 100px;" class="text-center">الحالة</th>
                                    <th style="width: 120px;" class="text-center">تاريخ الإنشاء</th>
                                    <th style="width: 180px;" class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schools as $school)
                                <tr>
                                    <td class="text-center">
                                        <small>{{ $loop->iteration + (($schools->currentPage() - 1) * $schools->perPage()) }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $school->name_ar }}</strong>
                                    </td>
                                    <td>
                                        {{ $school->name_en ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $school->code ?? 'غير محدد' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $school->is_active ? 'success' : 'danger' }}">
                                            {{ $school->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted">
                                            {{ $school->created_at->format('Y-m-d') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('schools.show', $school) }}" 
                                               class="btn btn-outline-info" 
                                               title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('schools.edit', $school) }}" 
                                               class="btn btn-outline-primary" 
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('schools.toggle-status', $school) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-outline-{{ $school->is_active ? 'warning' : 'success' }}" 
                                                        title="{{ $school->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $school->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('schools.destroy', $school) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger" 
                                                        title="حذف"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذه المدرسة؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">لا توجد مدارس للعرض</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- الترقيم المحسّن -->
                    @if($schools->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <small class="text-muted">
                                الصفحة {{ $schools->currentPage() }} من {{ $schools->lastPage() }}
                            </small>
                        </div>
                        <nav aria-label="التنقل بين الصفحات">
                            {{ $schools->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
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