<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Laporan Laba Rugi (Profit & Loss)</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end mb-3">
                <div class="col-md-3">
                    <label class="mb-1 small text-muted"><i class="far fa-calendar-alt mr-1"></i> Start Date</label>
                    <input wire:model="startDate" type="date" class="form-control form-control-sm shadow-sm">
                </div>
                <div class="col-md-3">
                    <label class="mb-1 small text-muted"><i class="far fa-calendar-check mr-1"></i> End Date</label>
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
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">Pendapatan</h5>
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
                                    @forelse($pendapatanData as $item)
                                        <tr>
                                            <td>{{ $item['kode'] }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td class="text-right">{{ number_format($item['total'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data pendapatan.</td>
                                        </tr>
                                    @endforelse
                                    <tr class="font-weight-bold bg-light">
                                        <td colspan="2">Total Pendapatan</td>
                                        <td class="text-right">{{ number_format($pendapatanTotal, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0">Beban</h5>
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
                                    @forelse($bebanData as $item)
                                        <tr>
                                            <td>{{ $item['kode'] }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td class="text-right">{{ number_format($item['total'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data beban.</td>
                                        </tr>
                                    @endforelse
                                    <tr class="font-weight-bold bg-light">
                                        <td colspan="2">Total Beban</td>
                                        <td class="text-right">{{ number_format($bebanTotal, 2) }}</td>
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
                        <div class="card-header {{ $labaRugi >= 0 ? 'bg-primary' : 'bg-warning' }} text-white">
                            <h5 class="card-title mb-0">Ringkasan</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered mb-0">
                                <tr>
                                    <td width="50%"><strong>Total Pendapatan</strong></td>
                                    <td class="text-right"><strong>{{ number_format($pendapatanTotal, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Beban</strong></td>
                                    <td class="text-right"><strong>{{ number_format($bebanTotal, 2) }}</strong></td>
                                </tr>
                                <tr class="h4">
                                    <td><strong>LABA RUGI</strong></td>
                                    <td class="text-right"><strong class="{{ $labaRugi >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($labaRugi, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>