<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('admin/favicon/site.webmanifest') }}">

    <!-- Font Awesome & Icons -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css">

    <!-- AdminLTE Core -->
    <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('admin/dist/css/toastr.min.css') }}">

    <!-- Modern Logistics Style -->
    <style>
        :root {
            --primary: #0066ff;
            --primary-dark: #0052cc;
            --primary-light: #e6f0ff;
            --secondary: #ff6b35;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-light: #f8fafc;
            --bg-card: #ffffff;
            --text-dark: #0f172a;
            --text-light: #64748b;
            --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            --border-color: #e2e8f0;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
        }

        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            font-size: 14px;
        }

        /* Animated Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(0, 102, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 107, 53, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            position: relative;
            z-index: 1;
        }

        /* ===== SIDEBAR ===== */
        .main-sidebar {
            background: var(--sidebar-bg);
            border-right: none;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .main-sidebar::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .main-sidebar .brand-link {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.2rem 1rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            color: #fff !important;
            position: relative;
            z-index: 2;
        }

        .main-sidebar .brand-link .brand-image {
            max-height: 40px;
            filter: drop-shadow(0 2px 8px rgba(0, 102, 255, 0.3));
        }

        .main-sidebar .brand-link:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        /* User Panel */
        .user-panel {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            margin: 1rem;
            padding: 1rem !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-panel img {
            border: 3px solid rgba(0, 102, 255, 0.5);
            box-shadow: 0 4px 12px rgba(0, 102, 255, 0.3);
            transition: all 0.3s ease;
        }

        .user-panel:hover img {
            transform: scale(1.05);
            border-color: var(--primary);
        }

        .user-panel .info a {
            color: #fff !important;
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Sidebar Navigation */
        .sidebar {
            position: relative;
            z-index: 2;
        }

        .nav-sidebar {
            padding: 0 0.5rem;
        }

        .nav-sidebar > .nav-item {
            margin-bottom: 0.3rem;
        }

        .nav-sidebar > .nav-item > .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .nav-sidebar > .nav-item > .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-sidebar > .nav-item > .nav-link i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
            width: 24px;
            text-align: center;
        }

        .nav-sidebar > .nav-item > .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(4px);
        }

        .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, #0052cc 100%);
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 102, 255, 0.3);
        }

        .nav-sidebar > .nav-item > .nav-link.active::before {
            transform: scaleY(1);
        }

        /* ===== HEADER ===== */
        .main-header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(10px);
            z-index: 1030;
        }

        .main-header .navbar-nav .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .main-header .navbar-nav .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .main-header .navbar-nav .nav-link i {
            font-size: 1.2rem;
        }

        /* ===== CONTENT WRAPPER ===== */
        .content-wrapper {
            background: transparent;
            padding: 1.5rem;
            min-height: calc(100vh - 3.5rem);
        }

        /* Content Header */
        .content-header {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .content-header h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.75rem;
            color: var(--text-dark);
            margin: 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-item {
            color: var(--text-light);
        }

        .breadcrumb-item a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item a:hover {
            color: var(--primary);
        }

        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 600;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: var(--text-light);
            font-size: 1.2rem;
        }

        /* ===== CARDS ===== */
        .card {
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            border-radius: 16px;
            overflow: hidden;
            background: var(--bg-card);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, rgba(0, 102, 255, 0.05) 100%);
            border-bottom: 2px solid var(--primary);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .card-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 4px 12px rgba(0, 102, 255, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #003d99 100%);
            box-shadow: 0 6px 20px rgba(0, 102, 255, 0.4);
            transform: translateY(-2px);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* ===== TABLES ===== */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: var(--primary-light);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        /* ===== BADGES ===== */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* ===== FOOTER ===== */
        footer.main-footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 2rem;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.04);
        }

        footer.main-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        footer.main-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* ===== STATS CARDS ===== */
        .stats-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 15s linear infinite;
        }

        .stats-card .icon {
            font-size: 3rem;
            opacity: 0.2;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .stats-card .content {
            position: relative;
            z-index: 2;
        }

        .stats-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }

        .stats-card .label {
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 500;
        }

        /* ===== TOASTR CUSTOMIZATION ===== */
        #toast-container > div {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 1.25rem;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content > * {
            animation: fadeInUp 0.5s ease;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1rem;
            }

            .content-header {
                padding: 1rem;
            }

            .content-header h4 {
                font-size: 1.5rem;
            }

            .card-body {
                padding: 1rem;
            }
        }

        /* ===== LOADING SPINNER ===== */
        .loading-spinner {
            border: 3px solid var(--primary-light);
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <x-navbar />
        <!-- /.navbar -->

        <!-- Sidebar -->
        <aside class="main-sidebar elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <i class="fas fa-shipping-fast mr-2"></i>
                <span class="brand-text">LogisticsPro</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
                    <div class="image">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" class="img-circle elevation-2" alt="User Image">
                        @else
                            <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
                        @endif
                    </div>
                    <div class="info">
                        <a href="{{ route('admin.dashboard') }}" class="d-block">{{ Auth::user()->name }}</a>
                        <small style="color: rgba(255,255,255,0.6);">{{ Auth::user()->email }}</small>
                    </div>
                </div>
                <x-sidebar />
            </div>
        </aside>

        <!-- Content -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4>@yield('title')</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home mr-1"></i>Home</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                {{ $slot }}
            </section>
        </div>

        <footer class="main-footer">
            <strong>© 2023–{{ date('Y') }} <a href="https://denisyarif1997.github.io/Portfolio/" target="_blank">Deni Sarifudin</a></strong>
            <span class="ml-2">| Logistics Management System</span>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/toastr.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>

    <script>
        // Toastr notification settings
        toastr.options = {
            "progressBar": true,
            "closeButton": true,
            "positionClass": "toast-bottom-right",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Livewire notification handler
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (data) => {
                const type = data.type || 'success';
                const message = data.message || data;
                toastr[type](message);
            });
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Add active class animation
        $(document).ready(function() {
            $('.nav-sidebar .nav-link').on('click', function() {
                $('.nav-sidebar .nav-link').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>

    @yield('js')
</body>
</html>