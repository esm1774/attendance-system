@extends('layouts.app')

@section('title', 'إدارة الطلاب')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0" style="color: #ffffff;">
                        <i class="fas fa-user-graduate ml-2"></i>
                        قائمة الطلاب
                    </h3>
                    <div>
                        
                        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus ml-2"></i> إضافة طالب
                        </a>
                        <a href="{{ route('students.download-template') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download ml-2"></i> تحميل نموذج
                        </a>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-upload ml-2"></i> استيراد طلاب
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

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- نموذج البحث والتصفية -->
                    <form method="GET" action="{{ route('students.index') }}" class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            
                                            <input type="text" name="search" class="form-control" 
                                                   placeholder="ابحث بالاسم أو الرقم" 
                                                   value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                           
                                            <select name="class_id" class="form-control">
                                                <option value="">جميع الفصول</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" 
                                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                        {{ $class->grade->name_ar }} - {{ $class->name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            
                                            <select name="gender" class="form-control">
                                                <option value="">جميع الجنسين</option>
                                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            
                                            <select name="status" class="form-control">
                                                <option value="">جميع الحالات</option>
                                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                                <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>منقول</option>
                                                <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>متخرج</option>
                                                <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>منسحب</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            
                                            <select name="is_active" class="form-control">
                                                <option value="">الكل</option>
                                                <option value="active" {{ request('is_active') == 'active' ? 'selected' : '' }}>نشط</option>
                                                <option value="inactive" {{ request('is_active') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-info align-middle w- h-100">
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
                                عرض {{ $students->firstItem() ?? 0 }} إلى {{ $students->lastItem() ?? 0 }} 
                                من أصل {{ $students->total() }} طالب
                            </small>
                        </div>
                        <div>
                            <span class="badge bg-primary">إجمالي الطلاب: {{ $students->total() }}</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle text-center" id="studentsTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;" class="text-center">#</th>
                                    <th style="width: 60px;" class="text-center">الصورة</th>
                                    <th>الهوية الوطنية</th>
                                    <th>الاسم الكامل</th>
                                    <th style="width: 150px;">الفصل</th>
                                    <th style="width: 100px;" class="text-center">الحالة</th>
                                    <th style="width: 180px;" class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td class="text-center">
                                        <small>{{ $loop->iteration + (($students->currentPage() - 1) * $students->perPage()) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="student-avatar">
                                            <i class="fas fa-user-graduate fa-lg text-muted"></i>
                                        </div>
                                    </td>
                                    <td>
                                       
                                        @if($student->national_id)
                                                {{ $student->national_id }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $student->full_name }}</strong>
                                      
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-white">
                                            {{ $student->class->grade->name_ar }} - {{ $student->class->name_ar }}
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="badge bg-{{ $student->status_color }}">
                                            {{ $student->status_text }}
                                        </span>
                                    </td>
                                 
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('students.show', $student) }}" 
                                               class="btn btn-outline-info" 
                                               title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student) }}" 
                                               class="btn btn-outline-primary" 
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('students.toggle-status', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-outline-{{ $student->is_active ? 'warning' : 'success' }}" 
                                                        title="{{ $student->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $student->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger" 
                                                        title="حذف"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا الطالب؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">لا توجد بيانات للعرض</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- الترقيم المحسّن -->
                    @if($students->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <small class="text-muted">
                                الصفحة {{ $students->currentPage() }} من {{ $students->lastPage() }}
                            </small>
                        </div>
                        <nav aria-label="التنقل بين الصفحات">
                            {{ $students->links('pagination::bootstrap-5') }}
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
                    استيراد طلاب من ملف Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
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
                        <a href="{{ route('students.download-template') }}" class="alert-link">تحميل النموذج</a>
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