{{-- Dashboard Modern Complex --}}
<style>
    .dash-wrap { padding: 1.5rem 0; }

    /* Page header */
    .dash-page-title { font-size: 24px; font-weight: 700; color: #1a1a2e; margin: 0; }
    .dash-page-sub { font-size: 14px; color: #6b7280; margin-top: 4px; }

    /* Stat cards - Enhanced */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e5e5e5;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .stat-card:hover { 
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        text-decoration: none;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: radial-gradient(circle, rgba(83,74,183,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .stat-card .sc-icon { 
        font-size: 22px; 
        color: #9ca3af; 
        margin-bottom: 12px; 
        display: block;
        position: relative;
        z-index: 1;
    }
    .stat-card .sc-val { 
        font-size: 28px; 
        font-weight: 700; 
        line-height: 1; 
        display: block;
        position: relative;
        z-index: 1;
    }
    .stat-card .sc-lbl { 
        font-size: 13px; 
        color: #6b7280; 
        margin-top: 6px; 
        display: block;
        position: relative;
        z-index: 1;
    }
    .stat-card .sc-trend {
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 8px;
        position: relative;
        z-index: 1;
    }
    .stat-card .sc-trend.positive { color: #059669; }
    .stat-card .sc-trend.negative { color: #dc2626; }
    .stat-card .sc-trend.neutral { color: #6b7280; }

    /* Accent colors */
    .accent-purple .sc-val { color: #534AB7; }
    .accent-purple .sc-icon { color: #534AB7; }
    .accent-teal .sc-val { color: #0F6E56; }
    .accent-teal .sc-icon { color: #0F6E56; }
    .accent-blue .sc-val { color: #185FA5; }
    .accent-blue .sc-icon { color: #185FA5; }
    .accent-amber .sc-val { color: #854F0B; }
    .accent-amber .sc-icon { color: #854F0B; }
    .accent-coral .sc-val { color: #993C1D; }
    .accent-coral .sc-icon { color: #993C1D; }
    .accent-pink .sc-val { color: #993556; }
    .accent-pink .sc-icon { color: #993556; }
    .accent-gray .sc-val { color: #5F5E5A; }
    .accent-gray .sc-icon { color: #5F5E5A; }
    .accent-emerald .sc-val { color: #059669; }
    .accent-emerald .sc-icon { color: #059669; }

    /* Alert strip - Enhanced */
    .dash-alert {
        background: linear-gradient(135deg, #fef9ec 0%, #fff7ed 100%);
        border: 1px solid #fac775;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(250, 199, 117, 0.15);
    }
    .dash-alert i { font-size: 20px; color: #854F0B; }
    .dash-alert p { font-size: 14px; color: #633806; margin: 0; }
    .dash-alert strong { font-weight: 600; }

    /* Panels - Enhanced */
    .panel-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    .panel-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    .dash-panel {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .dash-panel-head {
        padding: 14px 20px;
        border-bottom: 1px solid #f0f0ee;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }
    .dash-panel-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dash-panel-head i { font-size: 16px; color: #6366f1; }
    .dash-panel-head span { font-size: 14px; font-weight: 600; color: #1f2937; }
    .dash-panel-head .view-all {
        font-size: 12px;
        color: #6366f1;
        text-decoration: none;
    }
    .dash-panel-head .view-all:hover { text-decoration: underline; }

    /* Table - Enhanced */
    .dash-tbl { width: 100%; border-collapse: collapse; font-size: 13px; }
    .dash-tbl th {
        padding: 10px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f0f0ee;
        background: #fafafa;
    }
    .dash-tbl td {
        padding: 12px 16px;
        color: #374151;
        border-bottom: 1px solid #f9f9f8;
        vertical-align: middle;
    }
    .dash-tbl tr:last-child td { border-bottom: none; }
    .dash-tbl tr:hover td { background: #fafafa; }
    .dash-tbl td strong { font-weight: 600; color: #1f2937; }
    .dash-tbl .empty-td { text-align: center; color: #9ca3af; padding: 30px !important; font-style: italic; }

    /* Badges - Enhanced */
    .dbadge {
        display: inline-flex;
        align-items: center;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 100px;
    }
    .dbadge-green  { background: #EAF3DE; color: #3B6D11; }
    .dbadge-amber  { background: #FAEEDA; color: #854F0B; }
    .dbadge-blue   { background: #E6F1FB; color: #185FA5; }
    .dbadge-red    { background: #FCEBEB; color: #A32D2D; }
    .dbadge-purple { background: #EDEDFD; color: #534AB7; }

    /* Warehouse grid - Enhanced */
    .wh-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        padding: 16px;
    }
    .wh-tile { 
        background: linear-gradient(135deg, #f9f9f8 0%, #f3f4f6 100%); 
        border-radius: 12px; 
        padding: 14px;
        text-align: center;
        transition: transform 0.2s;
    }
    .wh-tile:hover {
        transform: scale(1.03);
    }
    .wh-tile .wh-name { font-size: 12px; color: #6b7280; margin-bottom: 6px; }
    .wh-tile .wh-val  { font-size: 22px; font-weight: 700; color: #1f2937; line-height: 1; }
    .wh-tile .wh-unit { font-size: 11px; color: #9ca3af; margin-top: 4px; }

    /* Chart container */
    .chart-container {
        padding: 16px 20px;
        min-height: 200px;
    }
    .chart-wrapper {
        position: relative;
        height: 200px;
    }
    .chart-wrapper-sm {
        position: relative;
        height: 180px;
    }

    /* Summary cards */
    .summary-card {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 16px;
        padding: 24px;
        color: #fff;
        margin-bottom: 20px;
    }
    .summary-card.emerald { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
    .summary-card.amber { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
    .summary-card .summary-title { font-size: 14px; opacity: 0.9; margin: 0; }
    .summary-card .summary-value { font-size: 32px; font-weight: 700; margin: 8px 0 0; }
    .summary-card .summary-desc { font-size: 12px; opacity: 0.8; margin-top: 4px; }

    /* Progress bar */
    .progress-bar {
        height: 6px;
        background: rgba(255,255,255,0.2);
        border-radius: 3px;
        margin-top: 12px;
        overflow: hidden;
    }
    .progress-bar .progress-fill {
        height: 100%;
        background: #fff;
        border-radius: 3px;
        transition: width 0.5s ease;
    }

    /* Activity timeline */
    .activity-timeline {
        padding: 16px 20px;
        max-height: 300px;
        overflow-y: auto;
    }
    .activity-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0ee;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }
    .activity-icon.purchase { background: #EDEDFD; color: #534AB7; }
    .activity-icon.receiving { background: #E6F1FB; color: #185FA5; }
    .activity-icon.usage { background: #FCEBEB; color: #A32D2D; }
    .activity-icon.payment { background: #EAF3DE; color: #3B6D11; }
    .activity-icon.sale { background: #EAF3DE; color: #059669; }
    .activity-content { flex: 1; }
    .activity-content .act-title { font-size: 13px; font-weight: 500; color: #1f2937; margin: 0; }
    .activity-content .act-desc { font-size: 11px; color: #6b7280; margin: 2px 0 0; }
    .activity-content .act-time { font-size: 10px; color: #9ca3af; margin-top: 4px; }

    /* Chart grid for multiple charts */
    .chart-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    @media (max-width: 992px) {
        .chart-grid-3 { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 768px) {
        .panel-grid-2, .panel-grid-3, .chart-grid-3 { grid-template-columns: 1fr; }
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
        .dash-page-title { font-size: 20px; }
    }
    @media (max-width: 480px) {
        .stat-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dash-wrap">
    @role('superadmin')

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="dash-page-title">Dashboard</h1>
        <p class="dash-page-sub">Ringkasan aktivitas inventory — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Summary Cards --}}
    <div class="panel-grid-3">
        <div class="summary-card">
            <p class="summary-title">Total Barang</p>
            <div class="summary-value">{{ number_format($totalBarang) }}</div>
            <p class="summary-desc">Jenis barang tersedia</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min(100, $totalBarang / 10) }}%"></div>
            </div>
        </div>
        <div class="summary-card emerald">
            <p class="summary-title">Total Stok</p>
            <div class="summary-value">{{ number_format($totalStok) }}</div>
            <p class="summary-desc">Item dalam gudang</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min(100, $totalStok / 1000) }}%"></div>
            </div>
        </div>
        <div class="summary-card amber">
            <p class="summary-title">Total Omzet</p>
            <div class="summary-value">Rp {{ number_format($totalOmzet / 1000000, 1) }}jt</div>
            <p class="summary-desc">Total penjualan</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min(100, $totalOmzet / 100000000) }}%"></div>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <a href="{{ route('admin.barang.index') }}" class="stat-card accent-purple">
            <i class="fas fa-boxes sc-icon"></i>
            <span class="sc-val">{{ number_format($totalBarang) }}</span>
            <span class="sc-lbl">Jenis barang</span>
            <span class="sc-trend positive">
                <i class="fas fa-arrow-up"></i> +12% dari bulan lalu
            </span>
        </a>
        <a href="{{ route('admin.stok.index') }}" class="stat-card accent-teal">
            <i class="fas fa-warehouse sc-icon"></i>
            <span class="sc-val">{{ number_format($totalStok) }}</span>
            <span class="sc-lbl">Total stok</span>
            <span class="sc-trend positive">
                <i class="fas fa-arrow-up"></i> +8% dari bulan lalu
            </span>
        </a>
        <a href="{{ route('admin.pembelian.index') }}" class="stat-card accent-blue">
            <i class="fas fa-shopping-cart sc-icon"></i>
            <span class="sc-val">{{ number_format($pembelian) }}</span>
            <span class="sc-lbl">Purchase orders</span>
            <span class="sc-trend neutral">
                <i class="fas fa-minus"></i> Data statis
            </span>
        </a>
        <a href="{{ route('admin.penerimaan.index') }}" class="stat-card accent-amber">
            <i class="fas fa-truck-loading sc-icon"></i>
            <span class="sc-val">{{ number_format($penerimaan) }}</span>
            <span class="sc-lbl">Total penerimaan</span>
            <span class="sc-trend positive">
                <i class="fas fa-arrow-up"></i> +15% dari bulan lalu
            </span>
        </a>
        <a href="{{ route('admin.pemakaian.index') }}" class="stat-card accent-coral">
            <i class="fas fa-dolly sc-icon"></i>
            <span class="sc-val">{{ number_format($pemakaian) }}</span>
            <span class="sc-lbl">Total pemakaian</span>
            <span class="sc-trend negative">
                <i class="fas fa-arrow-down"></i> -5% dari bulan lalu
            </span>
        </a>
        <a href="{{ route('admin.pembayaran.index') }}" class="stat-card accent-pink">
    <i class="fas fa-money-bill-wave sc-icon"></i>
    <span class="sc-val">Rp {{ number_format($totalHutang / 1000000, 1) }}jt</span>
    <span class="sc-lbl">Total Hutang</span>

    @if($totalHutang > 1000000)
        <span class="sc-trend negative">
            <i class="fas fa-arrow-down"></i> Perlu ditindaklanjuti
        </span>
    @else
        <span class="sc-trend positive">
            <i class="fas fa-check-circle"></i> Kondisi aman
        </span>
    @endif
</a>
        <a href="{{ route('admin.pembayaran.index') }}" class="stat-card accent-teal">
            <i class="fas fa-credit-card sc-icon"></i>
            <span class="sc-val">Rp {{ number_format($totalPembayaran / 1000000, 1) }}jt</span>
            <span class="sc-lbl">Total pembayaran</span>
            <span class="sc-trend positive">
                <i class="fas fa-arrow-up"></i> +20% dari bulan lalu
            </span>
        </a>
        <a href="{{ route('admin.kasir.index') }}" class="stat-card accent-emerald">
            <i class="fas fa-shopping-bag sc-icon"></i>
            <span class="sc-val">{{ number_format($totalPenjualan) }}</span>
            <span class="sc-lbl">Total penjualan</span>
            <span class="sc-trend positive">
                <i class="fas fa-arrow-up"></i> +10% dari bulan lalu
            </span>
        </a>
        <div class="stat-card accent-gray">
            <i class="fas fa-users sc-icon"></i>
            <span class="sc-val">{{ number_format($user) }}</span>
            <span class="sc-lbl">Total users</span>
            <span class="sc-trend neutral">
                <i class="fas fa-user-check"></i> Aktif
            </span>
        </div>
    </div>

    {{-- Low Stock Alert --}}
    @if($lowStockItems && $lowStockItems->count() > 0)
    <div class="dash-alert">
        <i class="fas fa-exclamation-triangle"></i>
        <p>
            <strong>{{ $lowStockItems->count() }} barang</strong>
            memiliki stok di bawah batas minimum — segera lakukan pemesanan.
        </p>
    </div>
    @endif

    {{-- Chart Section - Line chart Aktivitas 7 Hari (Full width) --}}
    <div class="dash-panel mb-4">
        <div class="dash-panel-head">
            <div class="dash-panel-head-left">
                <i class="fas fa-chart-line"></i>
                <span>Aktivitas 7 Hari Terakhir</span>
            </div>
        </div>
        <div class="chart-container">
            <div class="chart-wrapper">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    {{-- 3 Chart Grid: Doughnut, Bar Comparison, Horizontal Bar --}}
    <div class="chart-grid-3">
        {{-- Doughnut Chart - Distribusi Kategori --}}
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-chart-pie"></i>
                    <span>Distribusi Stok per Gudang</span>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-wrapper-sm">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Bar Chart - Perbandingan Bulanan --}}
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-chart-bar"></i>
                    <span>Perbandingan Bulanan (6 Bulan)</span>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-wrapper-sm">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Horizontal Bar - Top Stok Barang --}}
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-trophy"></i>
                    <span>Top 10 Stok Terbanyak</span>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-wrapper-sm">
                    <canvas id="topStokChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- PO Terbaru & Penerimaan Terbaru --}}
    <div class="panel-grid-2">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase order terbaru</span>
                </div>
                <a href="{{ route('admin.pembelian.index') }}" class="view-all">Lihat semua</a>
            </div>
            <table class="dash-tbl">
                <thead>
                    <tr>
                        <th>No PO</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPO as $po)
                    <tr>
                        <td><strong>{{ $po->no_po }}</strong></td>
                        <td>{{ $po->supplier->nama_supplier ?? '-' }}</td>
                        <td>{{ $po->tanggal_po ? \Carbon\Carbon::parse($po->tanggal_po)->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($po->status == 'approved')
                                <span class="dbadge dbadge-green">Approved</span>
                            @elseif($po->status == 'pending')
                                <span class="dbadge dbadge-amber">Pending</span>
                            @else
                                <span class="dbadge dbadge-blue">{{ ucfirst($po->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="empty-td">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-truck-loading"></i>
                    <span>Penerimaan terbaru</span>
                </div>
                <a href="{{ route('admin.penerimaan.index') }}" class="view-all">Lihat semua</a>
            </div>
            <table class="dash-tbl">
                <thead>
                    <tr>
                        <th>No Terima</th>
                        <th>Supplier</th>
                        <th>Gudang</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPenerimaan as $terima)
                    <tr>
                        <td><strong>{{ $terima->no_penerimaan }}</strong></td>
                        <td>{{ $terima->pembelian->supplier->nama_supplier ?? '-' }}</td>
                        <td>{{ $terima->gudang->nama_gudang ?? '-' }}</td>
                        <td>{{ $terima->tanggal_terima ? \Carbon\Carbon::parse($terima->tanggal_terima)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="empty-td">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pemakaian Terbaru & Stok Menipis --}}
    <div class="panel-grid-2">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-dolly"></i>
                    <span>Pemakaian terbaru</span>
                </div>
                <a href="{{ route('admin.pemakaian.index') }}" class="view-all">Lihat semua</a>
            </div>
            <table class="dash-tbl">
                <thead>
                    <tr>
                        <th>No Pakai</th>
                        <th>Departemen</th>
                        <th>Gudang</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPemakaian as $pakai)
                    <tr>
                        <td><strong>{{ $pakai->no_pemakaian }}</strong></td>
                        <td>{{ $pakai->departemen->nama_departemen ?? '-' }}</td>
                        <td>{{ $pakai->gudang->nama_gudang ?? '-' }}</td>
                        <td>{{ $pakai->tanggal_pakai ? \Carbon\Carbon::parse($pakai->tanggal_pakai)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="empty-td">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Stok menipis</span>
                </div>
                <a href="{{ route('admin.barang.index') }}" class="view-all">Kelola stok</a>
            </div>
            <table class="dash-tbl">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama barang</th>
                        <th>Stok</th>
                        <th>Min</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockItems as $item)
                    <tr>
                        <td><strong>{{ $item->kode_barang }}</strong></td>
                        <td>{{ $item->nama_barang }}</td>
                        <td><span class="dbadge dbadge-red">{{ $item->total_stok }}</span></td>
                        <td>{{ $item->stok_minimum }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="empty-td">
                            <i class="fas fa-check-circle mr-1" style="color:#059669"></i> Semua stok aman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Penjualan Terbaru --}}
    <div class="dash-panel mb-4">
        <div class="dash-panel-head">
            <div class="dash-panel-head-left">
                <i class="fas fa-shopping-bag"></i>
                <span>Penjualan terbaru</span>
            </div>
            <a href="{{ route('admin.kasir.index') }}" class="view-all">Lihat semua</a>
        </div>
        <table class="dash-tbl">
            <thead>
                <tr>
                    <th>No Penjualan</th>
                    <th>Gudang</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPenjualan as $jual)
                <tr>
                    <td><strong>{{ $jual->no_penjualan }}</strong></td>
                    <td>{{ $jual->gudang->nama_gudang ?? '-' }}</td>
                    <td>{{ $jual->tanggal ? \Carbon\Carbon::parse($jual->tanggal)->format('d/m/Y') : '-' }}</td>
                    <td>Rp {{ number_format($jual->total / 1000000, 1) }}jt</td>
                    <td>
                        @if($jual->status == 'selesai')
                            <span class="dbadge dbadge-green">Selesai</span>
                        @elseif($jual->status == 'pending')
                            <span class="dbadge dbadge-amber">Pending</span>
                        @else
                            <span class="dbadge dbadge-blue">{{ ucfirst($jual->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="empty-td">Belum ada data penjualan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Stok Per Gudang & Aktivitas Terbaru --}}
    <div class="panel-grid-2">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-warehouse"></i>
                    <span>Stok per gudang</span>
                </div>
            </div>
            <div class="wh-grid">
                @forelse($stokPerGudang as $gudang)
                <div class="wh-tile">
                    <div class="wh-name">{{ $gudang->nama_gudang }}</div>
                    <div class="wh-val">{{ number_format($gudang->stok_sum_stok_akhir ?? 0) }}</div>
                    <div class="wh-unit">Item</div>
                </div>
                @empty
                <p style="font-size:13px; color:#9ca3af; padding:16px; margin:0;">Belum ada data gudang.</p>
                @endforelse
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-clock-rotate"></i>
                    <span>Aktivitas Terbaru</span>
                </div>
            </div>
            <div class="activity-timeline">
                @php
                    $activities = collect();
                    foreach($recentPO as $po) {
                        $activities->push([
                            'icon' => 'purchase',
                            'iconFa' => 'fa-shopping-cart',
                            'title' => 'PO Baru: ' . ($po->no_po ?? ''),
                            'desc' => 'Supplier: ' . ($po->supplier->nama_supplier ?? '-'),
                            'time' => $po->tanggal_po ? \Carbon\Carbon::parse($po->tanggal_po)->diffForHumans() : '-',
                        ]);
                    }
                    foreach($recentPenerimaan as $trm) {
                        $activities->push([
                            'icon' => 'receiving',
                            'iconFa' => 'fa-truck-loading',
                            'title' => 'Penerimaan: ' . ($trm->no_penerimaan ?? ''),
                            'desc' => 'Dari: ' . ($trm->pembelian->supplier->nama_supplier ?? '-'),
                            'time' => $trm->tanggal_terima ? \Carbon\Carbon::parse($trm->tanggal_terima)->diffForHumans() : '-',
                        ]);
                    }
                    foreach($recentPemakaian as $pk) {
                        $activities->push([
                            'icon' => 'usage',
                            'iconFa' => 'fa-dolly',
                            'title' => 'Pemakaian: ' . ($pk->no_pemakaian ?? ''),
                            'desc' => 'Dept: ' . ($pk->departemen->nama_departemen ?? '-'),
                            'time' => $pk->tanggal_pakai ? \Carbon\Carbon::parse($pk->tanggal_pakai)->diffForHumans() : '-',
                        ]);
                    }
                    foreach($recentPenjualan as $jual) {
                        $activities->push([
                            'icon' => 'sale',
                            'iconFa' => 'fa-shopping-bag',
                            'title' => 'Penjualan: ' . ($jual->no_penjualan ?? ''),
                            'desc' => 'Total: Rp ' . number_format(($jual->total ?? 0) / 1000000, 1) . 'jt',
                            'time' => $jual->tanggal ? \Carbon\Carbon::parse($jual->tanggal)->diffForHumans() : '-',
                        ]);
                    }
                    $activities = $activities->sortByDesc('time')->take(10);
                @endphp

                @forelse($activities as $activity)
                <div class="activity-item">
                    <div class="activity-icon {{ $activity['icon'] }}">
                        <i class="fas {{ $activity['iconFa'] }}"></i>
                    </div>
                    <div class="activity-content">
                        <p class="act-title">{{ $activity['title'] }}</p>
                        <p class="act-desc">{{ $activity['desc'] }}</p>
                        <p class="act-time">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @empty
                <p style="text-align:center; color:#9ca3af; padding:20px; margin:0;">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Chart Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==================== CHART 1: Line Chart - Aktivitas 7 Hari ====================
            const ctx1 = document.getElementById('activityChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: {!! json_encode($weeklyActivity['labels'] ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
                    datasets: [{
                        label: 'Penerimaan',
                        data: {!! json_encode($weeklyActivity['penerimaan'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                        borderColor: '#185FA5',
                        backgroundColor: 'rgba(24, 95, 165, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }, {
                        label: 'Pemakaian',
                        data: {!! json_encode($weeklyActivity['pemakaian'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                        borderColor: '#A32D2D',
                        backgroundColor: 'rgba(163, 45, 45, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }, {
                        label: 'Penjualan',
                        data: {!! json_encode($weeklyActivity['penjualan'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 11 },
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 15
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { size: 12 },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.03)' },
                            ticks: { 
                                font: { size: 10 },
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            // ==================== CHART 2: Doughnut Chart - Distribusi Kategori ====================
            const ctx2 = document.getElementById('kategoriChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($kategoriDistribusi['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($kategoriDistribusi['data'] ?? []) !!},
                        backgroundColor: {!! json_encode($kategoriDistribusi['colors'] ?? []) !!},
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: { size: 10 },
                                boxWidth: 12,
                                padding: 8,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { size: 11 },
                            bodyFont: { size: 11 },
                            padding: 8,
                            cornerRadius: 6,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed;
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + value + ' item (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '55%'
                }
            });

            // ==================== CHART 3: Bar Chart - Perbandingan Bulanan ====================
            const ctx3 = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyComparison['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Pembelian',
                        data: {!! json_encode($monthlyComparison['pembelian'] ?? []) !!},
                        backgroundColor: '#185FA5',
                        borderRadius: 4,
                        borderSkipped: false
                    }, {
                        label: 'Penerimaan',
                        data: {!! json_encode($monthlyComparison['penerimaan'] ?? []) !!},
                        backgroundColor: '#0F6E56',
                        borderRadius: 4,
                        borderSkipped: false
                    }, {
                        label: 'Pemakaian',
                        data: {!! json_encode($monthlyComparison['pemakaian'] ?? []) !!},
                        backgroundColor: '#A32D2D',
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 10 },
                                usePointStyle: true,
                                pointStyle: 'rectRounded',
                                padding: 10
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            bodyFont: { size: 11 },
                            padding: 8,
                            cornerRadius: 6
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.03)' },
                            ticks: { 
                                font: { size: 9 },
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 9 } }
                        }
                    }
                }
            });

            // ==================== CHART 4: Horizontal Bar - Top 10 Stok ====================
            const topStokLabels = {!! json_encode($topStokBarang['labels'] ?? []) !!};
            const topStokData = {!! json_encode($topStokBarang['data'] ?? []) !!};

            // Generate gradient colors based on value
            const gradientColors = topStokData.map((val, i) => {
                const opacity = 0.4 + (i / topStokData.length) * 0.6;
                return `rgba(99, 102, 241, ${opacity})`;
            });

            const ctx4 = document.getElementById('topStokChart').getContext('2d');
            new Chart(ctx4, {
                type: 'bar',
                data: {
                    labels: topStokLabels,
                    datasets: [{
                        label: 'Total Stok',
                        data: topStokData,
                        backgroundColor: gradientColors,
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            bodyFont: { size: 11 },
                            padding: 8,
                            cornerRadius: 6,
                            callbacks: {
                                label: function(context) {
                                    return 'Stok: ' + context.parsed.x.toLocaleString() + ' item';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.03)' },
                            ticks: { font: { size: 9 } }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { size: 9 } }
                        }
                    }
                }
            });
        });
    </script>

    @endrole
</div>