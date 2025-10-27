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

                    {{-- أزرار عرض المحذوفين أو العودة --}}
                    @if($showTrashed)
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary mb-3">
                            العودة للمعلمين الحاليين
                        </a>
                    @else
                        <a href="{{ route('teachers.index', ['trashed' => 1]) }}" class="btn btn-warning mb-3">
                            عرض المعلمين المحذوفين
                        </a>
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
                                    <!-- ... نموذج البحث كما هو ... -->
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
                                            @if($showTrashed)
                                                {{-- زر الاستعادة --}}
                                                <form action="{{ route('teachers.restore', $teacher->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success" title="استعادة">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-outline-info" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-outline-primary" title="تعديل">
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
                                            @endif
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
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection