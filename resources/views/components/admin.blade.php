@php
    // Helper function to get page title from route name
    if (!function_exists('getPageTitle')) {
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
                'admin.penyesuaian.*' => 'Penyesuaian Stok',
                'admin.kasir.*' => 'Kasir POS Penjualan',
                'admin.penjualan.*' => 'Kasir POS Penjualan',
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
    }
@endphp

<!DOCTYPE html>
<html lang="en" data-theme="{{ Auth::user()->mode ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <title>{{ $title ?? 'Dashboard' }} | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/favicon/favicon-16x16.png') }}">

    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5/bundle.css">
    @livewireStyles

    <style>
        /* ============================================================
           ENTERPRISE DASHBOARD — Modern Logistics ERP Theme
           Design: Flat, Clean, High Information Density
           Inspired by: ERPNext, Linear, Atlassian Jira, VS Code
           ============================================================ */

        /* ----- TOKENS (Light Mode) ----- */
        :root, [data-theme="light"] {
            --primary: #0F6CBD;
            --primary-hover: #0D5BA6;
            --primary-light: #EBF4FF;
            --primary-border: #B3D4F7;
            --secondary: #2D9CDB;
            --success: #27AE60;
            --success-light: #E8F8F0;
            --warning: #F2C94C;
            --warning-light: #FEF9E7;
            --danger: #EB5757;
            --danger-light: #FDEDED;
            --info: #2D9CDB;

            --bg-body: #F7F9FC;
            --bg-card: #FFFFFF;
            --bg-sidebar: #FFFFFF;
            --bg-header: #FFFFFF;
            --bg-input: #FFFFFF;
            --bg-hover: #F2F4F7;
            --bg-selected: #EBF4FF;

            --text-primary: #1A1D26;
            --text-secondary: #5A6070;
            --text-muted: #8B90A0;
            --text-inverse: #FFFFFF;

            --border-color: #E5E7EB;
            --border-focus: #0F6CBD;

            --shadow-xs: 0 1px 2px rgba(26,29,38,0.04);
            --shadow-sm: 0 1px 3px rgba(26,29,38,0.06), 0 1px 2px rgba(26,29,38,0.04);
            --shadow-md: 0 4px 6px rgba(26,29,38,0.05), 0 2px 4px rgba(26,29,38,0.04);
            --shadow-lg: 0 10px 15px rgba(26,29,38,0.06), 0 4px 6px rgba(26,29,38,0.04);
            --shadow-xl: 0 20px 25px rgba(26,29,38,0.08), 0 8px 10px rgba(26,29,38,0.04);

            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;

            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-mono: 'SF Mono', 'Fira Code', 'Fira Mono', monospace;

            --transition-fast: 150ms ease;
            --transition-normal: 200ms ease;

            --sidebar-width: 260px;
            --sidebar-collapsed-width: 0px;
            --header-height: 52px;
        }

        /* ----- TOKENS (Dark Mode) ----- */
        [data-theme="dark"] {
            --primary: #3B82F6;
            --primary-hover: #60A5FA;
            --primary-light: #1E293B;
            --primary-border: #1E3A5F;
            --success: #34D399;
            --success-light: #064E3B;
            --warning: #FBBF24;
            --warning-light: #451A03;
            --danger: #F87171;
            --danger-light: #450A0A;
            --info: #38BDF8;

            --bg-body: #0F1117;
            --bg-card: #1A1D26;
            --bg-sidebar: #12141C;
            --bg-header: #1A1D26;
            --bg-input: #1A1D26;
            --bg-hover: #23262F;
            --bg-selected: #1E293B;

            --text-primary: #E2E4E9;
            --text-secondary: #9498A5;
            --text-muted: #6B6F7D;
            --text-inverse: #FFFFFF;

            --border-color: #2B2E37;
            --border-focus: #3B82F6;

            --shadow-xs: 0 1px 2px rgba(0,0,0,0.2);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.25), 0 1px 2px rgba(0,0,0,0.2);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.3), 0 2px 4px rgba(0,0,0,0.2);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.3), 0 4px 6px rgba(0,0,0,0.2);
            --shadow-xl: 0 20px 25px rgba(0,0,0,0.4), 0 8px 10px rgba(0,0,0,0.2);
        }

        /* ----- BASE RESET ----- */
        *, *::before, *::after { box-sizing: border-box; }

        html {
            background-color: var(--bg-body);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            height: 100%;
        }

        body {
            font-family: var(--font-sans);
            font-size: 14px;
            line-height: 1.5;
            color: var(--text-primary);
            background: var(--bg-body);
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
        }

        /* ----- APP LAYOUT ----- */
        .app-layout {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .app-body {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ----- HEADER ----- */
        .app-header {
            height: var(--header-height);
            min-height: var(--header-height);
            background: var(--bg-header);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 16px;
            gap: 12px;
            z-index: 100;
            position: relative;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 16px;
            color: var(--text-primary);
            text-decoration: none;
            white-space: nowrap;
            min-width: 180px;
        }

        .header-brand i {
            color: var(--primary);
            font-size: 20px;
        }

        .header-search {
            flex: 1;
            max-width: 480px;
            position: relative;
        }

        .header-search input {
            width: 100%;
            height: 34px;
            padding: 0 14px 0 36px;
            background: var(--bg-body);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-size: 13px;
            font-family: var(--font-sans);
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }

        .header-search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15,108,189,0.1);
        }

        .header-search input::placeholder { color: var(--text-muted); }

        .header-search .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
            pointer-events: none;
        }

        .header-search .search-shortcut {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 11px;
            color: var(--text-muted);
            background: var(--bg-hover);
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            font-family: var(--font-mono);
            pointer-events: none;
        }

        /* ----- GLOBAL SEARCH DROPDOWN ----- */
        .search-results-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            max-height: 320px;
            overflow-y: auto;
            z-index: 1050;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: var(--text-secondary);
            text-decoration: none;
            cursor: pointer;
            transition: background var(--transition-fast), color var(--transition-fast);
            border-bottom: 1px solid var(--border-color);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover,
        .search-result-item.selected {
            background: var(--bg-hover);
            color: var(--text-primary);
            text-decoration: none;
        }

        .search-result-item i {
            font-size: 16px;
            color: var(--primary);
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .search-result-item .result-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .search-result-item .result-title {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-result-item .result-category {
            font-size: 10.5px;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }

        .search-no-results {
            padding: 16px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-left: auto;
        }

        .header-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: 18px;
            transition: background var(--transition-fast), color var(--transition-fast);
            position: relative;
            text-decoration: none;
        }

        .header-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .header-btn .badge-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid var(--bg-header);
        }

        .header-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color var(--transition-fast);
        }

        .header-avatar:hover { border-color: var(--primary); }

        /* ----- SIDEBAR ----- */
        .app-sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            height: calc(100vh - var(--header-height));
            overflow: hidden;
            transition: width var(--transition-normal), min-width var(--transition-normal);
            position: relative;
        }

        .app-sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
            min-width: var(--sidebar-collapsed-width);
        }

        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px 0;
            min-height: 0;
        }

        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        .sidebar-user {
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 4px;
        }

        .sidebar-user img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .sidebar-user-info { flex: 1; min-width: 0; }

        .sidebar-user-info .name {
            font-weight: 600;
            font-size: 13px;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-info .role {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* ----- SIDEBAR NAV ----- */
        .nav-menu { list-style: none; padding: 0; margin: 0; }

        .nav-header {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            padding: 16px 16px 6px;
        }

        .nav-item { margin: 1px 6px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: background var(--transition-fast), color var(--transition-fast);
            cursor: pointer;
            position: relative;
        }

        .nav-link:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: var(--primary);
            border-radius: 3px;
        }

        .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-link .nav-badge {
            margin-left: auto;
            font-size: 10px;
            padding: 1px 6px;
            border-radius: 10px;
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .nav-link .chevron {
            margin-left: auto;
            font-size: 14px;
            color: var(--text-muted);
            transition: transform var(--transition-fast);
        }

        .nav-link .chevron.open { transform: rotate(90deg); }

        /* Submenu */
        .nav-treeview {
            list-style: none;
            padding: 0;
            margin: 2px 0 2px 32px;
            display: none;
        }

        .nav-treeview.open { display: block; }

        .nav-treeview .nav-link {
            padding: 6px 12px;
            font-size: 12.5px;
        }

        /* ----- MAIN CONTENT ----- */
        .app-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: var(--bg-body);
        }

        /* Tab Bar */
        .tab-bar {
            height: 36px;
            min-height: 36px;
            background: var(--bg-header);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 8px;
            gap: 2px;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .tab-bar::-webkit-scrollbar { height: 2px; }
        .tab-bar::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 2px; }

        .tab-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 12px;
            color: var(--text-secondary);
            border-radius: 4px 4px 0 0;
            cursor: pointer;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            transition: color var(--transition-fast), border-color var(--transition-fast);
            user-select: none;
        }

        .tab-item:hover {
            color: var(--text-primary);
            background: var(--bg-hover);
        }

        .tab-item.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            font-weight: 500;
        }

        .tab-item .tab-close {
            font-size: 14px;
            opacity: 0;
            transition: opacity var(--transition-fast);
        }

        .tab-item:hover .tab-close { opacity: 0.6; }
        .tab-item .tab-close:hover { opacity: 1; }

        /* ----- CONTENT AREA ----- */
        .content-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px 24px;
        }

        .content-area::-webkit-scrollbar { width: 6px; }
        .content-area::-webkit-scrollbar-track { background: transparent; }
        .content-area::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 6px;
        }
        .content-area::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        .content-header {
            margin-bottom: 20px;
        }

        .content-header h4 {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 4px;
            letter-spacing: -0.02em;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .breadcrumb li + li::before {
            content: '/';
            margin-right: 6px;
            color: var(--text-muted);
        }

        .breadcrumb a {
            color: var(--text-secondary);
            text-decoration: none;
        }

        .breadcrumb a:hover { color: var(--primary); }

        .breadcrumb .active {
            color: var(--text-primary);
            font-weight: 500;
        }

        /* ----- CARDS ----- */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xs);
            transition: box-shadow var(--transition-fast);
        }

        .card:hover { box-shadow: var(--shadow-sm); }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-header h3,
        .card-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            color: var(--text-primary);
        }

        .card-body { padding: 20px; }

        .card-footer {
            padding: 12px 20px;
            border-top: 1px solid var(--border-color);
            background: var(--bg-body);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        }

        .card-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ----- BUTTONS ----- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            font-family: var(--font-sans);
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            cursor: pointer;
            transition: background var(--transition-fast), border-color var(--transition-fast), box-shadow var(--transition-fast);
            text-decoration: none;
            line-height: 1.4;
            white-space: nowrap;
        }

        .btn:focus { outline: none; }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-secondary);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .btn-success {
            background: var(--success);
            color: #fff;
            border-color: var(--success);
        }

        .btn-success:hover { filter: brightness(1.08); }

        .btn-danger {
            background: var(--danger);
            color: #fff;
            border-color: var(--danger);
        }

        .btn-danger:hover { filter: brightness(1.08); }

        .btn-warning {
            background: var(--warning);
            color: #1A1D26;
            border-color: var(--warning);
        }

        .btn-warning:hover { filter: brightness(1.08); }

        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
        }

        .btn-lg {
            padding: 10px 20px;
            font-size: 14px;
        }

        .btn-icon {
            width: 34px;
            height: 34px;
            padding: 0;
        }

        /* ----- FORMS ----- */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            height: 38px;
            padding: 0 12px;
            font-size: 13px;
            font-family: var(--font-sans);
            color: var(--text-primary);
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15,108,189,0.1);
        }

        .form-control::placeholder { color: var(--text-muted); }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238B90A0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 32px;
        }

        textarea.form-control {
            height: auto;
            padding: 10px 12px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-error {
            font-size: 12px;
            color: var(--danger);
            margin-top: 4px;
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(235,87,87,0.1);
        }

        /* ----- TABLES ----- */
        .table-container {
            overflow-x: auto;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
        }

        .table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table thead th {
            background: var(--bg-body);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
            user-select: none;
        }

        .table thead th:first-child { border-radius: var(--radius-lg) 0 0 0; }
        .table thead th:last-child { border-radius: 0 var(--radius-lg) 0 0; }

        .table thead th.sortable { cursor: pointer; }
        .table thead th.sortable:hover { color: var(--text-primary); }

        .table thead th .sort-icon {
            margin-left: 4px;
            font-size: 10px;
            opacity: 0.3;
        }

        .table thead th.sortable:hover .sort-icon { opacity: 0.6; }

        .table tbody tr {
            transition: background var(--transition-fast);
        }

        .table tbody tr:nth-child(even) {
            background: var(--bg-body);
        }

        .table tbody tr:hover {
            background: var(--bg-hover);
        }

        .table tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            vertical-align: middle;
        }

        .table tbody tr:last-child td:first-child { border-radius: 0 0 0 var(--radius-lg); }
        .table tbody tr:last-child td:last-child { border-radius: 0 0 var(--radius-lg) 0; }

        /* ----- BADGES ----- */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 10px;
            white-space: nowrap;
        }

        .badge-primary {
            background: var(--primary-light);
            color: var(--primary);
        }

        .badge-success {
            background: var(--success-light);
            color: var(--success);
        }

        .badge-warning {
            background: var(--warning-light);
            color: #B8860B;
        }

        .badge-danger {
            background: var(--danger-light);
            color: var(--danger);
        }

        /* ----- FILTER CHIPS ----- */
        .filter-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 500;
            background: var(--bg-body);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: background var(--transition-fast), border-color var(--transition-fast), color var(--transition-fast);
        }

        .filter-chip:hover {
            background: var(--bg-hover);
            border-color: var(--text-muted);
        }

        .filter-chip.active {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        .filter-chip i { font-size: 12px; }

        /* ----- PAGINATION ----- */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            gap: 16px;
        }

        .pagination-info {
            font-size: 12px;
            color: var(--text-muted);
        }

        .pagination-buttons {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .page-btn {
            min-width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            font-size: 13px;
            color: var(--text-secondary);
            background: transparent;
            border: 1px solid transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: background var(--transition-fast), border-color var(--transition-fast);
        }

        .page-btn:hover {
            background: var(--bg-hover);
            border-color: var(--border-color);
        }

        .page-btn.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .page-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* ----- STATS CARDS (KPI) ----- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-xs);
            transition: box-shadow var(--transition-fast);
            position: relative;
            overflow: hidden;
        }

        .kpi-card:hover { box-shadow: var(--shadow-sm); }

        .kpi-card .kpi-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .kpi-card .kpi-icon.blue { background: var(--primary-light); color: var(--primary); }
        .kpi-card .kpi-icon.green { background: var(--success-light); color: var(--success); }
        .kpi-card .kpi-icon.yellow { background: var(--warning-light); color: #B8860B; }
        .kpi-card .kpi-icon.red { background: var(--danger-light); color: var(--danger); }

        .kpi-card .kpi-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.03em;
            line-height: 1.2;
        }

        .kpi-card .kpi-label {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .kpi-card .kpi-change {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 8px;
        }

        .kpi-card .kpi-change.up { color: var(--success); }
        .kpi-card .kpi-change.down { color: var(--danger); }

        /* ----- SKELETON LOADING ----- */
        .skeleton {
            background: linear-gradient(90deg, var(--bg-hover) 25%, var(--border-color) 50%, var(--bg-hover) 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite;
            border-radius: var(--radius-md);
        }

        @keyframes skeleton-shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .skeleton-text {
            height: 14px;
            margin-bottom: 8px;
            width: 80%;
        }

        .skeleton-title {
            height: 20px;
            margin-bottom: 12px;
            width: 60%;
        }

        .skeleton-card {
            height: 120px;
            margin-bottom: 16px;
        }

        /* ----- TOASTR / NOTIFICATION ----- */
        #toast-container > div {
            box-shadow: var(--shadow-lg) !important;
            border-radius: var(--radius-md) !important;
            padding: 14px 18px !important;
            border: none !important;
            font-family: var(--font-sans) !important;
        }

        /* ----- SWEETALERT OVERRIDES ----- */
        .swal2-popup {
            font-family: var(--font-sans) !important;
            border-radius: var(--radius-lg) !important;
            padding: 24px !important;
        }

        .swal2-title {
            font-size: 18px !important;
            font-weight: 600 !important;
        }

        /* ----- MODAL ----- */
        .modal-backdrop {
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(2px);
        }

        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header .modal-title {
            font-size: 16px;
            font-weight: 600;
        }

        .modal-body { padding: 20px; }

        .modal-footer {
            padding: 12px 20px;
            border-top: 1px solid var(--border-color);
        }

        /* ----- ALERTS ----- */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            font-size: 13px;
            border: 1px solid transparent;
        }

        .alert-info {
            background: var(--primary-light);
            border-color: var(--primary-border);
            color: var(--primary);
        }

        .alert-success {
            background: var(--success-light);
            border-color: var(--success);
            color: var(--success);
        }

        .alert-warning {
            background: var(--warning-light);
            border-color: var(--warning);
            color: #B8860B;
        }

        .alert-danger {
            background: var(--danger-light);
            border-color: var(--danger);
            color: var(--danger);
        }

        /* ----- FOOTER ----- */
        .app-footer {
            height: 36px;
            min-height: 36px;
            background: var(--bg-header);
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            font-size: 11px;
            color: var(--text-muted);
        }

        .app-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        /* ----- RESPONSIVE ----- */
        @media (min-width: 1920px) {
            .content-area { padding: 24px 32px; }
            .stats-grid { grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
        }

        @media (min-width: 2560px) {
            .content-area { padding: 32px 48px; }
        }

        @media (max-width: 1366px) {
            .content-area { padding: 16px 20px; }
        }

        @media (max-width: 768px) {
            .app-sidebar { width: 0; min-width: 0; }
            .app-sidebar.open { width: var(--sidebar-width); min-width: var(--sidebar-width); }
            .header-search { max-width: 200px; }
            .search-shortcut { display: none; }
            .content-area { padding: 12px 16px; }
            .form-row { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
        }

        /* ----- UTILITY ----- */
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-8 { gap: 8px; }
        .gap-12 { gap: 12px; }
        .gap-16 { gap: 16px; }
        .mb-8 { margin-bottom: 8px; }
        .mb-12 { margin-bottom: 12px; }
        .mb-16 { margin-bottom: 16px; }
        .mb-20 { margin-bottom: 20px; }
        .mt-8 { margin-top: 8px; }
        .mt-12 { margin-top: 12px; }
        .mt-16 { margin-top: 16px; }
        .p-16 { padding: 16px; }
        .p-20 { padding: 20px; }
        .text-center { text-align: center; }
        .text-muted { color: var(--text-muted); }
        .text-secondary { color: var(--text-secondary); }
        .text-primary { color: var(--primary); }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }
        .text-warning { color: var(--warning); }
        .w-full { width: 100%; }

        /* SPA Navigation Override */
        [wire\:loading], [wire\:loading\.delay], [wire\:loading\.inline-block],
        [wire\:loading\.inline], [wire\:loading\.block], [wire\:loading\.flex],
        [wire\:loading\.table], [wire\:loading\.grid], [wire\:loading\.inline-flex] {
            display: none !important;
        }

        .spa-navigation-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            background: var(--bg-body);
            color: var(--text-muted);
            font-size: 14px;
        }

        .spa-navigation-overlay.is-visible { display: flex; }

        /* Transition override for SPA */
        .hold-transition,
        .hold-transition .wrapper,
        .hold-transition .main-sidebar,
        .hold-transition .main-header,
        .hold-transition .content-wrapper,
        .hold-transition .main-footer {
            transition: none !important;
        }
    </style>

    @yield('css')
