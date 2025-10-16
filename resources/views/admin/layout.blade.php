<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>
    <meta charset="utf-8" />
    <title>Therapy App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="{{asset('admin/images/favicon.ico')}}">
    <link href="{{asset('admin/libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('admin/js/layout.js')}}"></script>
    <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <style>
        :root {
            --primary-color: #0ea5e9;
            --primary-light: #38bdf8;
            --primary-dark: #0284c7;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --sidebar-bg: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.15);
            --sidebar-active: var(--primary-color);
            --header-bg: #ffffff;
            --text-light: #f8fafc;
            --text-muted: #cbd5e1;
        }

        /* Header Improvements */
        #page-topbar {
            background: var(--header-bg);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-bottom: none;
        }

        .navbar-brand-box .logo-dark,
        .navbar-brand-box .logo-light {
            font-weight: 700;
            color: var(--primary-color);
        }

        .topbar-user .header-profile-user {
            border: 2px solid var(--primary-light);
            transition: all 0.3s ease;
        }

        .topbar-user .header-profile-user:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .user-name-text {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
        }

        .dropdown-item {
            padding: 8px 16px;
            border-radius: 6px;
            margin: 2px 8px;
            width: auto;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: white;
        }

        /* Sidebar Improvements */
        .app-menu {
            background: var(--sidebar-bg);
        }

        .navbar-brand-box {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-logo {
            width: 90px;
            height: 90px;
            padding: 12px;
            background: white;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4);
        }

        .menu-title {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .nav-link.menu-link {
            color: var(--text-light);
            padding: 12px 20px;
            margin: 2px 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link.menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-link.menu-link:hover {
            background: var(--sidebar-hover);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.menu-link:hover::before {
            transform: scaleY(1);
        }

        .nav-link.menu-link.active {
            background: var(--sidebar-active);
            color: white;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4);
        }

        .nav-link.menu-link.active::before {
            transform: scaleY(1);
        }

        .nav-link.menu-link i {
            font-size: 18px;
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        /* Dropdown Menu Improvements */
        .menu-dropdown {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            margin: 5px 12px;
            padding: 8px 0;
        }

        .menu-dropdown .nav-link {
            color: var(--text-muted);
            padding: 8px 20px 8px 45px;
            border-radius: 6px;
            margin: 2px 8px;
            transition: all 0.2s ease;
            position: relative;
        }

        .menu-dropdown .nav-link::before {
            content: '';
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            background: var(--text-muted);
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .menu-dropdown .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .menu-dropdown .nav-link:hover::before {
            background: var(--primary-color);
            transform: translateY(-50%) scale(1.2);
        }

        /* Collapse Arrow Animation */
        .nav-link.menu-link[aria-expanded="true"] .ri-arrow-down-s-line {
            transform: rotate(180deg);
        }

        .nav-link.menu-link .ri-arrow-down-s-line {
            transition: transform 0.3s ease;
        }

        /* Main Content Area */
        .main-content {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .page-content {
            padding: 20px;
        }

        /* Footer */
        .footer {
            background: white;
            border-top: 1px solid #e2e8f0;
            color: var(--secondary-color);
        }

        /* Hamburger Icon */
        .hamburger-icon span {
            background: var(--primary-color);
            height: 2px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .hamburger-icon:hover span {
            background: var(--primary-dark);
        }

        /* Scrollbar Styling */
        #scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        #scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        #scrollbar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        /* Logout Button Special Styling */
        .nav-link.menu-link[href*="logout"] {
            background: rgba(239, 68, 68, 0.1);
            color: #fecaca;
            margin-top: 10px;
        }

        .nav-link.menu-link[href*="logout"]:hover {
            background: #ef4444;
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .app-menu {
                box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
            }
            
            .nav-link.menu-link {
                margin: 2px 8px;
            }
        }

        /* Additional Color Enhancements */
        .btn-vertical-sm-hover {
            color: var(--primary-light);
        }

        .btn-vertical-sm-hover:hover {
            color: white;
        }

        .dropdown-header {
            color: var(--primary-dark);
            font-weight: 600;
        }

        /* Active state improvements */
        .nav-link.menu-link.active i {
            color: white;
        }

        /* Subtle glow effects */
        .sidebar-logo {
            filter: drop-shadow(0 4px 8px rgba(14, 165, 233, 0.3));
        }

        /* Improved text contrast */
        .user-name-sub-text {
            color: #94a3b8 !important;
        }
    </style>
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="{{ url('/')}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{asset('admin/images/logo-sm.png')}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('admin/images/logo-dark.png')}}" alt="" height="17">
                                </span>
                            </a>

                            <a href="{{ url('/')}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{asset('admin/images/logo-sm.png')}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('admin/images/logo-light.png')}}" alt="" height="17">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user" src="{{asset('admin/images/users/avatar-1.jpg')}}" alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"> Admin</span>
                                        <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{ ucfirst(getlogindetail('role')) }}</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome Admin!</h6>
                                <a class="dropdown-item" href="{{url('/admin/change_password')}}"><i class="mdi mdi-key text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Change Password</span></a>
                                <a class="dropdown-item" href="{{url('/admins/logout')}}"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="{{ url('/')}}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{asset('admin/images/myphsio.png')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('admin/images/myphsio.png')}}" alt="" height="100" class="sidebar-logo">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="{{ url('/')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('admin/images/myphsio.png')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('admin/images/myphsio.png')}}" alt="" height="100" class="sidebar-logo">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">
                    <div id="two-column-menu"></div>
                    <ul class="navbar-nav" id="navbar-nav">

                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                        <li class="nav-item">
                            <a class="nav-link menu-link " href="{{ url('admin/dashboard');}}">
                                <i class="ri-home-3-line"></i> <span data-key="t-dashboards">Dashboard</span>
                            </a>
                        </li>

                        @switch(getlogindetail('role'))
                        @case('admin')

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/registration');}}">
                                <i class="ri-user-add-line"></i> <span data-key="t-registration">Users / Patients</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/role/doctor');}}">
                                <i class="ri-stethoscope-line"></i> <span data-key="t-registration">Doctors List</span>
                            </a>
                        </li>

                        <li class="nav-item">
                           <a class="nav-link menu-link" href="{{ url('admin/role/nurse');}}">
                               <i class="ri-nurse-line"></i> <span data-key="t-registration">Nurses List</span>
                           </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                                <i class="ri-calendar-check-line"></i> <span data-key="t-layouts">Appointment</span>
                                <i class="ri-arrow-down-s-line float-end"></i>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarLayouts">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/appointments/all')}}" class="nav-link" data-key="t-horizontal">All Appointments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/appointments/pending')}}" class="nav-link" data-key="t-horizontal">Pending Appointments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/appointments/completed')}}" class="nav-link" data-key="t-horizontal">Completed Appointments</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#Withdrawel" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="Withdrawel">
                                <i class="ri-wallet-3-line"></i> <span data-key="t-layouts">Withdrawel Request</span>
                                <i class="ri-arrow-down-s-line float-end"></i>
                            </a>
                            <div class="collapse menu-dropdown" id="Withdrawel">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/withdrawel/all')}}" class="nav-link" data-key="t-horizontal">All</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/location');}}">
                                <i class="ri-map-pin-line"></i> <span data-key="t-registration">Location</span>
                            </a>
                        </li>

                       <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/degrees'); }}">
                                <i class="ri-medal-line"></i> <span data-key="t-registration">Degrees</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/certificates'); }}">
                                <i class="ri-award-line"></i> <span data-key="t-registration"> Certificates</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/questions'); }}">
                                <i class="ri-question-line"></i> <span data-key="t-registration"> Questions</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/category');}}">
                                <i class="ri-list-settings-line"></i> <span data-key="t-registration">Manage Category</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/plans');}}">
                                <i class="ri-price-tag-3-line"></i> <span data-key="t-registration">Manage Plans</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/kits');}}">
                                <i class="ri-briefcase-4-line"></i> <span data-key="t-registration">Manage Kits</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/kit-order-list');}}">
                                <i class="ri-shopping-bag-line"></i> <span data-key="t-registration">Kit Order List</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/plan-order-list');}}">
                                <i class="ri-shopping-cart-2-line"></i> <span data-key="t-registration">Plan Order List</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/banners');}}">
                                <i class="ri-image-2-line"></i> <span data-key="t-registration">Manage B2b Banners</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admins/logout');}}">
                                <i class="ri-logout-box-r-line"></i> <span data-key="t-registration">Logout</span>
                            </a>
                        </li>

                        @break

                        @case('doctor')

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/doctors/profile/'.getlogindetail('id'));}}">
                                <i class="ri-user-line"></i> <span data-key="t-registration">Profile</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                                <i class="ri-calendar-check-line"></i> <span data-key="t-layouts">Appointment</span>
                                <i class="ri-arrow-down-s-line float-end"></i>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarLayouts">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/appointments/all')}}" class="nav-link" data-key="t-horizontal">All Appointments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/appointments/pending')}}" class="nav-link" data-key="t-horizontal">Pending Appointments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/appointments/completed')}}" class="nav-link" data-key="t-horizontal">Completed Appointments</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/doctors/allotchamber/'.getlogindetail('id'));}}">
                                <i class="ri-building-line"></i> <span data-key="t-registration">Assigned Clinic/Add New</span>
                            </a>
                        </li>

                        @break

                        @case('staff')

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/registration');}}">
                                <i class="ri-user-add-line"></i> <span data-key="t-registration">Registration</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                                <i class="ri-calendar-check-line"></i> <span data-key="t-layouts">Appointment</span>
                                <i class="ri-arrow-down-s-line float-end"></i>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarLayouts">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/appointments/all')}}" class="nav-link" data-key="t-horizontal">All Appointments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/appointments/pending')}}" class="nav-link" data-key="t-horizontal">Pending Appointments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/appointments/completed')}}" class="nav-link" data-key="t-horizontal">Completed Appointments</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('admin/staff/profile/'.getlogindetail('id'));}}">
                                <i class="ri-user-line"></i> <span data-key="t-registration">Profile</span>
                            </a>
                        </li>

                        @endswitch

                    </ul>
                </div>
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    @section('content')
                    @show()

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © {{ getwebdetail('title') ?? '' }}.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by <a href="https://www.wayone.co.in/" target="__blank"> Wayone It Solutions</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- JAVASCRIPT -->
    <script src="{{asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('admin/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('admin/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('admin/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('admin/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('admin/js/plugins.js')}}"></script>
    <script src="{{asset('admin/libs/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('admin/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
    <script src="{{asset('admin/libs/jsvectormap/maps/world-merc.js')}}"></script>
    <script src="{{asset('admin/libs/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('admin/js/pages/dashboard-ecommerce.init.js')}}"></script>
    <script src="{{asset('admin/js/app.js')}}"></script>
    <script src="{{asset('admin/js/custom.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
</body>

</html>