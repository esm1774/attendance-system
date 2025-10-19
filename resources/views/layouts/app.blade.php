<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - نظام إدارة الحضور</title>
    
    <!-- Google Fonts - Tajawal -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS File -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    <!-- Inline Styles (Keep minimal inline styles) -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            min-height: 100vh;
        }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #ffffff !important;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }
        
        .navbar-brand i {
            margin-left: 10px;
            font-size: 1.8rem;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff !important;
            transform: translateY(-2px);
        }
        
        .nav-link i {
            margin-left: 8px;
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(0, 0, 0, 0.1);
        }
        
        /* Content Wrapper */
        .content-wrapper {
            min-height: calc(100vh - 180px);
            padding: 2rem;
            background-color: transparent;
        }
        
        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: #ffffff;
            padding: 1.5rem;
            margin-top: 2rem;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Navbar Toggler */
        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.5);
            padding: 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1rem;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-graduation-cap"></i>
                    نظام إدارة الحضور
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">
                                <i class="fas fa-home"></i> الرئيسية
                            </a>
                        </li>
                        
                        <!-- قائمة الإعدادات العامة -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i> إعدادات عامة
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('schools.index') }}">
                                        <i class="fas fa-school"></i> المدارس
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('stages.index') }}">
                                        <i class="fas fa-layer-group"></i> المراحل الدراسية
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('grades.index') }}">
                                        <i class="fas fa-chart-line"></i> الصفوف الدراسية
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('classes.index') }}">
                                        <i class="fas fa-door-open"></i> الفصول الدراسية
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('subjects.index') }}">
                                        <i class="fas fa-book"></i> المواد الدراسية
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- المستخدمين والأدوار -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="usersDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-users-cog"></i> المستخدمين
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('users.index') }}">
                                        <i class="fas fa-users"></i> إدارة المستخدمين
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('roles.index') }}">
                                        <i class="fas fa-user-tag"></i> الأدوار والصلاحيات
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- الطلاب -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('students.index') }}">
                                <i class="fas fa-user-graduate"></i> الطلاب
                            </a>
                        </li>
                        
                        <!-- الحضور والغياب -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="attendanceDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-calendar-check"></i> الحضور والغياب
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('attendances.index') }}">
                                        <i class="fas fa-user-check"></i> تسجيل الحضور
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('attendances.reports') }}">
                                        <i class="fas fa-chart-bar"></i> التقارير
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('attendances.excuses') }}">
                                        <i class="fas fa-file-medical"></i> الأعذار
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('attendances.statistics') }}">
                                        <i class="fas fa-chart-pie"></i> الإحصائيات
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    
                    <!-- User Menu -->
                    <ul class="navbar-nav">
                        @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user-cog"></i> الملف الشخصي
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog"></i> الإعدادات
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-sign-in-alt"></i> تسجيل الدخول
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="content-wrapper">
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
            
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer text-center">
            <strong>جميع الحقوق محفوظة &copy; {{ date('Y') }} نظام إدارة الحضور والغياب</strong>
            <div class="mt-2">
                <small>
                    <i class="fas fa-code"></i> تم التطوير بواسطة فريق العمل
                </small>
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
            
            // Initialize DataTables if exists
            if ($.fn.DataTable) {
                // تحقق إذا كان الجدول قد تم تهيئته من قبل
                if (!$.fn.DataTable.isDataTable('.data-table')) {
                    $('.data-table').DataTable({
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
                        },
                        pageLength: 25,
                        responsive: true,
                        order: [[0, 'asc']]
                    });
                }
            }
        });
    </script>

    @yield('scripts')
</body>
</html>