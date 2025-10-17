@extends('layouts.app')

@section('title', 'إدارة المعلمين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">قائمة المعلمين</h3>
                    <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة معلم جديد
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
                    <form method="GET" action="{{ route('teachers.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="ابحث بالاسم أو البريد أو الرقم الوظيفي" 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="specialization" class="form-control">
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
                                    <select name="employment_type" class="form-control">
                                        <option value="">جميع أنواع التعيين</option>
                                        <option value="full_time" {{ request('employment_type') == 'full_time' ? 'selected' : '' }}>دوام كامل</option>
                                        <option value="part_time" {{ request('employment_type') == 'part_time' ? 'selected' : '' }}>دوام جزئي</option>
                                        <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>عقد</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>معطل</option>
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
                                    <th>الرقم الوظيفي</th>
                                    <th>اسم المعلم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>التخصص</th>
                                    <th>المؤهل العلمي</th>
                                    <th>نوع التعيين</th>
                                    <th>المواد</th>
                                    <th>الفصول</th>
                                    <th>الحالة</th>
                                    <th>تاريخ التعيين</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{ $loop->iteration + (($teachers->currentPage() - 1) * $teachers->perPage()) }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $teacher->teacher_id }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $teacher->name }}</strong>
                                        @if($teacher->phone)
                                            <br><small class="text-muted">{{ $teacher->phone }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $teacher->specialization }}</span>
                                    </td>
                                    <td>{{ $teacher->qualification ?? 'غير محدد' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $teacher->employment_type == 'full_time' ? 'success' : 'warning' }}">
                                            {{ $teacher->employment_type_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">0 مادة</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">0 فصل</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $teacher->is_active ? 'success' : 'danger' }}">
                                            {{ $teacher->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </td>
                                    <td>{{ $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : 'غير محدد' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-info btn-sm" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-primary btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('teachers.toggle-status', $teacher) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-{{ $teacher->is_active ? 'warning' : 'success' }} btn-sm" 
                                                        title="{{ $teacher->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $teacher->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="حذف"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا المعلم؟')">
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
                        {{ $teachers->links() }}
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