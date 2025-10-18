@extends('layouts.app')

@section('title', 'إدارة الأعذار')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إدارة الأعذار</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>الرقم الدراسي</th>
                                    <th>تاريخ الغياب</th>
                                    <th>نوع العذر</th>
                                    <th>السبب</th>
                                    <th>المرفقات</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الرد</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($excuses as $excuse)
                                <tr>
                                    <td>{{ $excuse->id }}</td>
                                    <td>{{ $excuse->student->full_name }}</td>
                                    <td>{{ $excuse->student->student_id }}</td>
                                    <td>{{ $excuse->absence_date }}</td>
                                    <td>
                                        @switch($excuse->type)
                                            @case('sick')
                                                <span class="badge badge-info">مرضي</span>
                                                @break
                                            @case('personal')
                                                <span class="badge badge-primary">شخصي</span>
                                                @break
                                            @case('family')
                                                <span class="badge badge-warning">عائلي</span>
                                                @break
                                            @case('other')
                                                <span class="badge badge-secondary">أخرى</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $excuse->reason }}</td>
                                    <td>
                                        @if($excuse->attachment)
                                            <a href="{{ asset('storage/' . $excuse->attachment) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-file-download"></i> عرض
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @switch($excuse->status)
                                            @case('pending')
                                                <span class="badge badge-warning">في الانتظار</span>
                                                @break
                                            @case('approved')
                                                <span class="badge badge-success">مقبول</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-danger">مرفوض</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $excuse->approved_at ? $excuse->approved_at->format('Y-m-d H:i') : '-' }}</td>
                                    <td>
                                        @if($excuse->status == 'pending')
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveExcuseModal" data-id="{{ $excuse->id }}">
                                                <i class="fas fa-check"></i> قبول
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectExcuseModal" data-id="{{ $excuse->id }}">
                                                <i class="fas fa-times"></i> رفض
                                            </button>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $excuses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal لقبول العذر -->
<div class="modal fade" id="approveExcuseModal" tabindex="-1" role="dialog" aria-labelledby="approveExcuseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveExcuseModalLabel">قبول العذر</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="#" id="approveExcuseForm">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>هل أنت متأكد من قبول هذا العذر؟</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">قبول</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal لرفض العذر -->
<div class="modal fade" id="rejectExcuseModal" tabindex="-1" role="dialog" aria-labelledby="rejectExcuseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectExcuseModalLabel">رفض العذر</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="#" id="rejectExcuseForm">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">سبب الرفض</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">رفض</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // معالجة نموذج قبول العذر
        const approveExcuseModal = document.getElementById('approveExcuseModal');
        const approveExcuseForm = document.getElementById('approveExcuseForm');

        approveExcuseModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const excuseId = button.getAttribute('data-id');

            approveExcuseForm.action = `/attendances/excuses/${excuseId}/status`;

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'approved';
            approveExcuseForm.appendChild(statusInput);
        });

        // معالجة نموذج رفض العذر
        const rejectExcuseModal = document.getElementById('rejectExcuseModal');
        const rejectExcuseForm = document.getElementById('rejectExcuseForm');

        rejectExcuseModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const excuseId = button.getAttribute('data-id');

            rejectExcuseForm.action = `/attendances/excuses/${excuseId}/status`;

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'rejected';
            rejectExcuseForm.appendChild(statusInput);
        });
    });
</script>
@endsection
