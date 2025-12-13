{{-- Dashboard CSS --}}
<style>
    .dashboard-box {
        border-radius: 12px;
        padding: 24px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: none;
    }

    .dashboard-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .dashboard-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 48px;
        opacity: 0.3;
    }

    .dashboard-inner h3 {
        font-size: 42px;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .dashboard-inner p {
        margin: 8px 0 0;
        font-size: 16px;
        font-weight: 500;
        opacity: 0.95;
    }

    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
    .bg-gradient-dark { background: linear-gradient(135deg, #434343 0%, #000000 100%); }
    .bg-gradient-purple { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); }

    .small-box-footer {
        display: block;
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px solid rgba(255,255,255,0.3);
        color: white !important;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .small-box-footer:hover {
        color: white !important;
        padding-left: 5px;
    }

    .card-modern {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .card-modern:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .card-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 15px 20px;
        border: none;
    }

    .table-modern {
        font-size: 14px;
    }

    .table-modern thead th {
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    .badge-modern {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 11px;
    }

    .alert-modern {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 3px solid #667eea;
        display: inline-block;
    }
</style>

<div class="container-fluid">
    @role('admin')
        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-box bg-gradient-primary">
                    <div class="dashboard-inner">
                        <h3>{{ number_format($totalBarang) }}</h3>
                        <p>Total Jenis Barang</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <a href="{{ route('admin.barang.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-box bg-gradient-success">
                    <div class="dashboard-inner">
                        <h3>{{ number_format($totalStok) }}</h3>
                        <p>Total Stok Barang</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <a href="{{ route('admin.stok.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-box bg-gradient-info">
                    <div class="dashboard-inner">
                        <h3>{{ number_format($pembelian) }}</h3>
                        <p>Purchase Orders</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('admin.pembelian.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-box bg-gradient-warning">
                    <div class="dashboard-inner">
                        <h3>{{ number_format($penerimaan) }}</h3>
                        <p>Total Penerimaan</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fas fa-truck-loading"></i>
                    </div>
                    <a href="{{ route('admin.penerimaan.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-box bg-gradient-danger">
                    <div class="dashboard-inner">
                        <h3>{{ number_format($pemakaian) }}</h3>
                        <p>Total Pemakaian</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fas fa-dolly"></i>
                    </div>
                    <a href="{{ route('admin.pemakaian.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-box bg-gradient-purple">
                    <div class="dashboard-inner">
                        <h3>Rp {{ number_format($totalHutang, 0, ',', '.') }}</h3>
                        <p>Total Hutang</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <a href="{{ route('admin.pembayaran.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-box bg-gradient-secondary">
                    <div class="dashboard-inner">
                        <h3>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h3>
                        <p>Total Pembayaran</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <a href="{{ route('admin.pembayaran.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="dashboard-box bg-gradient-dark">
                    <div class="dashboard-inner">
                        <h3>{{ number_format($user) }}</h3>
                        <p>Total Users</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('admin.user.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        @if($lowStockItems->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning alert-modern">
                    <h5 class="mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Peringatan Stok Menipis</h5>
                    <p class="mb-0">Ada <strong>{{ $lowStockItems->count() }}</strong> barang dengan stok di bawah minimum. Segera lakukan pemesanan!</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Recent Activities & Low Stock --}}
        <div class="row">
            {{-- Recent Purchase Orders --}}
            <div class="col-lg-6 mb-4">
                <div class="card card-modern">
                    <div class="card-header card-header-modern">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart mr-2"></i>Purchase Order Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern table-hover mb-0">
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
                                                <span class="badge badge-success badge-modern">Approved</span>
                                            @elseif($po->status == 'pending')
                                                <span class="badge badge-warning badge-modern">Pending</span>
                                            @else
                                                <span class="badge badge-info badge-modern">{{ ucfirst($po->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Penerimaan --}}
            <div class="col-lg-6 mb-4">
                <div class="card card-modern">
                    <div class="card-header card-header-modern">
                        <h5 class="mb-0"><i class="fas fa-truck-loading mr-2"></i>Penerimaan Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No Penerimaan</th>
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
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Recent Pemakaian --}}
            <div class="col-lg-6 mb-4">
                <div class="card card-modern">
                    <div class="card-header card-header-modern">
                        <h5 class="mb-0"><i class="fas fa-dolly mr-2"></i>Pemakaian Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No Pemakaian</th>
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
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Low Stock Items --}}
            <div class="col-lg-6 mb-4">
                <div class="card card-modern">
                    <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle mr-2"></i>Stok Menipis</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern table-hover mb-0">
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
                                        <td>
                                            <span class="badge badge-danger badge-modern">
                                                {{ $item->stok->sum('stok_akhir') }}
                                            </span>
                                        </td>
                                        <td>{{ $item->stok_minimum }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-success py-3">
                                            <i class="fas fa-check-circle mr-2"></i>Semua stok aman
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stock by Warehouse --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-modern">
                    <div class="card-header card-header-modern">
                        <h5 class="mb-0"><i class="fas fa-warehouse mr-2"></i>Stok Per Gudang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($stokPerGudang as $gudang)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <div class="card" style="border-left: 4px solid #667eea;">
                                    <div class="card-body">
                                        <h6 class="mb-2 text-muted">{{ $gudang->nama_gudang }}</h6>
                                        <h3 class="mb-0 font-weight-bold" style="color: #667eea;">
                                            {{ number_format($gudang->stok_sum_stok_akhir ?? 0) }}
                                        </h3>
                                        <small class="text-muted">Unit</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endrole
</div>
