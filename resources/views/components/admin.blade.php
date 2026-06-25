@php
    // Helper function to get page title from route name
    function getPageTitle() {
        $titleMap = [
            'admin.dashboard' => 'Dashboard',
            'admin.user.*' => 'Manajemen Pengguna',
            'admin.role.*' => 'Manajemen Role',
            'admin.permission.*' => 'Manajemen Permission',
            'admin.barang.*' => 'Master Barang',
            'admin.suppliers.*' => 'Master Supplier',
            'admin.gudang.*' => 'Master Gudang',
            'admin.departemen.*' => 'Master Departemen',
            'admin.akun.*' => 'Master COA',
            'admin.ppn.*' => 'Master PPN',
            'admin.satuan.*' => 'Master Satuan',
            'admin.pembelian.*' => 'Purchase Order',
            'admin.penerimaan.*' => 'Penerimaan Barang',
            'admin.pembayaran.*' => 'Pembayaran',
            'admin.pemakaian.*' => 'Pemakaian Barang',
            'admin.transfer.*' => 'Transfer Barang',
            'admin.stok.*' => 'Laporan Stok',
            'admin.jurnal.*' => 'Laporan Jurnal',
            'admin.profile.*' => 'Profil Saya',
        ];
        
        foreach ($titleMap as $pattern => $title) {
            if (request()->routeIs($pattern)) {
                return $title;
            }
        }
        
        return 'Dashboard';
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} | {{ config('app.name') }}</title>

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5/bundle.css">

    <!-- Optimized Modern Style -->
    <style>
        /* Light Mode (Default) */
        :root, [data-theme="light"] {
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

        /* Dark Mode */
        [data-theme="dark"] {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #1e3a8a;
            --secondary: #f97316;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-light: #0f172a;
            --bg-card: #1e293b;
            --text-dark: #f1f5f9;
            --text-light: #94a3b8;
            --sidebar-bg: #0f172a;
            --border-color: #334155;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.4);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.5);
        }

        /* Remove universal transition for better performance */
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            font-size: 11px;
            transition: background-color 0.3s ease, color 0.3s ease;
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

        [data-theme="dark"] body::before {
            background: radial-gradient(circle at 20% 50%, rgba(59,130,246,0.05) 0%, transparent 50%);
        }

        .wrapper { position: relative; z-index: 1; }

        /* ===== SIDEBAR - Simplified ===== */
        .main-sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #0f172a 100%);
            border-right: none;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }

        [data-theme="dark"] .main-sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #020617 100%);
            box-shadow: 2px 0 8px rgba(0,0,0,0.5);
        }

        .main-sidebar .brand-link {
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 0.8rem 0.8rem;
            font-weight: 700;
            font-size: 1rem;
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
            border-radius: 8px;
            margin: 0.6rem;
            padding: 0.6rem !important;
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
            border-radius: 8px;
            padding: 0.5rem 0.7rem;
            font-weight: 500;
            transition: all 0.2s;
            will-change: transform, background-color;
        }

        .nav-sidebar > .nav-item > .nav-link i {
            font-size: 0.9rem;
            margin-right: 0.5rem;
            width: 20px;
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

        /* Submenu items - scaled down */
        .nav-treeview > .nav-item > .nav-link {
            padding: 0.4rem 0.7rem 0.4rem 2.5rem !important;
            font-size: 0.85rem !important;
        }
        .nav-treeview > .nav-item > .nav-link i {
            font-size: 0.5rem !important;
        }
        .nav-header {
            font-size: 0.65rem !important;
            padding: 0.4rem 0.7rem !important;
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
            padding: 0.3rem 0.7rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .main-header .navbar-nav .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* ===== CONTENT ===== */
        .content-wrapper {
            background: transparent;
            padding: 1rem;
            min-height: calc(100vh - 3rem);
        }

        .content-header {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .content-header h4 {
            font-weight: 700;
            font-size: 1.3rem;
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
            font-size: 0.75rem;
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
            padding: 0.8rem 1.2rem;
            font-weight: 600;
        }

        [data-theme="dark"] .card-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, rgba(59,130,246,0.1) 100%);
        }

        .card-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
            color: var(--text-dark);
        }

        .card-body { padding: 1rem; }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: 8px;
            padding: 0.4rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
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
            font-size: 0.7rem;
            border: none;
            padding: 0.6rem 0.8rem;
        }

        .table tbody tr {
            transition: background-color 0.15s;
        }

        .table tbody tr:hover {
            background: var(--primary-light);
        }

        [data-theme="dark"] .table tbody tr:hover {
            background: rgba(59,130,246,0.1);
        }

        .table tbody td {
            padding: 0.6rem 0.8rem;
            vertical-align: middle;
            border-color: var(--border-color);
            color: var(--text-dark);
        }

        /* ===== BADGES ===== */
        .badge {
            padding: 0.3rem 0.7rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.65rem;
        }

        /* ===== FOOTER ===== */
        footer.main-footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            text-align: center;
            padding: 0.6rem;
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 1rem;
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
            font-size: 2rem;
            font-weight: 700;
        }

        .stats-card .label {
            font-size: 0.8rem;
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
            .content-wrapper { padding: 0.6rem; }
            .content-header { padding: 0.7rem; }
            .content-header h4 { font-size: 1.1rem; }
            .card-body { padding: 0.7rem; }
        }

        /* Performance optimization: reduce repaints */
        .card, .btn, .nav-link {
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        /* Form Controls Dark Mode */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: #334155;
            border-color: #475569;
            color: #f1f5f9;
        }

        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: #334155;
            border-color: var(--primary);
            color: #f1f5f9;
        }

        [data-theme="dark"] .modal-content {
            background-color: var(--bg-card);
            color: var(--text-dark);
        }

        [data-theme="dark"] .modal-header {
            border-bottom-color: var(--border-color);
        }

        [data-theme="dark"] .modal-footer {
            border-top-color: var(--border-color);
        }

        /* Old (Jadul) Theme Overrides */
        .theme-old {
            font-family: 'Courier New', Courier, monospace !important;
        }
        .theme-old .card {
            border-radius: 0 !important;
            border: 2px solid var(--text-dark) !important;
            box-shadow: 4px 4px 0px var(--text-dark) !important;
        }
        .theme-old .btn {
            border-radius: 0 !important;
            border: 2px solid var(--text-dark) !important;
            box-shadow: 2px 2px 0px var(--text-dark) !important;
            text-transform: uppercase;
            font-weight: bold;
        }
        .theme-old .main-header, .theme-old .main-sidebar, .theme-old .main-footer {
            border: 2px solid var(--text-dark) !important;
        }
        .theme-old .nav-sidebar > .nav-item > .nav-link {
            border-radius: 0 !important;
        }
        .theme-old .stats-card {
            border-radius: 0 !important;
            border: 2px solid #fff !important;
        }
    </style>

    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed theme-{{ Auth::user()->theme ?? 'default' }}" data-theme="{{ Auth::user()->mode ?? 'light' }}">
    <div class="wrapper">
        <x-navbar />

        <aside class="main-sidebar elevation-4">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <i class="fas fa-shipping-fast mr-2"></i>
                <span class="brand-text">MyLogistics</span>
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
                    <h4>{{ $title ?? getPageTitle() }}</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home mr-1"></i>Home</a></li>
                        <li class="breadcrumb-item active">{{ $title ?? getPageTitle() }}</li>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>

    <script>
        // SweetAlert2 notification helper (Toast)
        function showNotification(message, type = 'success', title = null) {
            if (!message) return;
            
            var titleText = title || (type === 'success' ? 'Berhasil!' : type === 'error' ? 'Gagal!' : type === 'warning' ? 'Peringatan!' : 'Informasi');
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: type,
                title: titleText,
                text: message
            });
        }

        // Livewire notifications
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (data) => {
                var message = '';
                var type = 'success';
                var title = null;
                
                if (data === null || data === undefined) return;
                
                // Cek tipe data yang diterima
                if (typeof data === 'string') {
                    // Format: dispatch('notify', 'Pesan')
                    message = data;
                } else if (Array.isArray(data)) {
                    // Format dari Livewire v3: dispatch('notify', [...])
                    if (data.length === 1) {
                        var item = data[0];
                        if (typeof item === 'string') {
                            message = item;
                        } else if (typeof item === 'object') {
                            message = item.message || item[0] || '';
                            type = item.type || 'success';
                            title = item.title || null;
                        }
                    } else if (data.length >= 2) {
                        // Mungkin format: (message, type)
                        message = data[0] || '';
                        type = data[1] || 'success';
                    }
                } else if (typeof data === 'object') {
                    // Format: dispatch('notify', {message: '...', type: '...'})
                    message = data.message || data.text || '';
                    type = data.type || 'success';
                    title = data.title || null;
                }
                
                // Pastikan message adalah string
                if (typeof message !== 'string') {
                    try { message = JSON.stringify(message); } catch(e) { message = String(message); }
                }
                
                if (!message) return;
                showNotification(message, type, title);
            });
        });

        // Handle session flash messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('message'))
                showNotification('{{ session('message') }}', 'success');
            @endif
            @if(session('success'))
                showNotification('{{ session('success') }}', 'success');
            @endif
            @if(session('error'))
                showNotification('{{ session('error') }}', 'error');
            @endif
            @if(session('warning'))
                showNotification('{{ session('warning') }}', 'warning');
            @endif
            @if(session('info'))
                showNotification('{{ session('info') }}', 'info');
            @endif
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
