@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="container-fluid">
    <!-- الإحصائيات السريعة -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_users'] }}</h3>
                    <p>إجمالي المستخدمين</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('users.index') }}" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-left"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['active_users'] }}</h3>
                    <p>المستخدمين النشطين</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <a href="{{ route('users.index') }}?status=active" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-left"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['total_roles'] }}</h3>
                    <p>الأدوار</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-tag"></i>
                </div>
                <a href="{{ route('roles.index') }}" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-left"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['total_permissions'] }}</h3>
                    <p>الصلاحيات</p>
                </div>
                <div class="icon">
                    <i class="fas fa-key"></i>
                </div>
                <a href="#" class="small-box-footer">
                    المزيد <i class="fas fa-arrow-circle-left"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- آخر المستخدمين -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">آخر المستخدمين المسجلين</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
                            عرض الكل
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الدور</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle text-secondary mr-2"></i>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $user->role->name_ar ?? 'بدون دور' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                            {{ $user->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- توزيع المستخدمين حسب الأدوار -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">توزيع المستخدمين حسب الأدوار</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="rolesChart" width="400" height="200"></canvas>
                    </div>
                    <div class="mt-3">
                        @foreach($rolesWithUsers as $role)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>
                                <i class="fas fa-circle" style="color: {{ $role->color ?? '#007bff' }}"></i>
                                {{ $role->name_ar }}
                            </span>
                            <span class="badge badge-primary">{{ $role->users_count }} مستخدم</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الروابط السريعة -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">الوصول السريع</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('users.create') }}" class="btn btn-outline-primary btn-block py-3">
                                <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                                إضافة مستخدم
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('roles.create') }}" class="btn btn-outline-success btn-block py-3">
                                <i class="fas fa-user-tag fa-2x mb-2"></i><br>
                                إضافة دور
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-info btn-block py-3">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                إدارة المستخدمين
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-warning btn-block py-3">
                                <i class="fas fa-cogs fa-2x mb-2"></i><br>
                                إدارة الأدوار
                            </a>
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
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        position: relative;
        display: block;
        margin-bottom: 20px;
    }
    .small-box > .inner {
        padding: 10px;
    }
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: bold;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
    }
    .small-box p {
        font-size: 1rem;
    }
    .small-box .icon {
        position: absolute;
        top: -10px;
        right: 10px;
        z-index: 0;
        font-size: 70px;
        color: rgba(0,0,0,0.15);
        transition: transform .3s linear;
    }
    .small-box:hover .icon {
        transform: scale(1.1);
    }
    .small-box > .small-box-footer {
        position: relative;
        text-align: center;
        padding: 3px 0;
        color: rgba(255,255,255,0.8);
        display: block;
        z-index: 10;
        background: rgba(0,0,0,0.1);
        text-decoration: none;
    }
    .small-box > .small-box-footer:hover {
        color: #fff;
        background: rgba(0,0,0,0.15);
    }
    .chart-container {
        position: relative;
        height: 200px;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // بيانات الرسم البياني
        const rolesData = @json($rolesWithUsers);
        
        const labels = rolesData.map(role => role.name_ar);
        const data = rolesData.map(role => role.users_count);
        const colors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#e83e8c', '#fd7e14'];
        
        // إنشاء الرسم البياني
        const ctx = document.getElementById('rolesChart').getContext('2d');
        const rolesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true
                    }
                }
            }
        });
    });
</script>
@endsection