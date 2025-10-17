@extends('layouts.app')

@section('title', 'إدارة الطلاب')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">قائمة الطلاب</h3>
                    <div>
                        <a href="{{ route('students.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة طالب جديد
                        </a>
                        <a href="{{ route('students.download-template') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> تحميل نموذج
                        </a>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importModal">
                            <i class="fas fa-upload"></i> استيراد طلاب
                        </button>
                    </div>
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
                    <form method="GET" action="{{ route('students.index') }}" class="mb-4">
                        <div class="row">
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
                                        <option value="">جميع الحالات</option>
                                        <option value="active" {{ request('is_active') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ request('is_active') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-btable table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>الرقم الجامعي</th>
                                    <th>الاسم الكامل</th>
                                    <th>الفصل</th>
                                    <th>الجنس</th>
                                    <th>العمر</th>
                                    <th>الحالة</th>
                                    <th>النشاط</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration + (($students->currentPage() - 1) * $students->perPage()) }}</td>
                                    <td>
                                        <div class="student-avatar">
                                            <i class="fas fa-user-graduate fa-2x text-secondary"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $student->student_id }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $student->full_name }}</strong>
                                        @if($student->national_id)
                                            <br><small class="text-muted">هوية: {{ $student->national_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $student->class->grade->name_ar }} - {{ $student->class->name_ar }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $student->gender_text }}</span>
                                    </td>
                                    <td>{{ $student->age }} سنة</td>
                                    <td>
                                        <span class="badge badge-{{ $student->status_color }}">
                                            {{ $student->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $student->is_active ? 'success' : 'danger' }}">
                                            {{ $student->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('students.show', $student) }}" class="btn btn-info btn-sm" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student) }}" class="btn btn-primary btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('students.toggle-status', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-{{ $student->is_active ? 'warning' : 'success' }} btn-sm" 
                                                        title="{{ $student->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $student->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="حذف"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا الطالب؟')">
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
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal الاستيراد -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">استيراد طلاب من ملف Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">اختر ملف Excel</label>
                        <input type="file" class="form-control-file" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">
                            الملف يجب أن يكون بصيغة Excel (.xlsx, .xls) أو CSV
                        </small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        تأكد من تنسيق الملف حسب النموذج المرفق. 
                        <a href="{{ route('students.download-template') }}" class="alert-link">تحميل النموذج</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">استيراد</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .btn-group .btn {
        margin: 0 2px;
    }
</style>
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