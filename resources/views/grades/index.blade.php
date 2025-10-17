@extends('layouts.app')

@section('title', 'إدارة الصفوف الدراسية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">قائمة الصفوف الدراسية</h3>
                    <a href="{{ route('grades.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة صف جديد
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
                    <form method="GET" action="{{ route('grades.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="ابحث باسم الصف" 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="stage_id" class="form-control">
                                        <option value="">جميع المراحل</option>
                                        @foreach($stages as $stage)
                                            <option value="{{ $stage->id }}" 
                                                {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                                                {{ $stage->name_ar }} - {{ $stage->school->name_ar }}
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
                                    <th>اسم الصف</th>
                                    <th>الاسم بالعربية</th>
                                    <th>المرحلة</th>
                                    <th>المدرسة</th>
                                    <th>ترتيب الصف</th>
                                    <th>عدد الفصول</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades as $grade)
                                <tr>
                                    <td>{{ $loop->iteration + (($grades->currentPage() - 1) * $grades->perPage()) }}</td>
                                    <td>{{ $grade->name }}</td>
                                    <td>{{ $grade->name_ar }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $grade->stage->name_ar }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $grade->stage->school->name_ar }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $grade->order }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $grade->classes_count }} فصل</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $grade->is_active ? 'success' : 'danger' }}">
                                            {{ $grade->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>{{ $grade->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('grades.show', $grade) }}" class="btn btn-info btn-sm" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('grades.edit', $grade) }}" class="btn btn-primary btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('grades.toggle-status', $grade) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-{{ $grade->is_active ? 'warning' : 'success' }} btn-sm" 
                                                        title="{{ $grade->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $grade->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="حذف"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا الصف؟')">
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
                        {{ $grades->links() }}
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