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
    .stat-card .sc-link { 
        font-size: 12px; 
        color: #6366f1; 
        margin-top: 12px; 
        display: inline-flex; 
        align-items: center; 
        gap: 4px;
        position: relative;
        z-index: 1;
    }

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
        height: 180px;
    }

    /* Quick actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        padding: 16px 20px;
    }
    .quick-action-btn {
        background: #f9fafb;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }
    .quick-action-btn:hover {
        background: #6366f1;
        color: #fff;
        transform: translateY(-2px);
    }
    .quick-action-btn:hover i { color: #fff; }
    .quick-action-btn i { font-size: 18px; color: #6366f1; }
    .quick-action-btn span { font-size: 11px; font-weight: 500; color: #374151; }
    .quick-action-btn:hover span { color: #fff; }

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
    .activity-content { flex: 1; }
    .activity-content .act-title { font-size: 13px; font-weight: 500; color: #1f2937; margin: 0; }
    .activity-content .act-desc { font-size: 11px; color: #6b7280; margin: 2px 0 0; }
    .activity-content .act-time { font-size: 10px; color: #9ca3af; margin-top: 4px; }

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

    @media (max-width: 768px) {
        .panel-grid-2, .panel-grid-3 { grid-template-columns: 1fr; }
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
            <p class="summary-title">Total Hutang</p>
            <div class="summary-value">Rp {{ number_format($totalHutang / 1000000, 1) }}jt</div>
            <p class="summary-desc">Belum lunas</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min(100, $totalHutang / 100000000) }}%"></div>
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
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.stok.index') }}" class="stat-card accent-teal">
            <i class="fas fa-warehouse sc-icon"></i>
            <span class="sc-val">{{ number_format($totalStok) }}</span>
            <span class="sc-lbl">Total stok</span>
            <span class="sc-trend positive">
                <i class="fas fa-arrow-up"></i> +8% dari bulan lalu
            </span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.pembelian.index') }}" class="stat-card accent-blue">
            <i class="fas fa-shopping-cart sc-icon"></i>
            <span class="sc-val">{{ number_format($pembelian) }}</span>
            <span class="sc-lbl">Purchase orders</span>
            <span class="sc-trend neutral">
                <i class="fas fa-minus"></i> Data statis
            </span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.penerimaan.index') }}" class="stat-card accent-amber">
            <i class="fas fa-truck-loading sc-icon"></i>
            <span class="sc-val">{{ number_format($penerimaan) }}</span>
            <span class="sc-lbl">Total penerimaan</span>
            <span class="sc-trend positive">
                <i class="fas fa-arrow-up"></i> +15% dari bulan lalu
            </span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.pemakaian.index') }}" class="stat-card accent-coral">
            <i class="fas fa-dolly sc-icon"></i>
            <span class="sc-val">{{ number_format($pemakaian) }}</span>
            <span class="sc-lbl">Total pemakaian</span>
            <span class="sc-trend negative">
                <i class="fas fa-arrow-down"></i> -5% dari bulan lalu
            </span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.pembayaran.index') }}" class="stat-card accent-pink">
            <i class="fas fa-money-bill-wave sc-icon"></i>
            <span class="sc-val">Rp {{ number_format($totalHutang / 1000000, 1) }}jt</span>
            <span class="sc-lbl">Total hutang</span>
            <span class="sc-trend negative">
                <i class="fas fa-arrow-down"></i> Perlu ditindaklanjuti
            </span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.pembayaran.index') }}" class="stat-card accent-teal">
            <i class="fas fa-credit-card sc-icon"></i>
            <span class="sc-val">Rp {{ number_format($totalPembayaran / 1000000, 1) }}jt</span>
            <span class="sc-lbl">Total pembayaran</span>
            <span class="sc-trend positive">
                <i class="fas fa-arrow-up"></i> +20% dari bulan lalu
            </span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
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

    {{-- Chart & Quick Actions --}}
    <div class="panel-grid-2">
        {{-- Chart Panel --}}
        <div class="dash-panel">
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

        {{-- Quick Actions --}}
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-head-left">
                    <i class="fas fa-bolt"></i>
                    <span>Aksi Cepat</span>
                </div>
            </div>
            <div class="quick-actions">
                <a href="{{ route('admin.barang.index') }}" class="quick-action-btn">
                    <i class="fas fa-box"></i>
                    <span>Data Barang</span>
                </a>
                <a href="{{ route('admin.pembelian.index') }}" class="quick-action-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase Order</span>
                </a>
                <a href="{{ route('admin.penerimaan.index') }}" class="quick-action-btn">
                    <i class="fas fa-truck-loading"></i>
                    <span>Penerimaan</span>
                </a>
                <a href="{{ route('admin.pemakaian.index') }}" class="quick-action-btn">
                    <i class="fas fa-dolly"></i>
                    <span>Pemakaian</span>
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" class="quick-action-btn">
                    <i class="fas fa-money-bill"></i>
                    <span>Pembayaran</span>
                </a>
                <a href="{{ route('admin.stok.index') }}" class="quick-action-btn">
                    <i class="fas fa-warehouse"></i>
                    <span>Stok Gudang</span>
                </a>
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

    {{-- Stok Per Gudang --}}
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

    {{-- Chart Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('activityChart').getContext('2d');
            const activityChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($weeklyActivity['labels'] ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
                    datasets: [{
                        label: 'Penerimaan',
                        data: {!! json_encode($weeklyActivity['penerimaan'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                        borderColor: '#185FA5',
                        backgroundColor: 'rgba(24, 95, 165, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Pemakaian',
                        data: {!! json_encode($weeklyActivity['pemakaian'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                        borderColor: '#A32D2D',
                        backgroundColor: 'rgba(163, 45, 45, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 11 },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.02)' },
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        });
    </script>

    @endrole
</div>