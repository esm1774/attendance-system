@extends('layouts.app')

@section('title', 'إدارة الفصول الدراسية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">قائمة الفصول الدراسية</h3>
                    <a href="{{ route('classes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة فصل جديد
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
                    <form method="GET" action="{{ route('classes.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="ابحث باسم الفصل أو الرمز" 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="grade_id" class="form-control">
                                        <option value="">جميع الصفوف</option>
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}" 
                                                {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                                {{ $grade->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="teacher_id" class="form-control">
                                        <option value="">جميع المرشدين</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
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
                                    <th>رمز الفصل</th>
                                    <th>اسم الفصل</th>
                                    <th>الاسم بالعربية</th>
                                    <th>الصف</th>
                                    <th>المرشد</th>
                                    <th>الطلاب</th>
                                    <th>السعة</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
     @foreach($classes as $class)

    <tr>
    <td>{{ $loop->iteration + (($classes->currentPage() - 1) * $classes->perPage()) }}</td>
    <td><span class="badge badge-secondary">{{ $class->code }}</span></td>
        <td>{{ $class->name }}</td>
        <td>{{ $class->name_ar }}</td>
        <td><span class="badge badge-info">{{ $class->grade->name_ar ?? 'غير محدد' }}</span></td>
        <td>
            @if($class->teacher)
                <span class="badge badge-primary">{{ $class->teacher->name }}</span>
            @else
                <span class="badge badge-secondary">غير محدد</span>
            @endif
        </td>
        <td>
            <span class="badge badge-{{ $class->students_count > 0 ? 'success' : 'secondary' }}">
                {{ $class->students_count }} طالب
            </span>
        </td>
        <td>
            <div class="progress" style="height: 20px;">
                <div class="progress-bar {{ $class->occupancy_rate > 80 ? 'bg-danger' : ($class->occupancy_rate > 50 ? 'bg-warning' : 'bg-success') }}" 
                     role="progressbar" 
                     style="width: {{ $class->occupancy_rate }}%"
                     aria-valuenow="{{ $class->occupancy_rate }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                    {{ $class->current_students_count }}/{{ $class->capacity }}
                </div>
            </div>
            <small class="text-muted">{{ $class->occupancy_rate_text }}</small>
        </td>
        <td>
            <span class="badge badge-{{ $class->is_active ? 'success' : 'danger' }}">
                {{ $class->status_text }}
            </span>
        </td>
        <td>{{ $class->created_at->format('Y-m-d') }}</td>
        <td>
            <div class="btn-group">
                <a href="{{ route('classes.show', $class) }}" class="btn btn-info btn-sm" title="عرض">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('classes.edit', $class) }}" class="btn btn-primary btn-sm" title="تعديل">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('classes.toggle-status', $class) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-{{ $class->is_active ? 'warning' : 'success' }} btn-sm" 
                            title="{{ $class->is_active ? 'تعطيل' : 'تفعيل' }}">
                        <i class="fas fa-{{ $class->is_active ? 'pause' : 'play' }}"></i>
                    </button>
                </form>
                <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" 
                            title="حذف"
                            onclick="return confirm('هل أنت متأكد من حذف هذا الفصل؟')">
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

                    <!-- الترقيم -->
                    <div class="d-flex justify-content-center mt-4">
                    {{ $classes->links() }}
                    </div>
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