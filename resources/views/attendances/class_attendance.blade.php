@extends('layouts.app')

@section('title', 'تسجيل الحضور والغيابة للفصل')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تسجيل الحضور والغيابة للفصل</h3>
                    <div class="card-tools">
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>الفصل:</strong> {{ $class->full_path }}</div>
                        <div class="col-md-3"><strong>المادة:</strong> {{ $subject->name }}</div>
                        <div class="col-md-3"><strong>التاريخ:</strong> {{ $date }}</div>
                        <div class="col-md-3"><strong>عدد الطلاب:</strong> {{ $students->count() }}</div>
                    </div>

                    <!-- أزرار التحديد السريع -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success" id="markAllPresent">
                                    <i class="fas fa-user-check"></i> تحديد الكل حاضرين
                                </button>
                                <button type="button" class="btn btn-warning" id="markAllLate">
                                    <i class="fas fa-clock"></i> تحديد الكل متأخرين
                                </button>
                                <button type="button" class="btn btn-danger" id="markAllAbsent">
                                    <i class="fas fa-user-times"></i> تحديد الكل غائبين
                                </button>
                                <button type="button" class="btn btn-info" id="markAllExcused">
                                    <i class="fas fa-file-medical"></i> تحديد الكل معذرين
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- نموذج الحضور -->
                    <form id="saveAttendanceForm" method="POST" action="{{ route('attendances.store') }}">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        <input type="hidden" name="date" value="{{ $date }}">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الطالب</th>
                                        <th>حاضر</th>
                                        <th>متأخر</th>
                                        <th>غياب بدون عذر</th>
                                        <th>غياب بعذر</th>
                                        <th>ملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <input type="hidden" name="attendances[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                        <td>
                                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="present"
                                                   {{ isset($attendances[$student->id]) && $attendances[$student->id]->status=='present' ? 'checked':'' }}>
                                        </td>
                                        <td>
                                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="late"
                                                   {{ isset($attendances[$student->id]) && $attendances[$student->id]->status=='late' ? 'checked':'' }}>
                                        </td>
                                        <td>
                                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="absent"
                                                   {{ isset($attendances[$student->id]) && $attendances[$student->id]->status=='absent' ? 'checked':'' }}>
                                        </td>
                                        <td>
                                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="excused"
                                                   {{ isset($attendances[$student->id]) && $attendances[$student->id]->status=='excused' ? 'checked':'' }}>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="attendances[{{ $student->id }}][notes]"
                                                   value="{{ $attendances[$student->id]->notes ?? '' }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
                            <a href="{{ route('attendances.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> رجوع</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const attendanceRadios = document.querySelectorAll('input[type=radio][name*="[status]"]');

    function markAll(status) {
        attendanceRadios.forEach(r => {
            if(r.value === status) r.checked = true;
        });
    }

    // ربط أزرار التحديد الكلي
    document.getElementById('markAllPresent').addEventListener('click', ()=>markAll('present'));
    document.getElementById('markAllLate').addEventListener('click', ()=>markAll('late'));
    document.getElementById('markAllAbsent').addEventListener('click', ()=>markAll('absent'));
    document.getElementById('markAllExcused').addEventListener('click', ()=>markAll('excused'));

});
</script>
@endsection
