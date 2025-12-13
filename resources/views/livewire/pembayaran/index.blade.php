<div>
    <div class="row">
        <!-- Data Penerimaan -->
        <div class="@if($isOpen) col-md-8 @else col-md-12 @endif">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-truck-loading mr-2"></i>Data Penerimaan</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Cari No Penerimaan, No PO, atau Supplier...">
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
                                    <th>No. Penerimaan</th>
                                    <th>No. PO</th>
                                    <th>Supplier</th>
                                    <th>Tanggal Terima</th>
                                    <th>Gudang</th>
                                    <th>Total Nilai</th>
                                    <th>Status Bayar</th>
                                    <th>Diterima Oleh</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penerimaansBelumLunas as $index => $penerimaan)
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
                                        <td>{{ $index + 1 }}</td>
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
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" wire:click="showPaymentDetail({{ $penerimaan->id }})" 
                                                        class="btn btn-info" title="Detail Pembayaran">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($statusBayar != 'lunas')
                                                    <button type="button" wire:click="createFromPenerimaan({{ $penerimaan->id }})" 
                                                            class="btn btn-success" title="Bayar">
                                                        <i class="fas fa-money-bill-wave"></i>
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
                </div>
            </div>
        </div>

        <!-- Form Pembayaran -->
        @if($isOpen)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title mb-0 text-white">
                            <i class="fas fa-money-bill-wave mr-2"></i>Form Pembayaran
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('livewire.pembayaran.form')
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Payment Detail Modal -->
    @if($showPaymentDetailModal && $selectedPenerimaanForDetail)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>Detail Pembayaran - {{ $selectedPenerimaanForDetail->no_penerimaan }}
                        </h5>
                        <button type="button" class="close text-white" wire:click="closePaymentDetail">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @php
                            $totalNilai = $selectedPenerimaanForDetail->calculated_total ?? 0;
                            $totalBayar = $selectedPenerimaanForDetail->pembayaran->where('status', '!=', 'gagal')->sum('jumlah_bayar');
                            $sisaHutang = $totalNilai - $totalBayar;
                        @endphp

                        <!-- Info Penerimaan -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Penerimaan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="40%"><strong>No. Penerimaan:</strong></td>
                                                <td>{{ $selectedPenerimaanForDetail->no_penerimaan }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Supplier:</strong></td>
                                                <td>{{ $selectedPenerimaanForDetail->pembelian->supplier->nama_supplier ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. PO:</strong></td>
                                                <td>{{ $selectedPenerimaanForDetail->pembelian->no_po ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Tanggal Terima:</strong></td>
                                                <td>{{ $selectedPenerimaanForDetail->tanggal_terima ? \Carbon\Carbon::parse($selectedPenerimaanForDetail->tanggal_terima)->format('d/m/Y') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Gudang:</strong></td>
                                                <td>{{ $selectedPenerimaanForDetail->gudang->nama_gudang ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Diterima Oleh:</strong></td>
                                                <td>{{ $selectedPenerimaanForDetail->diterima_oleh }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ringkasan Pembayaran -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-calculator mr-2"></i>Ringkasan Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td width="50%"><strong>Total Tagihan:</strong></td>
                                        <td class="text-right"><strong class="text-primary">Rp {{ number_format($totalNilai, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Sudah Dibayar:</strong></td>
                                        <td class="text-right"><strong class="text-success">Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr class="border-top">
                                        <td><strong>Sisa Hutang:</strong></td>
                                        <td class="text-right">
                                            <strong class="{{ $sisaHutang > 0 ? 'text-danger' : 'text-success' }}">
                                                Rp {{ number_format($sisaHutang, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Riwayat Pembayaran -->
                        @if($selectedPenerimaanForDetail->pembayaran->count() > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-history mr-2"></i>Riwayat Pembayaran</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal Bayar</th>
                                                    <th>Jumlah Bayar</th>
                                                    <th>Metode Bayar</th>
                                                    <th>Keterangan</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectedPenerimaanForDetail->pembayaran->sortByDesc('tanggal_bayar') as $index => $pay)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <small>
                                                                <i class="far fa-calendar mr-1"></i>
                                                                {{ $pay->tanggal_bayar ? \Carbon\Carbon::parse($pay->tanggal_bayar)->format('d/m/Y') : '-' }}
                                                            </small>
                                                        </td>
                                                        <td><strong class="text-success">Rp {{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</strong></td>
                                                        <td>
                                                            <span class="badge badge-secondary">
                                                                <i class="fas fa-wallet mr-1"></i>
                                                                {{ $pay->akun->nama_akun ?? $pay->metode_bayar ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td><small>{{ $pay->keterangan ?? '-' }}</small></td>
                                                        <td>
                                                            @if($pay->status == 'lunas')
                                                                <span class="badge badge-success">Lunas</span>
                                                            @elseif($pay->status == 'pending')
                                                                <span class="badge badge-info">Pending</span>
                                                            @else
                                                                <span class="badge badge-secondary">{{ $pay->status }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Belum ada pembayaran untuk penerimaan ini.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closePaymentDetail">
                            <i class="fas fa-times mr-1"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
