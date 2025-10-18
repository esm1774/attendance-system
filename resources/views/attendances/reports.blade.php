@extends('layouts.app')

@section('title', 'تقارير الحضور والغيابة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تقارير الحضور والغيابة</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('attendances.show-report') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subject_id">المادة الدراسية</label>
                                    <select class="form-control" id="subject_id" name="subject_id">
                                        <option value="">جميع المواد</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="from_date">من تاريخ</label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" value="{{ now()->subDays(30)->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="to_date">إلى تاريخ</label>
                                    <input type="date" class="form-control" id="to_date" name="to_date" value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="report_type">نوع التقرير</label>
                                    <select class="form-control" id="report_type" name="report_type" required>
                                        <option value="summary">ملخص</option>
                                        <option value="detailed">مفصل</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> عرض التقرير
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