</head>

<body class="theme-{{ Auth::user()->theme ?? 'default' }}">
    <div class="app-layout">
        <!-- HEADER -->
        @persist('navbar')
        <header class="app-header">
            <button class="header-btn" id="sidebar-toggle-btn" title="Toggle Sidebar" style="margin-right: 8px;">
                <i class="ri-menu-line"></i>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="header-brand">
                <i class="fas fa-shipping-fast"></i>
                <span>{{ config('app.name') }}</span>
            </a>

            <div class="header-search">
                <i class="ri-search-line search-icon"></i>
                <input type="text" placeholder="Cari menu, data, atau laporan..." id="global-search-input" autocomplete="off" />
                <span class="search-shortcut">Ctrl+K</span>
                <div class="search-results-dropdown" id="global-search-results" style="display: none;"></div>
            </div>

            <div class="header-actions">
                <a href="{{ route('admin.profile.edit') }}" class="header-btn" title="Profil Saya" wire:navigate.hover>
                    <i class="ri-user-line"></i>
                </a>
                <button class="header-btn" title="Notifikasi">
                    <i class="ri-notification-3-line"></i>
                    <span class="badge-dot"></span>
                </button>
                <button class="header-btn" title="Ganti Tema" id="theme-toggle-btn">
                    <i class="ri-contrast-2-line"></i>
                </button>
                <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0; padding:0;">
                    @csrf
                    <button type="submit" class="header-btn" title="Keluar">
                        <i class="ri-logout-box-r-line"></i>
                    </button>
                </form>

            </div>
        </header>
        @endpersist

        <div class="app-body">
            <!-- SIDEBAR -->
            @persist('sidebar')
            <aside class="app-sidebar" id="app-sidebar">
                <div class="sidebar-user">

                    <div class="sidebar-user-info">
                        <div class="name">{{ Auth::user()->name }}</div>
                        <div class="role">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="sidebar-scroll">
                    <x-sidebar />
                </div>
            </aside>
            @endpersist

            <!-- MAIN CONTENT -->
            <main class="app-content">
                <!-- Tab Bar -->
                <div class="tab-bar">
                    <div class="tab-item active">
                        <i class="ri-dashboard-line" style="font-size:14px;"></i>
                        {{ $title ?? getPageTitle() }}
                    </div>
                </div>

                <!-- Content Area -->
                <div class="content-area">
                    <!-- Breadcrumb / Header -->
                    <div class="content-header">
                        <h4>{{ $title ?? getPageTitle() }}</h4>
                        <ol class="breadcrumb">
                            <li><a href="{{ route('admin.dashboard') }}"><i class="ri-home-4-line mr-1"></i>Home</a></li>
                            <li class="active">{{ $title ?? getPageTitle() }}</li>
                        </ol>
                    </div>

                    <!-- Main Slot -->
                    {{ $slot }}
                </div>

                <!-- Footer -->
                @persist('footer')
                <footer class="app-footer">
                    <span>© 2023–{{ date('Y') }} <a href="https://denisyarif1997.github.io/Portfolio/" target="_blank">Deni Sarifudin</a></span>
                    <span>Logistics Management System v2.0</span>
                </footer>
                @endpersist
            </main>
        </div>
    </div>

    <!-- SPA Navigation Overlay -->
    @persist('spa-navigation-overlay')
    <div id="spa-navigation-overlay" class="spa-navigation-overlay" role="status" aria-live="polite">
        Memuat halaman...
    </div>
    @endpersist

    <!-- Scripts -->
    @persist('scripts')
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpersist

    @livewireScripts

    <script data-navigate-once>
        // Toast Styles Configuration
        var toastStyles = {
            success: {
                bg: '#27AE60',
                icon: 'ri-checkbox-circle-line'
            },
            error: {
                bg: '#EB5757',
                icon: 'ri-close-circle-line'
            },
            warning: {
                bg: '#F2C94C',
                icon: 'ri-alert-line'
            },
            info: {
                bg: '#0F6CBD',
                icon: 'ri-information-line'
            }
        };

        function showNotification(message, type = 'success', title = null) {
            if (!message) return;

            var titleText = title || (
                type === 'success' ? 'Berhasil!' :
                type === 'error' ? 'Gagal!' :
                type === 'warning' ? 'Peringatan!' : 'Informasi'
            );
            var style = toastStyles[type] || toastStyles.info;

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: type,
                iconHtml: '<i class="' + style.icon + '" style="font-size:20px;color:#fff;"></i>',
                title: titleText,
                text: message,
                color: '#fff',
                background: style.bg
            });
        }

        // Livewire notifications
        document.addEventListener('livewire:init', function() {
            Livewire.on('notify', function(data) {
                var message = '';
                var type = 'success';
                var title = null;

                if (data === null || data === undefined) return;

                if (typeof data === 'string') {
                    message = data;
                } else if (Array.isArray(data)) {
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
                        message = data[0] || '';
                        type = data[1] || 'success';
                    }
                } else if (typeof data === 'object') {
                    message = data.message || data.text || '';
                    type = data.type || 'success';
                    title = data.title || null;
                }

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

        // Sidebar active state update
        function updateSidebarActiveState() {
            var currentPath = window.location.pathname.replace(/\/$/, '');
            var links = document.querySelectorAll('.app-sidebar .nav-link[href]:not([href="#"])');
            var currentLink = null;

            links.forEach(function(link) {
                var linkPath = new URL(link.href, window.location.origin).pathname.replace(/\/$/, '');
                if (linkPath === currentPath) {
                    currentLink = link;
                }
            });

            if (!currentLink) return;

            links.forEach(function(l) { l.classList.remove('active'); });
            currentLink.classList.add('active');

            var parent = currentLink.closest('.nav-treeview');
            if (parent) {
                var sibling = parent.previousElementSibling;
                if (sibling && sibling.classList.contains('nav-link')) {
                    sibling.classList.add('active');
                }
            }
        }

        // Theme toggle with server-side persistence
        document.addEventListener('click', function(e) {
            var themeBtn = e.target.closest('#theme-toggle-btn');
            if (themeBtn) {
                var currentTheme = document.documentElement.getAttribute('data-theme');
                var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                // 1. Instantly update client-side DOM for visual feedback
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('app-theme', newTheme);

                // 2. Save choice to database in background
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
                
                fetch('{{ route('admin.profile.toggle-mode') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Failed to save theme preference in database');
                    }
                })
                .catch(err => console.error('Error toggling theme:', err));
            }
        });

        // Sync local storage with database theme rendered by server
        var serverTheme = "{{ Auth::user()->mode ?? 'light' }}";
        localStorage.setItem('app-theme', serverTheme);
        document.documentElement.setAttribute('data-theme', serverTheme);

        // Sidebar toggle button click
        document.addEventListener('click', function(e) {
            var toggle = e.target.closest('#sidebar-toggle-btn');
            if (toggle) {
                e.preventDefault();
                var sidebar = document.getElementById('app-sidebar');
                if (sidebar) {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.toggle('open');
                    } else {
                        sidebar.classList.toggle('collapsed');
                    }
                }
            }
        });

        // Track if a sidebar menu link (not a toggle) was clicked
        let sidebarLinkClicked = false;
        document.addEventListener('click', function(e) {
            var navLink = e.target.closest('.app-sidebar .nav-link:not(.has-treeview)[href]:not([href="#"])');
            if (navLink) {
                sidebarLinkClicked = true;
            }
        });

        // Close/collapse sidebar ONLY after successful SPA navigation
        document.addEventListener('livewire:navigated', function() {
            if (sidebarLinkClicked) {
                sidebarLinkClicked = false;
                var sidebar = document.getElementById('app-sidebar');
                if (sidebar) {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('open');
                    } else {
                        sidebar.classList.add('collapsed');
                    }
                }
            }
        });

        // Submenu toggle (Accordion style)
        document.addEventListener('click', function(e) {
            var link = e.target.closest('.nav-link.has-treeview');
            if (link) {
                e.preventDefault();
                var treeview = link.nextElementSibling;
                if (treeview && treeview.classList.contains('nav-treeview')) {
                    var isOpen = treeview.classList.contains('open');
                    
                    // Close all other treeviews
                    document.querySelectorAll('.nav-treeview').forEach(function(otherTreeview) {
                        if (otherTreeview !== treeview) {
                            otherTreeview.classList.remove('open');
                            // Close chevrons on other triggers
                            var otherLink = otherTreeview.previousElementSibling;
                            if (otherLink && otherLink.classList.contains('nav-link')) {
                                var otherChevron = otherLink.querySelector('.chevron');
                                if (otherChevron) otherChevron.classList.remove('open');
                            }
                        }
                    });

                    // Toggle current treeview
                    if (isOpen) {
                        treeview.classList.remove('open');
                        var chevron = link.querySelector('.chevron');
                        if (chevron) chevron.classList.remove('open');
                    } else {
                        treeview.classList.add('open');
                        var chevron = link.querySelector('.chevron');
                        if (chevron) chevron.classList.add('open');
                    }
                }
            }
        });

        // Global Menu Search Functionality
        let menuIndex = [];

        function buildMenuIndex() {
            menuIndex = [];
            const sidebar = document.getElementById('app-sidebar');
            if (!sidebar) return;

            // Find all nav links that have actual destinations (not toggle headers/dropdowns)
            const links = sidebar.querySelectorAll('.nav-link[href]:not([href="#"])');
            
            links.forEach(link => {
                const href = link.getAttribute('href');
                const titleSpan = link.querySelector('span');
                const title = titleSpan ? titleSpan.textContent.trim() : '';
                if (!title || !href) return;

                const icon = link.querySelector('i')?.className || 'ri-link';
                
                // Determine Category / Breadcrumb
                let categoryParts = [];
                
                // 1. Check if it's inside a treeview submenu
                const treeview = link.closest('.nav-treeview');
                if (treeview) {
                    // Find the trigger link for this treeview
                    const parentLink = treeview.previousElementSibling;
                    if (parentLink && parentLink.classList.contains('nav-link')) {
                        const parentTitleSpan = parentLink.querySelector('span');
                        if (parentTitleSpan) {
                            categoryParts.unshift(parentTitleSpan.textContent.trim());
                        }
                    }
                }
                
                // 2. Find the nearest preceding nav-header
                let previousSibling = link.closest('.nav-item');
                if (treeview) {
                    previousSibling = treeview.closest('.nav-item');
                }
                
                while (previousSibling) {
                    previousSibling = previousSibling.previousElementSibling;
                    if (previousSibling && previousSibling.classList.contains('nav-header')) {
                        categoryParts.unshift(previousSibling.textContent.trim());
                        break;
                    }
                }
                
                const category = categoryParts.join(' > ') || 'Utama';

                menuIndex.push({
                    title: title,
                    href: href,
                    icon: icon,
                    category: category,
                    element: link // Keep a reference to trigger click if needed
                });
            });
        }

        // Initialize Menu Search
        function setupGlobalSearch() {
            const searchInput = document.getElementById('global-search-input');
            const resultsDropdown = document.getElementById('global-search-results');
            if (!searchInput || !resultsDropdown) return;

            let selectedIndex = -1;
            let currentResults = [];

            // Prevent form submit if wrapped in form
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });

            // Handle Input Changes
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase().trim();
                if (!query) {
                    resultsDropdown.style.display = 'none';
                    resultsDropdown.innerHTML = '';
                    currentResults = [];
                    selectedIndex = -1;
                    return;
                }

                // Filter items
                currentResults = menuIndex.filter(item => 
                    item.title.toLowerCase().includes(query) || 
                    item.category.toLowerCase().includes(query)
                );

                renderResults();
            });

            // Handle Keyboard Navigation
            searchInput.addEventListener('keydown', (e) => {
                // Keyboard shortcut: Ctrl+K focus search (always active)
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                    return;
                }

                if (currentResults.length === 0) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedIndex = (selectedIndex + 1) % currentResults.length;
                    highlightItem();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedIndex = (selectedIndex - 1 + currentResults.length) % currentResults.length;
                    highlightItem();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (selectedIndex >= 0 && selectedIndex < currentResults.length) {
                        navigate(currentResults[selectedIndex]);
                    } else if (currentResults.length > 0) {
                        navigate(currentResults[0]);
                    }
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    resultsDropdown.style.display = 'none';
                    searchInput.blur();
                }
            });

            // Handle Clicks Outside to Close
            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !resultsDropdown.contains(e.target)) {
                    resultsDropdown.style.display = 'none';
                }
            });

            // Show Dropdown on Focus if not empty
            searchInput.addEventListener('focus', () => {
                if (searchInput.value.trim()) {
                    resultsDropdown.style.display = 'block';
                }
            });

            function renderResults() {
                selectedIndex = -1;
                if (currentResults.length === 0) {
                    resultsDropdown.innerHTML = '<div class="search-no-results">Menu tidak ditemukan</div>';
                } else {
                    resultsDropdown.innerHTML = currentResults.map((item, index) => `
                        <div class="search-result-item" data-index="${index}">
                            <i class="${item.icon}"></i>
                            <div class="result-info">
                                <span class="result-title">${item.title}</span>
                                <span class="result-category">${item.category}</span>
                            </div>
                        </div>
                    `).join('');

                    // Add click listeners to items
                    resultsDropdown.querySelectorAll('.search-result-item').forEach(el => {
                        el.addEventListener('click', () => {
                            const index = parseInt(el.getAttribute('data-index'));
                            navigate(currentResults[index]);
                        });
                    });
                }
                resultsDropdown.style.display = 'block';
            }

            function highlightItem() {
                const items = resultsDropdown.querySelectorAll('.search-result-item');
                items.forEach((item, index) => {
                    if (index === selectedIndex) {
                        item.classList.add('selected');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('selected');
                    }
                });
            }

            function navigate(item) {
                resultsDropdown.style.display = 'none';
                searchInput.value = '';
                selectedIndex = -1;
                currentResults = [];

                if (item.element) {
                    item.element.click();
                } else {
                    window.location.href = item.href;
                }
            }
        }

        // Global shortcut Ctrl+K to focus search (when not already focused)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                const searchInput = document.getElementById('global-search-input');
                if (searchInput && document.activeElement !== searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                }
            }
        });

        // Initialize and bind search on page load & livewire navigation
        document.addEventListener('DOMContentLoaded', () => {
            buildMenuIndex();
            setupGlobalSearch();
        });
        
        document.addEventListener('livewire:navigated', () => {
            buildMenuIndex();
            setupGlobalSearch();
        });

        // SPA Navigation
        document.addEventListener('livewire:navigated', updateSidebarActiveState);

        document.addEventListener('livewire:navigating', function() {
            document.getElementById('spa-navigation-overlay')?.classList.add('is-visible');
        });

        document.addEventListener('livewire:navigated', function() {
            requestAnimationFrame(function() {
                document.getElementById('spa-navigation-overlay')?.classList.remove('is-visible');
            });
        });
    </script>

    @yield('js')
</body>
</html>