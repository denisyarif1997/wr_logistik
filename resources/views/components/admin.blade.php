<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>

    <!-- Preconnect for faster font loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/favicon/favicon-16x16.png') }}">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/toastr.min.css') }}">

    <!-- Optimized Modern Style -->
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
            --sidebar-bg: #1e293b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
        }

        /* Remove universal transition for better performance */
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            font-size: 14px;
        }

        /* Simplified background - no animation */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(0,102,255,0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .wrapper { position: relative; z-index: 1; }

        /* ===== SIDEBAR - Simplified ===== */
        .main-sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #0f172a 100%);
            border-right: none;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }

        .main-sidebar .brand-link {
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 1.2rem 1rem;
            font-weight: 700;
            font-size: 1.3rem;
            color: #fff !important;
            transition: background 0.2s;
        }

        .main-sidebar .brand-link:hover {
            background: rgba(255,255,255,0.08);
        }

        .main-sidebar .brand-link .brand-image {
            max-height: 40px;
            filter: brightness(1.2);
        }

        /* User Panel */
        .user-panel {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            margin: 1rem;
            padding: 1rem !important;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .user-panel img {
            border: 2px solid rgba(0,102,255,0.5);
            transition: transform 0.2s;
            will-change: transform;
        }

        .user-panel:hover img {
            transform: scale(1.05);
        }

        .user-panel .info a {
            color: #fff !important;
            font-weight: 600;
        }

        /* Sidebar Navigation - Optimized */
        .nav-sidebar { padding: 0 0.5rem; }
        .nav-sidebar > .nav-item { margin-bottom: 0.3rem; }

        .nav-sidebar > .nav-item > .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.2s;
            will-change: transform, background-color;
        }

        .nav-sidebar > .nav-item > .nav-link i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
            width: 24px;
            text-align: center;
        }

        .nav-sidebar > .nav-item > .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(4px);
        }

        .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,102,255,0.3);
        }

        /* ===== HEADER ===== */
        .main-header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            z-index: 1030;
        }

        .main-header .navbar-nav .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .main-header .navbar-nav .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* ===== CONTENT ===== */
        .content-wrapper {
            background: transparent;
            padding: 1.5rem;
            min-height: calc(100vh - 3.5rem);
        }

        .content-header {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .content-header h4 {
            font-weight: 700;
            font-size: 1.75rem;
            color: var(--text-dark);
            margin: 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-item { color: var(--text-light); }
        .breadcrumb-item a {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.2s;
        }
        .breadcrumb-item a:hover { color: var(--primary); }
        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 600;
        }

        /* ===== CARDS - Optimized ===== */
        .card {
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            border-radius: 16px;
            background: var(--bg-card);
            transition: transform 0.2s, box-shadow 0.2s;
            will-change: transform;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, rgba(0,102,255,0.05) 100%);
            border-bottom: 2px solid var(--primary);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .card-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
        }

        .card-body { padding: 1.5rem; }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            transition: all 0.2s;
            will-change: transform;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 2px 8px rgba(0,102,255,0.25);
        }

        .btn-primary:hover {
            box-shadow: 0 4px 12px rgba(0,102,255,0.35);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success);
            box-shadow: 0 2px 8px rgba(16,185,129,0.25);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .btn-warning {
            background: var(--warning);
            box-shadow: 0 2px 8px rgba(245,158,11,0.25);
        }

        .btn-danger {
            background: var(--danger);
            box-shadow: 0 2px 8px rgba(239,68,68,0.25);
        }

        /* ===== TABLES ===== */
        .table thead th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            border: none;
            padding: 1rem;
        }

        .table tbody tr {
            transition: background-color 0.15s;
        }

        .table tbody tr:hover {
            background: var(--primary-light);
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
        }

        footer.main-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
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

        .stats-card .icon {
            font-size: 3rem;
            opacity: 0.2;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .stats-card .number {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .stats-card .label {
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 500;
        }

        /* ===== TOASTR ===== */
        #toast-container > div {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 1.25rem;
        }

        /* ===== ANIMATIONS - Simplified ===== */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .content > * {
            animation: fadeIn 0.3s ease;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-light); }
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-dark); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .content-wrapper { padding: 1rem; }
            .content-header { padding: 1rem; }
            .content-header h4 { font-size: 1.5rem; }
            .card-body { padding: 1rem; }
        }

        /* Performance optimization: reduce repaints */
        .card, .btn, .nav-link {
            backface-visibility: hidden;
            transform: translateZ(0);
        }
    </style>

    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <x-navbar />

        <aside class="main-sidebar elevation-4">
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

    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/toastr.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>

    <script>
        // Toastr settings
        toastr.options = {
            "progressBar": true,
            "closeButton": true,
            "positionClass": "toast-bottom-right",
            "timeOut": "3000",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Livewire notifications
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (data) => {
                toastr[data.type || 'success'](data.message || data);
            });
        });

        // Sidebar active state (optimized)
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