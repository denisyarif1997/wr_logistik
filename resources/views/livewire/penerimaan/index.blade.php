<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-truck-loading mr-2"></i>Penerimaan Barang</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> New Penerimaan
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.penerimaan.form')
                <hr>
            @endif

            <div class="row align-items-end mb-3">
                <div class="col-md-3">
                    <label class="mb-1"><i class="far fa-calendar-alt mr-1"></i> Start Date</label>
                    <input wire:model="startDate" type="date" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="mb-1"><i class="far fa-calendar-check mr-1"></i> End Date</label>
                    <input wire:model="endDate" type="date" class="form-control form-control-sm">
                </div>
                <div class="col-md-4">
                    <label class="mb-1"><i class="fas fa-search mr-1"></i> Search</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Cari No Penerimaan, PO, atau Supplier...">
                        <div class="input-group-append">
                            <button wire:click="$refresh" class="btn btn-primary" type="button">
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>No. Faktur</th>
                            <th>No. PO</th>
                            <th>Supplier</th>
                            <th>Tanggal Terima</th>
                            <th>Gudang</th>
                            <th>Total Nilai</th>
                            <th>Status Bayar</th>
                            <th>Diterima Oleh</th>
                            <th width="12%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penerimaans as $index => $penerimaan)
                            @php
                                $totalNilai = $penerimaan->calculated_total ?? 0;
                                $pembayaran = $penerimaan->pembayaran->first();
                                $totalPembayaran = $penerimaan->pembayaran->where('status', '!=', 'gagal')->sum('jumlah_bayar');
                                
                                if ($totalPembayaran >= $totalNilai) {
                                    $statusBayar = 'lunas';
                                } elseif ($totalPembayaran > 0) {
                                    $statusBayar = 'cicilan';
                                } else {
                                    $statusBayar = 'belum_bayar';
                                }
                            @endphp
                            <tr>
                                <td>{{ $penerimaans->firstItem() + $index }}</td>
                                <td><strong>{{ $penerimaan->no_penerimaan }}</strong></td>
                                <td>
                                    <span class="badge badge-info">{{ $penerimaan->pembelian->no_po ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-building text-muted mr-1"></i>
                                    {{ $penerimaan->pembelian->supplier->nama_supplier ?? 'N/A' }}
                                </td>
                                <td>
                                    <small>
                                        <i class="far fa-calendar mr-1"></i>
                                        {{ $penerimaan->tanggal_terima ? \Carbon\Carbon::parse($penerimaan->tanggal_terima)->format('d/m/Y') : '-' }}
                                    </small>
                                </td>
                                <td>{{ $penerimaan->gudang->nama_gudang ?? 'N/A' }}</td>
                                <td>
                                    <strong class="text-primary">Rp {{ number_format($totalNilai, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($statusBayar == 'lunas')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i>LUNAS
                                        </span>
                                    @elseif($statusBayar == 'cicilan')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i>CICILAN
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-exclamation-circle mr-1"></i>BELUM BAYAR
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $penerimaan->diterima_oleh }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button wire:click="show({{ $penerimaan->id }})" class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if(!$pembayaran)
                                            <button wire:click="edit({{ $penerimaan->id }})" class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="delete({{ $penerimaan->id }})" class="btn btn-danger" 
                                                onclick="return confirm('Yakin hapus data penerimaan ini?');" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada data penerimaan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $penerimaans->links() }}
            </div>
        </div>
    </div>
</div>