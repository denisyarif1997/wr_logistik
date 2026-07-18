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
            'admin.pengaturan.*' => 'Pengaturan COA',
            'admin.ppn.*' => 'Master PPN',
            'admin.satuan.*' => 'Master Satuan',
            'admin.pembelian.*' => 'Purchase Order',
            'admin.penerimaan.*' => 'Penerimaan Barang',
            'admin.pembayaran.*' => 'Pembayaran',
            'admin.pemakaian.*' => 'Pemakaian Barang',
            'admin.transfer.*' => 'Transfer Barang',
            'admin.stok.*' => 'Laporan Stok',
            'admin.jurnal.*' => 'Laporan Jurnal',
            'admin.laba-rugi.*' => 'Laporan Laba Rugi',
            'admin.neraca.*' => 'Laporan Neraca',
            'admin.hutang-supplier.*' => 'Laporan Hutang Supplier',
            'admin.stock-opname.*' => 'Stock Opname',
            'admin.laporan-stock-opname.*' => 'Laporan Stock Opname',
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

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

    <!-- Modern Minimalist Style — "Receipt Paper" identity for POS -->
    <style>
        /* ============ TOKENS ============ */
        /* Light Mode (Default) */
        :root, [data-theme="light"] {
            --primary: #3D5A80;
            --primary-dark: #2C4460;
            --primary-light: #EAF0F6;
            --secondary: #E0A458;
            --secondary-light: #FBF1E1;
            --success: #6B9080;
            --success-light: #E9F2EE;
            --warning: #E0A458;
            --warning-light: #FBF1E1;
            --danger: #C1494F;
            --danger-light: #FBEAEA;
            --bg-light: #F6F6F3;
            --bg-card: #FFFFFF;
            --text-dark: #1C1E21;
            --text-light: #767A80;
            --sidebar-bg: #FFFFFF;
            --border-color: #E7E4DD;
            --shadow-sm: 0 1px 2px rgba(28,30,33,0.05);
            --shadow-md: 0 6px 16px rgba(28,30,33,0.06);
            --shadow-lg: 0 16px 32px rgba(28,30,33,0.08);
        }

        /* Dark Mode */
        [data-theme="dark"] {
            --primary: #8DA9C4;
            --primary-dark: #6E8FAF;
            --primary-light: #1E2A35;
            --secondary: #E0B074;
            --secondary-light: #2E2618;
            --success: #8FB3A3;
            --success-light: #1B2723;
            --warning: #E0B074;
            --warning-light: #2E2618;
            --danger: #D3777C;
            --danger-light: #2C1B1C;
            --bg-light: #16181B;
            --bg-card: #1E2125;
            --text-dark: #ECEDEE;
            --text-light: #9CA0A6;
            --sidebar-bg: #1A1C1F;
            --border-color: #2B2E33;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.3);
            --shadow-md: 0 6px 16px rgba(0,0,0,0.35);
            --shadow-lg: 0 16px 32px rgba(0,0,0,0.45);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            font-size: 12.5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Numeric / price / stat treatment — the signature detail for a POS app */
        .mono-num, .stats-card .number, .badge, .content-header h4 small {
            font-family: 'IBM Plex Mono', monospace;
            letter-spacing: -0.01em;
        }

        [data-theme="dark"] .form-control-plaintext,
        .dark-mode .form-control-plaintext {
            color: var(--text-dark) !important;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 15% 0%, rgba(61,90,128,0.03) 0%, transparent 45%);
            pointer-events: none;
            z-index: 0;
        }

        [data-theme="dark"] body::before {
            background: radial-gradient(circle at 15% 0%, rgba(141,169,196,0.04) 0%, transparent 45%);
        }

        .wrapper { position: relative; z-index: 1; }

        /* ===== Receipt-tear divider — signature element ===== */
        .receipt-divider {
            position: relative;
            border-top: none !important;
            height: 1px;
            background-image: repeating-linear-gradient(to right, var(--border-color) 0 6px, transparent 6px 12px);
        }

        /* ===== SIDEBAR — light, airy, generous whitespace ===== */
        .main-sidebar {
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            box-shadow: none;
        }

        .main-sidebar .brand-link {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.1rem;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -0.01em;
            color: var(--text-dark) !important;
            transition: opacity 0.2s;
        }

        .main-sidebar .brand-link i {
            color: var(--primary);
        }

        .main-sidebar .brand-link:hover {
            opacity: 0.75;
        }

        .main-sidebar .brand-link .brand-image {
            max-height: 36px;
            filter: none;
        }

        /* User Panel */
        .user-panel {
            background: var(--bg-light);
            border-radius: 12px;
            margin: 0.7rem;
            padding: 0.7rem !important;
            border: 1px solid var(--border-color);
        }

        .user-panel img {
            border: 2px solid var(--bg-card);
            box-shadow: 0 0 0 1.5px var(--primary);
            transition: transform 0.2s;
            will-change: transform;
        }

        .user-panel:hover img {
            transform: scale(1.05);
        }

        .user-panel .info a {
            color: var(--text-dark) !important;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .user-panel .info small {
            color: var(--text-light) !important;
        }

        /* Sidebar Navigation */
        .nav-sidebar { padding: 0.3rem 0.6rem; }
        .nav-sidebar > .nav-item { margin-bottom: 0.15rem; }

        .nav-sidebar > .nav-item > .nav-link {
            color: var(--text-light);
            border-radius: 9px;
            padding: 0.55rem 0.75rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.18s;
        }

        .nav-sidebar > .nav-item > .nav-link i {
            font-size: 0.9rem;
            margin-right: 0.6rem;
            width: 18px;
            text-align: center;
            color: var(--text-light);
            transition: color 0.18s;
        }

        .nav-sidebar > .nav-item > .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-sidebar > .nav-item > .nav-link:hover i {
            color: var(--primary);
        }

        .nav-sidebar > .nav-item > .nav-link.active {
            background: var(--primary-light);
            color: var(--primary) !important;
            font-weight: 600;
            border-left: 3px solid var(--primary);
            box-shadow: none;
        }

        .nav-sidebar > .nav-item > .nav-link.active i {
            color: var(--primary);
        }

        /* Submenu items */
        .nav-treeview > .nav-item > .nav-link {
            padding: 0.42rem 0.75rem 0.42rem 2.6rem !important;
            font-size: 0.82rem !important;
            color: var(--text-light);
        }
        .nav-treeview > .nav-item > .nav-link i {
            font-size: 0.4rem !important;
            color: var(--text-light) !important;
        }
        .nav-header {
            font-size: 0.65rem !important;
            padding: 0.9rem 0.75rem 0.3rem !important;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
        }

        /* ===== HEADER ===== */
        .main-header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            box-shadow: none;
            z-index: 1030;
        }

        .main-header .navbar-nav .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.35rem 0.7rem;
            border-radius: 8px;
            transition: all 0.18s;
        }

        .main-header .navbar-nav .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* ===== CONTENT ===== */
        .content-wrapper {
            background: transparent;
            padding: 1.1rem 1.3rem;
            min-height: calc(100vh - 3rem);
        }

        .content-header {
            background: transparent;
            border-radius: 0;
            padding: 0.4rem 0 1.1rem;
            margin-bottom: 0.6rem;
            box-shadow: none;
            border: none;
            border-bottom: 1px dashed var(--border-color);
        }

        .content-header h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 1.25rem;
            letter-spacing: -0.01em;
            color: var(--text-dark);
            margin: 0;
            background: none;
            -webkit-background-clip: unset;
            -webkit-text-fill-color: unset;
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

        /* ===== CARDS ===== */
        .card {
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            border-radius: 14px;
            background: var(--bg-card);
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 1.2rem;
            font-weight: 600;
            border-radius: 14px 14px 0 0 !important;
        }

        .card-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
            color: var(--text-dark);
        }

        .card-body { padding: 1.1rem; }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: 9px;
            padding: 0.45rem 1.05rem;
            font-weight: 600;
            font-size: 0.75rem;
            border: none;
            transition: all 0.18s;
        }

        .btn-primary {
            background: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success);
            box-shadow: var(--shadow-sm);
        }

        .btn-success:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
        }

        .btn-warning {
            background: var(--warning);
            color: #fff;
            box-shadow: var(--shadow-sm);
        }

        .btn-danger {
            background: var(--danger);
            box-shadow: var(--shadow-sm);
        }

        .btn-outline-primary {
            border: 1px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* ===== TABLES ===== */
        .table thead th {
            background: var(--bg-light);
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.68rem;
            letter-spacing: 0.04em;
            border: none;
            border-bottom: 1px solid var(--border-color);
            padding: 0.65rem 0.8rem;
        }

        .table tbody tr {
            transition: background-color 0.15s;
        }

        .table tbody tr:hover {
            background: var(--primary-light);
        }

        .table tbody td {
            padding: 0.65rem 0.8rem;
            vertical-align: middle;
            border-color: var(--border-color);
            color: var(--text-dark);
        }

        /* ===== BADGES ===== */
        .badge {
            padding: 0.32rem 0.65rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.65rem;
        }

        /* ===== FOOTER ===== */
        footer.main-footer {
            background: var(--bg-light);
            border-top: none;
            text-align: center;
            padding: 0.9rem 0.6rem 0.6rem;
            font-size: 0.72rem;
            color: var(--text-light);
            margin-top: 1.2rem;
        }

        footer.main-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        /* ===== STATS CARDS — minimalist, accent stripe instead of full gradient fill ===== */
        .stats-card {
            background: var(--bg-card);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            border-left: 3px solid var(--primary);
            border-radius: 14px;
            padding: 1.3rem 1.4rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .stats-card .icon {
            font-size: 1.6rem;
            opacity: 1;
            position: absolute;
            right: 1.2rem;
            top: 1.2rem;
            color: var(--primary);
            background: var(--primary-light);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-card .number {
            font-size: 1.7rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .stats-card .label {
            font-size: 0.78rem;
            opacity: 0.75;
            font-weight: 500;
            color: var(--text-light);
        }

        /* ===== TOASTR ===== */
        #toast-container > div {
            box-shadow: var(--shadow-md);
            border-radius: 12px;
            padding: 1.25rem;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(2px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .content > * {
            animation: fadeIn 0.25s ease;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-light); }
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-light); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .content-wrapper { padding: 0.7rem; }
            .content-header { padding: 0.4rem 0 0.8rem; }
            .content-header h4 { font-size: 1.05rem; }
            .card-body { padding: 0.8rem; }
        }

        .card, .btn, .nav-link {
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        /* Form Controls Dark Mode */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: #23262A;
            border-color: var(--border-color);
            color: var(--text-dark);
        }

        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: #23262A;
            border-color: var(--primary);
            color: var(--text-dark);
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

        <aside class="main-sidebar elevation-0">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <i class="fas fa-shipping-fast mr-2"></i>
                <span class="brand-text">{{ config('app.name') }}</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
                    <div class="image">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" class="img-circle elevation-0" alt="User Image">
                        @else
                            <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-0" alt="User Image">
                        @endif
                    </div>
                    <div class="info">
                        <a href="{{ route('admin.dashboard') }}" class="d-block">{{ Auth::user()->name }}</a>
                        <small>{{ Auth::user()->email }}</small>
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
            <div class="receipt-divider mb-2"></div>
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