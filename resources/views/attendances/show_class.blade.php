<div class="card">
    <div class="card-header">
        <h3 class="card-title">سجلات الحضور والغيابة</h3>
        <div class="card-tools">
            <span class="badge badge-info">{{ $class->full_path }}</span>
            <span class="badge badge-secondary ml-2">{{ $subject->name }}</span>
            <span class="badge badge-primary ml-2">{{ $date }}</span>
        </div>
    </div>
    <div class="card-body">
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
                            <th>الرقم الدراسي</th>
                            <th>حالة الحضور</th>
                            <th>وقت الوصول</th>
                            <th>وقت المغادرة</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->student_id }}</td>
                            <td>
                                <div class="form-group mb-0">
                                    <select name="attendances[{{ $student->id }}][student_id]" type="hidden" value="{{ $student->id }}">
                                    <select name="attendances[{{ $student->id }}][status]" class="form-control attendance-status">
                                        <option value="present" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status == 'present' ? 'selected' : '' }}>حاضر</option>
                                        <option value="absent" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status == 'absent' ? 'selected' : '' }}>غائب</option>
                                        <option value="late" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status == 'late' ? 'selected' : '' }}>متأخر</option>
                                        <option value="excused" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status == 'excused' ? 'selected' : '' }}>معتذر</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group mb-0">
                                    <input type="time" name="attendances[{{ $student->id }}][arrival_time]" class="form-control" 
                                        value="{{ isset($attendances[$student->id]) ? $attendances[$student->id]->arrival_time : '' }}">
                                </div>
                            </td>
                            <td>
                                <div class="form-group mb-0">
                                    <input type="time" name="attendances[{{ $student->id }}][departure_time]" class="form-control" 
                                        value="{{ isset($attendances[$student->id]) ? $attendances[$student->id]->departure_time : '' }}">
                                </div>
                            </td>
                            <td>
                                <div class="form-group mb-0">
                                    <input type="text" name="attendances[{{ $student->id }}][notes]" class="form-control" 
                                        value="{{ isset($attendances[$student->id]) ? $attendances[$student->id]->notes : '' }}">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ سجلات الحضور
                    </button>
                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> رجوع
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .attendance-status {
        min-width: 120px;
    }
</style>
