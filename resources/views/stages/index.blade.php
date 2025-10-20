@extends('layouts.app')

@section('title', 'إدارة المراحل الدراسية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">قائمة المراحل الدراسية</h3>
                    <a href="{{ route('stages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة مرحلة جديدة
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fas fa-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fas fa-ban"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- نموذج البحث والتصفية -->
                    <form method="GET" action="{{ route('stages.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="ابحث باسم المرحلة أو الرمز" 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="school_id" class="form-control">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-btable table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الترتيب</th>
                                    <th>اسم المرحلة</th>
                                    <th>الاسم بالعربية</th>
                                    <th>المدرسة</th>
                                    <th>نطاق العمر</th>
                                    <th>الصفوف</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stages as $stage)
                                <tr>
                                    <td>{{ $loop->iteration + (($stages->currentPage() - 1) * $stages->perPage()) }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $stage->order }}</span>
                                    </td>
                                    <td>{{ $stage->name }}</td>
                                    <td>{{ $stage->name_ar }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $stage->school->name_ar }}</span>
                                    </td>
                                    <td>{{ $stage->age_range }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $stage->grades_count }} صف</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $stage->is_active ? 'success' : 'danger' }}">
                                            {{ $stage->status_text }}
                                        </span>
                                    </td>
                                    <td>{{ $stage->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('stages.show', $stage) }}" class="btn btn-outline-info btn-sm" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('stages.edit', $stage) }}" class="btn btn-outline-primary btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('stages.toggle-status', $stage) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-{{ $stage->is_active ? 'warning' : 'success' }} btn-sm" 
                                                        title="{{ $stage->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $stage->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('stages.destroy', $stage) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        title="حذف"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذه المرحلة؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                        <!-- الترقيم المحسّن -->
                    @if($stages->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <small class="text-muted">
                                الصفحة {{ $stages->currentPage() }} من {{ $stages->lastPage() }}
                            </small>
                        </div>
                        <nav aria-label="التنقل بين الصفحات">
                            {{ $stages->links('pagination::bootstrap-5') }}
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
        $('.table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
            },
            "responsive": true,
            "autoWidth": false,
            "paging": false,
            "searching": false,
            "info": false
        });
    });
</script>
@endsection