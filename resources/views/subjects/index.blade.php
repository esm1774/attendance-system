@extends('layouts.app')

@section('title', 'إدارة المواد الدراسية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">قائمة المواد الدراسية</h3>
                    <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة مادة جديدة
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
                    <form method="GET" action="{{ route('subjects.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="ابحث باسم المادة أو الرمز" 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="type" class="form-control">
                                        <option value="">جميع الأنواع</option>
                                        <option value="mandatory" {{ request('type') == 'mandatory' ? 'selected' : '' }}>إجباري</option>
                                        <option value="elective" {{ request('type') == 'elective' ? 'selected' : '' }}>اختياري</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
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
                                    <th>رمز المادة</th>
                                    <th>اسم المادة</th>
                                    <th>الاسم بالعربية</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $subject)
                                <tr>
                                    <td>{{ $loop->iteration + (($subjects->currentPage() - 1) * $subjects->perPage()) }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $subject->code }}</span>
                                    </td>
                                    <td>{{ $subject->name }}</td>
                                    <td>{{ $subject->name_ar }}</td>
                                    <td>
                                        <span class="badge badge-{{ $subject->type == 'mandatory' ? 'primary' : 'success' }}">
                                            {{ $subject->type_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $subject->is_active ? 'success' : 'danger' }}">
                                            {{ $subject->status_text }}
                                        </span>
                                    </td>
                                    <td>{{ $subject->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('subjects.show', $subject) }}" class="btn btn-info btn-sm" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-primary btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('subjects.toggle-status', $subject) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-{{ $subject->is_active ? 'warning' : 'success' }} btn-sm" 
                                                        title="{{ $subject->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $subject->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="حذف"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
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
                        {{ $subjects->links() }}
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