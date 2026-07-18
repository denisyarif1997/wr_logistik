<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Laporan Neraca (Balance Sheet)</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end mb-3">
                <div class="col-md-3">
                    <label class="mb-1 small text-muted"><i class="far fa-calendar-check mr-1"></i> Per Tanggal</label>
                    <input wire:model="endDate" type="date" class="form-control form-control-sm shadow-sm">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm" wire:click="$refresh">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secondary btn-sm" wire:click="resetFilter">
                        <i class="fas fa-sync"></i> Reset
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Aset</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th width="20%">Kode</th>
                                        <th>Nama Akun</th>
                                        <th width="25%" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($asetData as $item)
                                        <tr>
                                            <td>{{ $item['kode'] }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td class="text-right">{{ number_format($item['total'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data aset.</td>
                                        </tr>
                                    @endforelse
                                    <tr class="font-weight-bold bg-light">
                                        <td colspan="2">Total Aset</td>
                                        <td class="text-right">{{ number_format($totalAset, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">Liabilitas & Ekuitas</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th width="20%">Kode</th>
                                        <th>Nama Akun</th>
                                        <th width="25%" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-light">
                                        <td colspan="3"><strong>LIABILITAS</strong></td>
                                    </tr>
                                    @forelse($liabilitasData as $item)
                                        <tr>
                                            <td>{{ $item['kode'] }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td class="text-right">{{ number_format($item['total'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data liabilitas.</td>
                                        </tr>
                                    @endforelse
                                    <tr class="font-weight-bold bg-light">
                                        <td colspan="2">Total Liabilitas</td>
                                        <td class="text-right">{{ number_format($totalLiabilitas, 2) }}</td>
                                    </tr>
                                    
                                    <tr class="bg-light">
                                        <td colspan="3"><strong>EKUITAS</strong></td>
                                    </tr>
                                    @forelse($ekuitasData as $item)
                                        <tr>
                                            <td>{{ $item['kode'] }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td class="text-right">{{ number_format($item['total'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data ekuitas.</td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="2">Laba Rugi Ditambah</td>
                                        <td class="text-right">{{ number_format($labaRugiPeriode, 2) }}</td>
                                    </tr>
                                    <tr class="font-weight-bold bg-light">
                                        <td colspan="2">Total Ekuitas</td>
                                        <td class="text-right">{{ number_format($totalEkuitas, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header {{ $totalAset == ($totalLiabilitas + $totalEkuitas) ? 'bg-success' : 'bg-warning' }} text-white">
                            <h5 class="card-title mb-0">Validasi Neraca</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered mb-0">
                                <tr>
                                    <td width="50%"><strong>Total Aset</strong></td>
                                    <td class="text-right"><strong>{{ number_format($totalAset, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Liabilitas + Ekuitas</strong></td>
                                    <td class="text-right"><strong>{{ number_format($totalLiabilitas + $totalEkuitas, 2) }}</strong></td>
                                </tr>
                                <tr class="h4">
                                    <td><strong>SELISIH</strong></td>
                                    <td class="text-right"><strong class="{{ $totalAset == ($totalLiabilitas + $totalEkuitas) ? 'text-success' : 'text-danger' }}">{{ number_format($totalAset - ($totalLiabilitas + $totalEkuitas), 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>