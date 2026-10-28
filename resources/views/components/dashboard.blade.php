{{-- Dashboard Enterprise --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dash-wrap">
    @role('superadmin')

    {{-- Page Header --}}
    <div class="content-header" style="margin-bottom: 24px;">
        <h4 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px; letter-spacing: -0.02em;">
            Dashboard
        </h4>
        <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
            Ringkasan aktivitas inventory — {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    {{-- KPI Cards --}}
    <div class="stats-grid">
        <div class="kpi-card">
            <div class="kpi-icon blue"><i class="ri-box-3-line"></i></div>
            <div class="kpi-value">{{ number_format($totalBarang) }}</div>
            <div class="kpi-label">Jenis Barang</div>
            <div class="kpi-change up"><i class="ri-arrow-up-line"></i> +12% dari bulan lalu</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green"><i class="ri-warehouse-line"></i></div>
            <div class="kpi-value">{{ number_format($totalStok) }}</div>
            <div class="kpi-label">Total Stok</div>
            <div class="kpi-change up"><i class="ri-arrow-up-line"></i> +8% dari bulan lalu</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon blue"><i class="ri-file-list-3-line"></i></div>
            <div class="kpi-value">{{ number_format($pembelian) }}</div>
            <div class="kpi-label">Purchase Orders</div>
            <div class="kpi-change" style="color: var(--text-muted);"><i class="ri-subtract-line"></i> Data statis</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon yellow"><i class="ri-inbox-archive-line"></i></div>
            <div class="kpi-value">{{ number_format($penerimaan) }}</div>
            <div class="kpi-label">Total Penerimaan</div>
            <div class="kpi-change up"><i class="ri-arrow-up-line"></i> +15% dari bulan lalu</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon red"><i class="ri-open-arm-line"></i></div>
            <div class="kpi-value">{{ number_format($pemakaian) }}</div>
            <div class="kpi-label">Total Pemakaian</div>
            <div class="kpi-change down"><i class="ri-arrow-down-line"></i> -5% dari bulan lalu</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon red"><i class="ri-bank-card-line"></i></div>
            <div class="kpi-value">Rp {{ number_format($totalHutang / 1000000, 1) }}jt</div>
            <div class="kpi-label">Total Hutang</div>
            @if($totalHutang > 1000000)
                <div class="kpi-change down"><i class="ri-alert-line"></i> Perlu ditindaklanjuti</div>
            @else
                <div class="kpi-change up"><i class="ri-checkbox-circle-line"></i> Kondisi aman</div>
            @endif
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green"><i class="ri-bank-card-2-line"></i></div>
            <div class="kpi-value">Rp {{ number_format($totalPembayaran / 1000000, 1) }}jt</div>
            <div class="kpi-label">Total Pembayaran</div>
            <div class="kpi-change up"><i class="ri-arrow-up-line"></i> +20% dari bulan lalu</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green"><i class="ri-shopping-bag-line"></i></div>
            <div class="kpi-value">{{ number_format($totalPenjualan) }}</div>
            <div class="kpi-label">Total Penjualan</div>
            <div class="kpi-change up"><i class="ri-arrow-up-line"></i> +10% dari bulan lalu</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon blue"><i class="ri-group-line"></i></div>
            <div class="kpi-value">{{ number_format($user) }}</div>
            <div class="kpi-label">Total Users</div>
            <div class="kpi-change" style="color: var(--text-muted);"><i class="ri-user-check-line"></i> Aktif</div>
        </div>
    </div>

    {{-- Low Stock Alert --}}
    @if($lowStockItems && $lowStockItems->count() > 0)
    <div class="alert alert-warning" style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="ri-alert-line" style="font-size: 20px;"></i>
        <span>
            <strong>{{ $lowStockItems->count() }} barang</strong>
            memiliki stok di bawah batas minimum — segera lakukan pemesanan.
        </span>
    </div>
    @endif

    {{-- Chart: Aktivitas 7 Hari --}}
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="ri-line-chart-line" style="margin-right: 8px; color: var(--primary);"></i>Aktivitas 7 Hari Terakhir</h3>
        </div>
        <div class="card-body">
            <div style="position: relative; height: 220px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    {{-- 3 Chart Grid --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px;">
        {{-- Doughnut Chart --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ri-pie-chart-line" style="margin-right: 8px; color: var(--primary);"></i>Distribusi Stok per Gudang</h3>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 200px;">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Bar Chart Bulanan --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ri-bar-chart-line" style="margin-right: 8px; color: var(--primary);"></i>Perbandingan Bulanan (6 Bulan)</h3>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 200px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Stok --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ri-trophy-line" style="margin-right: 8px; color: var(--primary);"></i>Top 10 Stok Terbanyak</h3>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 200px;">
                    <canvas id="topStokChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- PO Terbaru & Penerimaan Terbaru --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ri-file-list-3-line" style="margin-right: 8px; color: var(--primary);"></i>Purchase Order Terbaru</h3>
                <div class="card-actions">
                    <a href="{{ route('admin.pembelian.index') }}" class="btn btn-sm btn-secondary">Lihat semua</a>
                </div>
            </div>
            <div class="table-container" style="border: none; border-radius: 0;">
                <table class="table">
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
                                    <span class="badge badge-success">Approved</span>
                                @elseif($po->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-primary">{{ ucfirst($po->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ri-inbox-archive-line" style="margin-right: 8px; color: var(--primary);"></i>Penerimaan Terbaru</h3>
                <div class="card-actions">
                    <a href="{{ route('admin.penerimaan.index') }}" class="btn btn-sm btn-secondary">Lihat semua</a>
                </div>
            </div>
            <div class="table-container" style="border: none; border-radius: 0;">
                <table class="table">
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
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pemakaian Terbaru & Stok Menipis --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ri-open-arm-line" style="margin-right: 8px; color: var(--primary);"></i>Pemakaian Terbaru</h3>
                <div class="card-actions">
                    <a href="{{ route('admin.pemakaian.index') }}" class="btn btn-sm btn-secondary">Lihat semua</a>
                </div>
            </div>
            <div class="table-container" style="border: none; border-radius: 0;">
                <table class="table">
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
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ri-alert-line" style="margin-right: 8px; color: var(--danger);"></i>Stok Menipis</h3>
                <div class="card-actions">
                    <a href="{{ route('admin.barang.index') }}" class="btn btn-sm btn-secondary">Kelola stok</a>
                </div>
            </div>
            <div class="table-container" style="border: none; border-radius: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Min</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockItems as $item)
                        <tr>
                            <td><strong>{{ $item->kode_barang }}</strong></td>
                            <td>{{ $item->nama_barang }}</td>
                            <td><span class="badge badge-danger">{{ $item->total_stok }}</span></td>
                            <td>{{ $item->stok_minimum }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                <i class="ri-checkbox-circle-line" style="color: var(--success); margin-right: 6px;"></i> Semua stok aman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Penjualan Terbaru --}}
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="ri-shopping-bag-line" style="margin-right: 8px; color: var(--primary);"></i>Penjualan Terbaru</h3>
            <div class="card-actions">
                <a href="{{ route('admin.kasir.index') }}" class="btn btn-sm btn-secondary">Lihat semua</a>
            </div>
        </div>
        <div class="table-container" style="border: none; border-radius: 0;">
            <table class="table">
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
                                <span class="badge badge-success">Selesai</span>
                            @elseif($jual->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-primary">{{ ucfirst($jual->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada data penjualan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Stok Per Gudang & Aktivitas Terbaru --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ri-warehouse-line" style="margin-right: 8px; color: var(--primary);"></i>Stok per Gudang</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px;">
                    @forelse($stokPerGudang as $gudang)
                    <div style="background: var(--bg-body); border-radius: var(--radius-md); padding: 16px; text-align: center; border: 1px solid var(--border-color);">
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 6px;">{{ $gudang->nama_gudang }}</div>
                        <div style="font-size: 22px; font-weight: 700; color: var(--text-primary); line-height: 1;">{{ number_format($gudang->stok_sum_stok_akhir ?? 0) }}</div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Item</div>
                    </div>
                    @empty
                    <p style="font-size:13px; color: var(--text-muted); margin: 0; grid-column: 1 / -1;">Belum ada data gudang.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ri-time-line" style="margin-right: 8px; color: var(--primary);"></i>Aktivitas Terbaru</h3>
            </div>
            <div class="card-body" style="max-height: 320px; overflow-y: auto; padding: 0;">
                @php
                    $activities = collect();
                    foreach($recentPO as $po) {
                        $activities->push([
                            'icon' => 'purchase',
                            'iconFa' => 'ri-file-list-3-line',
                            'title' => 'PO Baru: ' . ($po->no_po ?? ''),
                            'desc' => 'Supplier: ' . ($po->supplier->nama_supplier ?? '-'),
                            'time' => $po->tanggal_po ? \Carbon\Carbon::parse($po->tanggal_po)->diffForHumans() : '-',
                        ]);
                    }
                    foreach($recentPenerimaan as $trm) {
                        $activities->push([
                            'icon' => 'receiving',
                            'iconFa' => 'ri-inbox-archive-line',
                            'title' => 'Penerimaan: ' . ($trm->no_penerimaan ?? ''),
                            'desc' => 'Dari: ' . ($trm->pembelian->supplier->nama_supplier ?? '-'),
                            'time' => $trm->tanggal_terima ? \Carbon\Carbon::parse($trm->tanggal_terima)->diffForHumans() : '-',
                        ]);
                    }
                    foreach($recentPemakaian as $pk) {
                        $activities->push([
                            'icon' => 'usage',
                            'iconFa' => 'ri-open-arm-line',
                            'title' => 'Pemakaian: ' . ($pk->no_pemakaian ?? ''),
                            'desc' => 'Dept: ' . ($pk->departemen->nama_departemen ?? '-'),
                            'time' => $pk->tanggal_pakai ? \Carbon\Carbon::parse($pk->tanggal_pakai)->diffForHumans() : '-',
                        ]);
                    }
                    foreach($recentPenjualan as $jual) {
                        $activities->push([
                            'icon' => 'sale',
                            'iconFa' => 'ri-shopping-bag-line',
                            'title' => 'Penjualan: ' . ($jual->no_penjualan ?? ''),
                            'desc' => 'Total: Rp ' . number_format(($jual->total ?? 0) / 1000000, 1) . 'jt',
                            'time' => $jual->tanggal ? \Carbon\Carbon::parse($jual->tanggal)->diffForHumans() : '-',
                        ]);
                    }
                    $activities = $activities->sortByDesc('time')->take(10);
                @endphp

                <div style="padding: 8px 0;">
                    @forelse($activities as $activity)
                    <div style="display: flex; gap: 12px; padding: 12px 20px; border-bottom: 1px solid var(--border-color);">
                        <div style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;
                            @if($activity['icon'] == 'purchase') background: var(--primary-light); color: var(--primary);
                            @elseif($activity['icon'] == 'receiving') background: var(--primary-light); color: var(--primary);
                            @elseif($activity['icon'] == 'usage') background: var(--danger-light); color: var(--danger);
                            @else background: var(--success-light); color: var(--success); @endif
                        ">
                            <i class="{{ $activity['iconFa'] }}"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 13px; font-weight: 500; color: var(--text-primary); margin: 0;">{{ $activity['title'] }}</p>
                            <p style="font-size: 11px; color: var(--text-secondary); margin: 2px 0 0;">{{ $activity['desc'] }}</p>
                            <p style="font-size: 10px; color: var(--text-muted); margin: 4px 0 0;">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @empty
                    <p style="text-align: center; color: var(--text-muted); padding: 20px; margin: 0;">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==================== CHART 1: Line Chart ====================
            const ctx1 = document.getElementById('activityChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: {!! json_encode($weeklyActivity['labels'] ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
                    datasets: [{
                        label: 'Penerimaan',
                        data: {!! json_encode($weeklyActivity['penerimaan'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                        borderColor: '#0F6CBD',
                        backgroundColor: 'rgba(15, 108, 189, 0.08)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#0F6CBD'
                    }, {
                        label: 'Pemakaian',
                        data: {!! json_encode($weeklyActivity['pemakaian'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                        borderColor: '#EB5757',
                        backgroundColor: 'rgba(235, 87, 87, 0.08)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#EB5757'
                    }, {
                        label: 'Penjualan',
                        data: {!! json_encode($weeklyActivity['penjualan'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                        borderColor: '#27AE60',
                        backgroundColor: 'rgba(39, 174, 96, 0.08)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#27AE60'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { font: { size: 11 }, usePointStyle: true, pointStyle: 'circle', padding: 15, boxWidth: 8 }
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
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { font: { size: 10 }, stepSize: 1 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            // ==================== CHART 2: Doughnut ====================
            const ctx2 = document.getElementById('kategoriChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($kategoriDistribusi['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($kategoriDistribusi['data'] ?? []) !!},
                        backgroundColor: {!! json_encode($kategoriDistribusi['colors'] ?? ['#0F6CBD', '#27AE60', '#F2C94C', '#EB5757', '#2D9CDB', '#8B90A0']) !!},
                        borderWidth: 2,
                        borderColor: 'var(--bg-card)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { font: { size: 10 }, boxWidth: 10, padding: 8, usePointStyle: true, pointStyle: 'circle' }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
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
                    cutout: '60%'
                }
            });

            // ==================== CHART 3: Bar Chart Bulanan ====================
            const ctx3 = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyComparison['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Pembelian',
                        data: {!! json_encode($monthlyComparison['pembelian'] ?? []) !!},
                        backgroundColor: '#0F6CBD',
                        borderRadius: 4,
                        borderSkipped: false
                    }, {
                        label: 'Penerimaan',
                        data: {!! json_encode($monthlyComparison['penerimaan'] ?? []) !!},
                        backgroundColor: '#27AE60',
                        borderRadius: 4,
                        borderSkipped: false
                    }, {
                        label: 'Pemakaian',
                        data: {!! json_encode($monthlyComparison['pemakaian'] ?? []) !!},
                        backgroundColor: '#EB5757',
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
                            labels: { font: { size: 10 }, usePointStyle: true, pointStyle: 'rectRounded', padding: 10, boxWidth: 8 }
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
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { font: { size: 9 }, stepSize: 1 }
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

            const gradientColors = topStokData.map((val, i) => {
                const opacity = 0.3 + (i / topStokData.length) * 0.7;
                return `rgba(15, 108, 189, ${opacity})`;
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
                        legend: { display: false },
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
                            grid: { color: 'rgba(0,0,0,0.04)' },
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