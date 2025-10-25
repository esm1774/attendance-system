@extends('layouts.app')

@section('title', 'تفاصيل الطالب: ' . $student->full_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل الطالب: {{ $student->full_name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="student-profile-avatar mb-3">
                                <i class="fas fa-user-graduate fa-6x text-primary"></i>
                            </div>
                            <h4>{{ $student->full_name }}</h4>
                            <p class="text-muted">{{ $student->student_id }}</p>
                            <div class="badge-group">
                                <span class="badge badge-{{ $student->status_color }} mb-1">
                                    {{ $student->status_text }}
                                </span>
                                <span class="badge badge-{{ $student->is_active ? 'success' : 'danger' }} mb-1">
                                    {{ $student->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                                <span class="badge badge-light mb-1">
                                    {{ $student->gender_text }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">الفصل الدراسي:</th>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $student->class->grade->name_ar }} - {{ $student->class->name_ar }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>الرقم الوطني:</th>
                                            <td>{{ $student->national_id ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>تاريخ الميلاد:</th>
                                            <td>
                                                {{ optional($student->birth_date)->format('Y-m-d') ?? 'غير محدد' }}
                                                @if($student->birth_date)
                                                    ({{ $student->age }} سنة)
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>مكان الميلاد:</th>
                                            <td>{{ $student->birth_place ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>الجنسية:</th>
                                            <td>{{ $student->nationality }}</td>
                                        </tr>
                                        <tr>
                                            <th>الديانة:</th>
                                            <td>{{ $student->religion }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">تاريخ التسجيل:</th>
                                            <td>{{ optional($student->enrollment_date)->format('Y-m-d') ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>نوع التسجيل:</th>
                                            <td>{{ $student->enrollment_type == 'new' ? 'جديد' : 'منقول' }}</td>
                                        </tr>
                                        <tr>
                                            <th>المدرسة السابقة:</th>
                                            <td>{{ $student->previous_school ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>الهاتف:</th>
                                            <td>{{ $student->phone ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>البريد الإلكتروني:</th>
                                            <td>{{ $student->email ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>العنوان:</th>
                                            <td>{{ $student->address ?? 'غير محدد' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات ولي الأمر -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user-shield"></i> بيانات ولي الأمر
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>اسم ولي الأمر:</strong>
                                            <p>{{ $student->guardian_name }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>صلة القرابة:</strong>
                                            <p>{{ $student->guardian_relation }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>هاتف ولي الأمر:</strong>
                                            <p>{{ $student->guardian_phone }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>بريد ولي الأمر:</strong>
                                            <p>{{ $student->guardian_email ?? 'غير محدد' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>هاتف الطوارئ:</strong>
                                            <p>{{ $student->emergency_phone ?? 'غير محدد' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>معلومات الطوارئ:</strong>
                                            <p class="text-success">{{ $student->emergency_info }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المعلومات الطبية -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-heartbeat"></i> المعلومات الطبية
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>فصيلة الدم:</strong>
                                            <p>
                                                @if($student->blood_type)
                                                    <span class="badge badge-danger">{{ $student->blood_type }}</span>
                                                @else
                                                    غير محدد
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-9">
                                            <strong>الحساسيات:</strong>
                                            <p>
                                                @if($student->allergies)
                                                    <span class="badge badge-warning">{{ $student->allergies }}</span>
                                                @else
                                                    لا توجد حساسيات معروفة
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <strong>ملاحظات طبية:</strong>
                                            <p class="border p-2 rounded bg-light">
                                                {{ $student->medical_notes ?? 'لا توجد ملاحظات طبية' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- المواد الدراسية -->
<!-- استبدل قسم المواد في صفحة عرض الطالب بهذا الكود -->

<!-- المواد الدراسية -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0" style="color: #ffffff;">
                    <i class="fas fa-book ml-2"></i>
                    المواد الدراسية ({{ $student->class->grade->subjects->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($student->class->grade->subjects->count() > 0)
                    <div class="row">
                        @foreach($student->class->grade->subjects as $subject)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-{{ $subject->pivot->is_required ? 'primary' : 'secondary' }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-book fa-2x text-{{ $subject->pivot->is_required ? 'primary' : 'secondary' }}"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">{{ $subject->name_ar }}</h6>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    @if($subject->pivot->is_required)
                                                        <span class="badge bg-primary">إجبارية</span>
                                                    @else
                                                        <span class="badge bg-secondary">اختيارية</span>
                                                    @endif
                                                    
                                                    @if($subject->pivot->weekly_hours)
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-clock ml-1"></i>
                                                            {{ $subject->pivot->weekly_hours }} حصة/أسبوع
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                @if($subject->code)
                                                    <small class="text-muted d-block mt-1">
                                                        كود: {{ $subject->code }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- ملخص المواد -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="alert alert-primary mb-0">
                                <i class="fas fa-check-circle ml-1"></i>
                                <strong>المواد الإجبارية:</strong> 
                                {{ $student->class->grade->requiredSubjects->count() }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-secondary mb-0">
                                <i class="fas fa-info-circle ml-1"></i>
                                <strong>المواد الاختيارية:</strong> 
                                {{ $student->class->grade->optionalSubjects->count() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <h5>لم يتم تحديد مواد لهذا الصف الدراسي</h5>
                        <p class="mb-3">يجب تحديد المواد الدراسية للصف {{ $student->class->grade->name_ar }}</p>
                        <a href="{{ route('grades.manage-subjects', $student->class->grade) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus ml-1"></i> إضافة مواد للصف
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

                    <!-- الملاحظات العامة -->
                    @if($student->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-sticky-note"></i> الملاحظات العامة
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p>{{ $student->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- الإحصائيات -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar"></i> الإحصائيات
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <!-- الحضور -->
                    <div class="col-md-3">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">الحضور</span>
                                <span class="info-box-number">
                                    @php
                                        $attendanceCount = $student->attendances()
                                            ->where('attendance_date', '>=', now()->subDays(30))
                                            ->where('status', 'present')
                                            ->count();
                                    @endphp
                                    {{ $attendanceCount }}
                                </span>
                                <small>آخر 30 يوم</small>
                            </div>
                        </div>
                    </div>

                    <!-- المعدل (سيتم تفعيله لاحقاً) -->
                    <div class="col-md-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">المعدل</span>
                                <span class="info-box-number">-</span>
                                <small>قريباً</small>
                            </div>
                        </div>
                    </div>

                    <!-- عدد المواد -->
                    <div class="col-md-3">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">المواد</span>
                                <span class="info-box-number">{{ $student->class->grade->subjects->count() }}</span>
                                <small>المسجلة</small>
                            </div>
                        </div>
                    </div>

                    <!-- الغياب -->
                    <div class="col-md-3">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">الغياب</span>
                                <span class="info-box-number">
                                    @php
                                        $absenceCount = $student->attendances()
                                            ->where('attendance_date', '>=', now()->startOfMonth())
                                            ->whereIn('status', ['absent', 'absent_excused'])
                                            ->count();
                                    @endphp
                                    {{ $absenceCount }}
                                </span>
                                <small>هذا الشهر</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تفاصيل إضافية -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-info-circle ml-1"></i> تفاصيل إضافية</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>نسبة الحضور:</strong>
                                        @php
                                            $totalDays = $student->attendances()
                                                ->where('attendance_date', '>=', now()->startOfMonth())
                                                ->count();
                                            $presentDays = $student->attendances()
                                                ->where('attendance_date', '>=', now()->startOfMonth())
                                                ->where('status', 'present')
                                                ->count();
                                            $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
                                        @endphp
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-{{ $attendanceRate >= 80 ? 'success' : ($attendanceRate >= 60 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $attendanceRate }}%">
                                                {{ $attendanceRate }}%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>إجمالي أيام الدراسة:</strong>
                                        <p class="mb-0 mt-2">{{ $totalDays }} يوم</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>التأخيرات:</strong>
                                        @php
                                            $lateCount = $student->attendances()
                                                ->where('attendance_date', '>=', now()->startOfMonth())
                                                ->where('status', 'late')
                                                ->count();
                                        @endphp
                                        <p class="mb-0 mt-2">
                                            <span class="badge badge-warning">{{ $lateCount }} مرة</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('students.toggle-status', $student) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $student->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $student->is_active ? 'pause' : 'play' }}"></i>
                            {{ $student->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                    <div class="btn-group float-left">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-cog"></i> إجراءات إضافية
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-print"></i> طباعة البطاقة
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-file-pdf"></i> تصدير PDF
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('students.change-status', $student) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="transferred">
                                <button type="submit" class="dropdown-item" onclick="return confirm('هل تريد نقل هذا الطالب؟')">
                                    <i class="fas fa-exchange-alt"></i> نقل طالب
                                </button>
                            </form>
                            <form action="{{ route('students.change-status', $student) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="graduated">
                                <button type="submit" class="dropdown-item" onclick="return confirm('هل تخرج هذا الطالب؟')">
                                    <i class="fas fa-graduation-cap"></i> تخرج الطالب
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .student-profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        margin: 0 auto;
        border: 3px solid #007bff;
    }
    .badge-group .badge {
        display: block;
        margin-bottom: 5px;
    }
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: 0.25rem;
        background: #fff;
        display: flex;
        margin-bottom: 1rem;
        min-height: 80px;
        padding: 0.5rem;
        position: relative;
    }
    .info-box .info-box-icon {
        border-radius: 0.25rem;
        align-items: center;
        display: flex;
        font-size: 1.875rem;
        justify-content: center;
        text-align: center;
        width: 70px;
    }
    .info-box .info-box-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1.8;
        flex: 1;
        padding: 0 10px;
    }
    .info-box .info-box-number {
        font-size: 1.5rem;
        font-weight: 700;
    }
</style>
@endsection
