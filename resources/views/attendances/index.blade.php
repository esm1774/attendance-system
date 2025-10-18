@extends('layouts.app')

@section('title', 'تسجيل الحضور والغيابة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تسجيل الحضور والغيابة</h3>
                </div>
                <div class="card-body">
                    <form id="attendanceForm" method="POST" action="{{ route('attendances.show-class') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class_id">الفصل الدراسي</label>
                                    <select class="form-control" id="class_id" name="class_id" required>
                                        <option value="">اختر الفصل</option>
                                        @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->full_path }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="subject_id">المادة الدراسية</label>
                                    <select class="form-control" id="subject_id" name="subject_id" required>
                                        <option value="">اختر المادة</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date">التاريخ</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ today()->format('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> عرض سجلات الحضور
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- يتم عرض سجلات الحضور هنا عبر Ajax -->
    <div id="attendanceRecords" class="mt-4"></div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const attendanceForm = document.getElementById('attendanceForm');

        attendanceForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            // توجيه المستخدم إلى صفحة تسجيل الحضور للفصل بأكمله
            const classId = formData.get('class_id');
            const subjectId = formData.get('subject_id');
            const date = formData.get('date');
            
            window.location.href = `/attendances/class?class_id=${classId}&subject_id=${subjectId}&date=${date}`;
        });
    });
</script>
@endsection
