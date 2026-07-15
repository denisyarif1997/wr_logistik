{{-- Dashboard Modern --}}
<style>
    .dash-wrap { padding: 1.5rem 0; }

    /* Page header */
    .dash-page-title { font-size: 20px; font-weight: 600; color: #1a1a2e; margin: 0; }
    .dash-page-sub { font-size: 13px; color: #6b7280; margin-top: 3px; }

    /* Stat cards */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
        gap: 12px;
        margin-bottom: 1.75rem;
    }
    .stat-card {
        background: #f9f9f8;
        border-radius: 10px;
        padding: 14px 16px;
        border: none;
        text-decoration: none;
        display: block;
        transition: background 0.15s;
    }
    .stat-card:hover { background: #f0f0ee; text-decoration: none; }
    .stat-card .sc-icon { font-size: 17px; color: #9ca3af; margin-bottom: 10px; display: block; }
    .stat-card .sc-val { font-size: 20px; font-weight: 600; line-height: 1; display: block; }
    .stat-card .sc-lbl { font-size: 12px; color: #6b7280; margin-top: 5px; display: block; }
    .stat-card .sc-link { font-size: 11px; color: #9ca3af; margin-top: 10px; display: inline-flex; align-items: center; gap: 3px; }

    /* Accent colors — hanya pada angka */
    .accent-purple .sc-val { color: #534AB7; }
    .accent-teal   .sc-val { color: #0F6E56; }
    .accent-blue   .sc-val { color: #185FA5; }
    .accent-amber  .sc-val { color: #854F0B; }
    .accent-coral  .sc-val { color: #993C1D; }
    .accent-pink   .sc-val { color: #993556; }
    .accent-gray   .sc-val { color: #5F5E5A; }

    /* Alert strip */
    .dash-alert {
        background: #fef9ec;
        border: 1px solid #fac775;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dash-alert i { font-size: 16px; color: #854F0B; }
    .dash-alert p { font-size: 13px; color: #633806; margin: 0; }
    .dash-alert strong { font-weight: 600; }

    /* Panels */
    .panel-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 14px;
    }
    .dash-panel {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        overflow: hidden;
    }
    .dash-panel-head {
        padding: 10px 16px;
        border-bottom: 1px solid #f0f0ee;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dash-panel-head i { font-size: 15px; color: #9ca3af; }
    .dash-panel-head span { font-size: 13px; font-weight: 600; color: #374151; }

    /* Table */
    .dash-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
    .dash-tbl th {
        padding: 7px 14px;
        text-align: left;
        font-size: 10px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f0f0ee;
        background: #fafafa;
    }
    .dash-tbl td {
        padding: 8px 14px;
        color: #374151;
        border-bottom: 1px solid #f9f9f8;
        vertical-align: middle;
    }
    .dash-tbl tr:last-child td { border-bottom: none; }
    .dash-tbl tr:hover td { background: #fafafa; }
    .dash-tbl td strong { font-weight: 600; color: #1f2937; }
    .dash-tbl .empty-td { text-align: center; color: #9ca3af; padding: 20px !important; font-style: italic; }

    /* Badges */
    .dbadge {
        display: inline-flex;
        align-items: center;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 100px;
    }
    .dbadge-green  { background: #EAF3DE; color: #3B6D11; }
    .dbadge-amber  { background: #FAEEDA; color: #854F0B; }
    .dbadge-blue   { background: #E6F1FB; color: #185FA5; }
    .dbadge-red    { background: #FCEBEB; color: #A32D2D; }

    /* Warehouse grid */
    .wh-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        padding: 14px;
    }
    .wh-tile { background: #f9f9f8; border-radius: 8px; padding: 10px 12px; }
    .wh-tile .wh-name { font-size: 11px; color: #9ca3af; margin-bottom: 4px; }
    .wh-tile .wh-val  { font-size: 18px; font-weight: 600; color: #1f2937; line-height: 1; }
    .wh-tile .wh-unit { font-size: 11px; color: #9ca3af; margin-top: 2px; }

    @media (max-width: 640px) {
        .panel-grid-2 { grid-template-columns: 1fr; }
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="dash-wrap">
    @role('admin')

    {{-- Page Header --}}
    <div class="mb-4">
        {{-- <h1 class="dash-page-title">Dashboard</h1> --}}
        <p class="dash-page-sub">Ringkasan aktivitas inventory — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <a href="{{ route('admin.barang.index') }}" class="stat-card accent-purple">
            <i class="fas fa-boxes sc-icon"></i>
            <span class="sc-val">{{ number_format($totalBarang) }}</span>
            <span class="sc-lbl">Jenis barang</span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.stok.index') }}" class="stat-card accent-teal">
            <i class="fas fa-warehouse sc-icon"></i>
            <span class="sc-val">{{ number_format($totalStok) }}</span>
            <span class="sc-lbl">Total stok</span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.pembelian.index') }}" class="stat-card accent-blue">
            <i class="fas fa-shopping-cart sc-icon"></i>
            <span class="sc-val">{{ number_format($pembelian) }}</span>
            <span class="sc-lbl">Purchase orders</span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.penerimaan.index') }}" class="stat-card accent-amber">
            <i class="fas fa-truck-loading sc-icon"></i>
            <span class="sc-val">{{ number_format($penerimaan) }}</span>
            <span class="sc-lbl">Total penerimaan</span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.pemakaian.index') }}" class="stat-card accent-coral">
            <i class="fas fa-dolly sc-icon"></i>
            <span class="sc-val">{{ number_format($pemakaian) }}</span>
            <span class="sc-lbl">Total pemakaian</span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.pembayaran.index') }}" class="stat-card accent-pink">
            <i class="fas fa-money-bill-wave sc-icon"></i>
            <span class="sc-val">Rp {{ number_format($totalHutang / 1000000, 1) }}jt</span>
            <span class="sc-lbl">Total hutang</span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.pembayaran.index') }}" class="stat-card accent-teal">
            <i class="fas fa-credit-card sc-icon"></i>
            <span class="sc-val">Rp {{ number_format($totalPembayaran / 1000000, 1) }}jt</span>
            <span class="sc-lbl">Total pembayaran</span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
        <a href="{{ route('admin.user.index') }}" class="stat-card accent-gray">
            <i class="fas fa-users sc-icon"></i>
            <span class="sc-val">{{ number_format($user) }}</span>
            <span class="sc-lbl">Total users</span>
            <span class="sc-link">Lihat detail <i class="fas fa-arrow-right" style="font-size:9px"></i></span>
        </a>
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

    {{-- PO Terbaru & Penerimaan Terbaru --}}
    <div class="panel-grid-2">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchase order terbaru</span>
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
                <i class="fas fa-truck-loading"></i>
                <span>Penerimaan terbaru</span>
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
                <i class="fas fa-dolly"></i>
                <span>Pemakaian terbaru</span>
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
                <i class="fas fa-exclamation-circle"></i>
                <span>Stok menipis</span>
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
                            <i class="fas fa-check-circle mr-1" style="color:#3B6D11"></i> Semua stok aman
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
            <i class="fas fa-warehouse"></i>
            <span>Stok per gudang</span>
        </div>
        <div class="wh-grid">
            @forelse($stokPerGudang as $gudang)
            <div class="wh-tile">
                <div class="wh-name">{{ $gudang->nama_gudang }}</div>
                <div class="wh-val">{{ number_format($gudang->stok_sum_stok_akhir ?? 0) }}</div>
                <div class="wh-unit">Item</div>
            </div>
            @empty
            <p style="font-size:12px; color:#9ca3af; padding:14px; margin:0;">Belum ada data gudang.</p>
            @endforelse
        </div>
    </div>

    @endrole
</div>