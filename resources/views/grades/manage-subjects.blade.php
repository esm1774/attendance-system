@extends('layouts.app')

@section('title', 'إدارة مواد الصف')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0" style="color: #ffffff;">
                        <i class="fas fa-book ml-2"></i>
                        إدارة مواد: {{ $grade->name_ar }}
                    </h3>
                    <a href="{{ route('grades.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <!-- معلومات الصف -->
                    <div class="alert alert-info mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <strong><i class="fas fa-layer-group ml-1"></i> المرحلة:</strong>
                                {{ $grade->stage->name_ar }}
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-graduation-cap ml-1"></i> الصف:</strong>
                                {{ $grade->name_ar }}
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-book ml-1"></i> عدد المواد الحالية:</strong>
                                {{ $grade->subjects->count() }}
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('grades.update-subjects', $grade) }}" method="POST">
                        @csrf

                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0" style="color: #ffffff;">
                                    <i class="fas fa-list ml-2"></i>
                                    اختر المواد الدراسية للصف
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50" class="text-center">
                                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                                </th>
                                                <th>اسم المادة</th>
                                                <th>النوع</th>
                                                <th width="120" class="text-center">إجبارية</th>
                                                <th width="150">عدد الحصص الأسبوعية</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($subjects as $subject)
                                                @php
                                                    $isSelected = $gradeSubjects->has($subject->id);
                                                    $pivot = $gradeSubjects->get($subject->id);
                                                @endphp
                                                <tr>
                                                    <td class="text-center">
                                                        <input class="form-check-input subject-checkbox" 
                                                               type="checkbox" 
                                                               name="subjects[]" 
                                                               value="{{ $subject->id }}" 
                                                               id="subject_{{ $subject->id }}"
                                                               {{ $isSelected ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <label for="subject_{{ $subject->id }}" class="mb-0" style="cursor: pointer;">
                                                            <strong>{{ $subject->name_ar }}</strong>
                                                            @if($subject->code)
                                                                <br><small class="text-muted">كود: {{ $subject->code }}</small>
                                                            @endif
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $subject->type == 'academic' ? 'primary' : 'info' }}">
                                                            {{ $subject->type == 'academic' ? 'أكاديمية' : 'نشاط' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-check form-switch d-inline-block">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   name="is_required_{{ $subject->id }}" 
                                                                   value="1"
                                                                   id="required_{{ $subject->id }}"
                                                                   {{ $isSelected && $pivot->pivot->is_required ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               class="form-control form-control-sm" 
                                                               name="weekly_hours_{{ $subject->id }}"
                                                               value="{{ $isSelected ? $pivot->pivot->weekly_hours : '' }}"
                                                               min="1"
                                                               max="20"
                                                               placeholder="عدد الحصص">
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">
                                                        <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                                        <p class="text-muted">لا توجد مواد متاحة</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- ملخص الاختيار -->
                                <div class="alert alert-secondary mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <i class="fas fa-info-circle ml-1"></i>
                                            <strong>عدد المواد المحددة:</strong>
                                            <span id="selectedCount" class="badge bg-primary">{{ $gradeSubjects->count() }}</span>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <small class="text-muted">
                                                <i class="fas fa-lightbulb ml-1"></i>
                                                يمكنك تحديد المواد كإجبارية أو اختيارية
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times ml-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save ml-1"></i> حفظ التغييرات
                            </button>
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
    $(document).ready(function() {
        // تحديث عداد المواد المحددة
        function updateCount() {
            const count = $('.subject-checkbox:checked').length;
            $('#selectedCount').text(count);
        }

        // تحديد/إلغاء تحديد الكل
        $('#selectAll').on('change', function() {
            $('.subject-checkbox').prop('checked', $(this).is(':checked'));
            updateCount();
        });

        // تحديث العداد عند تغيير أي checkbox
        $('.subject-checkbox').on('change', function() {
            updateCount();
            
            // تحديث حالة "تحديد الكل"
            const allChecked = $('.subject-checkbox').length === $('.subject-checkbox:checked').length;
            $('#selectAll').prop('checked', allChecked);
        });

        // تفعيل/تعطيل حقول عدد الحصص والإجبارية عند تحديد المادة
        $('.subject-checkbox').on('change', function() {
            const subjectId = $(this).val();
            const isChecked = $(this).is(':checked');
            
            $(`input[name="weekly_hours_${subjectId}"]`).prop('disabled', !isChecked);
            $(`input[name="is_required_${subjectId}"]`).prop('disabled', !isChecked);
        });

        // تفعيل الحقول عند تحميل الصفحة
        $('.subject-checkbox:checked').each(function() {
            const subjectId = $(this).val();
            $(`input[name="weekly_hours_${subjectId}"]`).prop('disabled', false);
            $(`input[name="is_required_${subjectId}"]`).prop('disabled', false);
        });

        // إخفاء التنبيهات تلقائياً
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection

@section('styles')
<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .form-check-input {
        cursor: pointer;
    }
    
    label {
        cursor: pointer;
    }
</style>
@endsection