<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Laporan Hutang Supplier</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end mb-3">
                <div class="col-md-3">
                    <label class="mb-1 small text-muted"><i class="fas fa-truck mr-1"></i> Supplier</label>
                    <select wire:model="supplier_id" class="form-control form-control-sm shadow-sm">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="mb-1 small text-muted"><i class="fas fa-filter mr-1"></i> Status</label>
                    <select wire:model="status" class="form-control form-control-sm shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="belum_lunas">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="mb-1 small text-muted"><i class="fas fa-search mr-1"></i> Pencarian</label>
                    <div class="input-group input-group-sm shadow-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control border-left-0" placeholder="Cari No Penerimaan/Supplier...">
                        <div class="input-group-append">
                            <button wire:click="$refresh" class="btn btn-primary" type="button">
                                <i class="fas fa-search mr-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secondary btn-sm" wire:click="resetFilter">
                        <i class="fas fa-sync"></i> Reset
                    </button>
                </div>
            </div>

            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>No Penerimaan</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th class="text-right">Total Tagihan</th>
                        <th class="text-right">Total Bayar</th>
                        <th class="text-right">Sisa Hutang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr>
                            <td>{{ $item['no_penerimaan'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                            <td>{{ $item['supplier'] }}</td>
                            <td class="text-right">{{ number_format($item['total_tagihan'], 2) }}</td>
                            <td class="text-right">{{ number_format($item['total_bayar'], 2) }}</td>
                            <td class="text-right">{{ number_format($item['sisa_hutang'], 2) }}</td>
                            <td>
                                @if($item['status'] == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold bg-light">
                        <td colspan="3">Total</td>
                        <td class="text-right">{{ number_format($data->sum('total_tagihan'), 2) }}</td>
                        <td class="text-right">{{ number_format($data->sum('total_bayar'), 2) }}</td>
                        <td class="text-right">{{ number_format($totalHutang, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $penerimaans->links() }}
            </div>
        </div>
    </div>
</div>