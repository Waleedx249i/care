<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لوحة التحكم - Care System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Tajawal', 'Segoe UI', sans-serif;
        }
        .navbar-brand {
            font-weight: 700;
            color: #2563eb !important;
        }
        .nav-link {
            font-weight: 500;
            color: #4b5563 !important;
            transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: #2563eb !important;
        }
        @media (max-width: 991.98px) {
            .offcanvas-header {
                background-color: #f8fafc;
                border-bottom: 1px solid #e2e8f0;
            }
            .offcanvas-title {
                font-weight: 600;
            }
            .nav-link {
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                margin: 0.25rem 0;
            }
            .nav-link:hover {
                background-color: #f1f5f9;
            }
        }
        .btn-logout {
            color: #dc2626 !important;
            font-weight: 500;
        }
        .btn-logout:hover {
            color: #b91c1c !important;
            background-color: #fef2f2;
        }
        main.container {
            padding-top: 1.5rem;
            padding-bottom: 3rem;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="bi bi-heart-fill text-danger me-2"></i>
                Care System
            </a>

            <!-- Toggler for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="قائمة التنقل">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Offcanvas from top for mobile -->
            <div class="offcanvas offcanvas-top h-auto" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">القائمة الرئيسية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="إغلاق"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

                        {{-- ✅ Admin Menu --}}
                        @role('admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i> لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}" href="{{ route('admin.doctors.index') }}">
                                    <i class="bi bi-person-badge me-2"></i> الأطباء
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}" href="{{ route('admin.patients.index') }}">
                                    <i class="bi bi-people me-2"></i> المرضى
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
                                    <i class="bi bi-list-task me-2"></i> الخدمات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}" href="{{ route('admin.invoices.index') }}">
                                    <i class="bi bi-receipt me-2"></i> الفواتير
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                                    <i class="bi bi-bar-chart me-2"></i> التقارير
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                    <i class="bi bi-gear me-2"></i> الإعدادات
                                </a>
                            </li>
                        @endrole

                        {{-- ✅ Doctor Menu --}}
                        @role('doctor')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}" href="{{ route('doctor.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i> لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('doctor.calendar') ? 'active' : '' }}" href="{{ route('doctor.calendar') }}">
                                    <i class="bi bi-calendar-event me-2"></i> التقويم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('doctor.medical_records.*') ? 'active' : '' }}" href="{{ route('doctor.medical_records.index') }}">
                                    <i class="bi bi-file-medical me-2"></i> السجلات الطبية
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('doctor.prescriptions.*') ? 'active' : '' }}" href="{{ route('doctor.prescriptions.index') }}">
                                    <i class="bi bi-capsule me-2"></i> الوصفات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('doctor.invoices.*') ? 'active' : '' }}" href="{{ route('doctor.invoices.index') }}">
                                    <i class="bi bi-receipt me-2"></i> الفواتير
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('doctor.profile.*') ? 'active' : '' }}" href="{{ route('doctor.profile.edit') }}">
                                    <i class="bi bi-person me-2"></i> الملف الشخصي
                                </a>
                            </li>
                        @endrole

                        {{-- ✅ Staff Menu --}}
                        @role('staff')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i> لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.appointments.*') ? 'active' : '' }}" href="{{ route('staff.appointments.index') }}">
                                    <i class="bi bi-calendar-check me-2"></i> المواعيد
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.patients.*') ? 'active' : '' }}" href="{{ route('staff.patients.index') }}">
                                    <i class="bi bi-people me-2"></i> المرضى
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.invoices.*') ? 'active' : '' }}" href="{{ route('staff.invoices.index') }}">
                                    <i class="bi bi-receipt me-2"></i> الفواتير
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.services.*') ? 'active' : '' }}" href="{{ route('staff.services.index') }}">
                                    <i class="bi bi-list-task me-2"></i> الخدمات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('staff.reports.*') ? 'active' : '' }}" href="{{ route('staff.reports.daily') }}">
                                    <i class="bi bi-bar-chart me-2"></i> التقارير
                                </a>
                            </li>
                        @endrole

                        {{-- ✅ Patient Menu --}}
                        @role('user')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}" href="{{ route('patient.dashboard') }}">
                                    <i class="bi bi-house me-2"></i> لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('appointments.book') ? 'active' : '' }}" href="{{ route('appointments.book') }}">
                                    <i class="bi bi-calendar-plus me-2"></i> حجز موعد
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.appointments.*') ? 'active' : '' }}" href="{{ route('patient.appointments.index') }}">
                                    <i class="bi bi-calendar2-week me-2"></i> مواعيدي
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.medical_records.*') ? 'active' : '' }}" href="{{ route('patient.medical_records.index') }}">
                                    <i class="bi bi-file-medical me-2"></i> سجلي الطبي
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.prescriptions.*') ? 'active' : '' }}" href="{{ route('patient.prescriptions.index') }}">
                                    <i class="bi bi-capsule me-2"></i> وصفاتي
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.invoices.*') ? 'active' : '' }}" href="{{ route('patient.invoices.index') }}">
                                    <i class="bi bi-receipt me-2"></i> فواتيري
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.profile.*') ? 'active' : '' }}" href="{{ route('patient.profile.edit') }}">
                                    <i class="bi bi-person me-2"></i> ملفي الشخصي
                                </a>
                            </li>
                        @endrole

                        {{-- ✅ Logout --}}
                        @auth
                            <li class="nav-item mt-3 border-top pt-3 mx-3">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link nav-link btn-logout d-flex align-items-center p-0">
                                        <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
                                    </button>
                                </form>
                            </li>
                        @endauth

                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>