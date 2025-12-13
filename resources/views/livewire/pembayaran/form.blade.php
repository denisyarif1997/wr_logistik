<form wire:submit.prevent="store">
    @if($penerimaan_id)
        @php
            $selectedPenerimaan = \App\Models\Penerimaan::with(['pembelian.supplier', 'pembayaran'])->find($penerimaan_id);
            $totalTagihan = $selectedPenerimaan->calculated_total ?? 0;
            $totalBayar = $selectedPenerimaan->pembayaran->where('status', '!=', 'gagal')->sum('jumlah_bayar');
            $sisaHutang = $totalTagihan - $totalBayar;
        @endphp
        
        {{-- Info Penerimaan --}}
        <div class="alert alert-info">
            <h5 class="mb-3"><i class="fas fa-info-circle mr-2"></i>Detail Penerimaan</h5>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td width="40%"><strong>No Penerimaan:</strong></td>
                    <td>{{ $selectedPenerimaan->no_penerimaan ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Supplier:</strong></td>
                    <td>{{ $selectedPenerimaan->pembelian->supplier->nama_supplier ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>No PO:</strong></td>
                    <td>{{ $selectedPenerimaan->pembelian->no_po ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- Ringkasan Pembayaran --}}
        <div class="card bg-light mb-3">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-calculator mr-2"></i>Ringkasan Pembayaran</h5>
                <table class="table table-sm mb-0">
                    <tr>
                        <td width="50%"><strong>Total Tagihan:</strong></td>
                        <td class="text-right"><strong class="text-primary">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Total Sudah Dibayar:</strong></td>
                        <td class="text-right"><strong class="text-success">Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>Sisa Hutang:</strong></td>
                        <td class="text-right"><strong class="text-danger">Rp {{ number_format($sisaHutang, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Riwayat Pembayaran --}}
        @if($selectedPenerimaan->pembayaran->count() > 0)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-history mr-2"></i>Riwayat Pembayaran</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedPenerimaan->pembayaran->sortByDesc('tanggal_bayar') as $pay)
                                    <tr>
                                        <td><small>{{ $pay->tanggal_bayar ? \Carbon\Carbon::parse($pay->tanggal_bayar)->format('d/m/Y') : '-' }}</small></td>
                                        <td><small class="text-success">Rp {{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</small></td>
                                        <td><small>{{ $pay->akun->nama_akun ?? $pay->metode_bayar ?? '-' }}</small></td>
                                        <td>
                                            @if($pay->status == 'lunas')
                                                <span class="badge badge-success badge-sm">Lunas</span>
                                            @elseif($pay->status == 'pending')
                                                <span class="badge badge-info badge-sm">Pending</span>
                                            @else
                                                <span class="badge badge-secondary badge-sm">{{ $pay->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <hr>

    {{-- Form Input Pembayaran --}}
    <h5 class="mb-3"><i class="fas fa-money-bill-wave mr-2"></i>Input Pembayaran</h5>

    <div class="form-group">
        <label for="tanggal_bayar">Tanggal Bayar <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="tanggal_bayar" wire:model="tanggal_bayar" @if($isShow) readonly @endif>
        @error('tanggal_bayar') <span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label for="jumlah_bayar">Jumlah Bayar <span class="text-danger">*</span></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Rp</span>
            </div>
            <input type="number" step="0.01" class="form-control" id="jumlah_bayar" 
                   placeholder="Masukkan jumlah bayar" wire:model="jumlah_bayar" @if($isShow) readonly @endif>
        </div>
        <small class="form-text text-muted">
            <i class="fas fa-info-circle mr-1"></i>
            Maksimal: Rp {{ number_format($sisaHutang ?? 0, 0, ',', '.') }}
        </small>
        @error('jumlah_bayar') <span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label for="akun_id">Akun Pembayaran (Kas/Bank) <span class="text-danger">*</span></label>
        <select class="form-control" id="akun_id" wire:model="akun_id" @if($isShow) disabled @endif>
            <option value="">Pilih Akun</option>
            @foreach($akuns as $akun)
                <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
            @endforeach
        </select>
        @error('akun_id') <span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label for="keterangan">Keterangan</label>
        <textarea class="form-control" id="keterangan" rows="2" 
                  placeholder="Masukkan keterangan pembayaran (opsional)" 
                  wire:model="keterangan" @if($isShow) readonly @endif></textarea>
        @error('keterangan') <span class="text-danger">{{ $message }}</span>@enderror
    </div>

    <hr>

    @unless($isShow)
        <button type="submit" class="btn btn-success btn-lg btn-block">
            <i class="fas fa-save mr-2"></i>Simpan Pembayaran
        </button>
    @endunless

    <button wire:click="closeModal()" type="button" class="btn btn-secondary btn-block">
        <i class="fas fa-times mr-2"></i>{{ $isShow ? 'Tutup' : 'Batal' }}
    </button>
</form>
