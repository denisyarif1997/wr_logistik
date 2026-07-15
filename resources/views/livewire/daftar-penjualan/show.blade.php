<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Penjualan - {{ $showPenjualan->no_penjualan }}</h3>
        <div class="card-tools">
            <button wire:click="closeShow" class="btn btn-sm btn-secondary">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>No Penjualan:</strong> {{ $showPenjualan->no_penjualan }}
            </div>
            <div class="col-md-4">
                <strong>Tanggal:</strong> {{ $showPenjualan->tanggal->timezone('Asia/Jakarta')->format('d-m-Y') }}
            </div>
            <div class="col-md-4">
                <strong>Gudang:</strong> {{ $showPenjualan->gudang->nama_gudang ?? '-' }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Jam Dibuat:</strong> {{ $showPenjualan->created_at ? $showPenjualan->created_at->timezone('Asia/Jakarta')->format('H:i:s') : '-' }}
            </div>
            @if($showPenjualan->diskon > 0)
            <div class="col-md-4">
                <strong>Diskon:</strong> {{ $showPenjualan->diskon }}%
            </div>
            @endif
            @if($showPenjualan->ppn > 0)
            <div class="col-md-4">
                <strong>PPN:</strong> {{ $showPenjualan->ppn_rate }}% (Rp {{ number_format($showPenjualan->ppn, 0, ',', '.') }})
            </div>
            @endif
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Kasir:</strong> {{ $showPenjualan->creator->name ?? '-' }}
            </div>
            <div class="col-md-4">
                <strong>Status:</strong>
                <span class="badge badge-{{ $showPenjualan->status == 'selesai' ? 'success' : 'warning' }}">
                    {{ ucfirst($showPenjualan->status) }}
                </span>
            </div>
            @if($showPenjualan->keterangan)
            <div class="col-md-4">
                <strong>Keterangan:</strong> {{ $showPenjualan->keterangan }}
            </div>
            @endif
        </div>

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($showPenjualan->details as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $d->barang->nama_barang ?? '-' }}</td>
                    <td class="text-center">{{ number_format($d->qty, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-6 offset-md-6">
                <table class="table table-sm">
                    @php
                        $subtotal = $showPenjualan->details->sum('subtotal');
                        $diskonAmount = $subtotal * ($showPenjualan->diskon / 100);
                    @endphp
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">{{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($showPenjualan->diskon > 0)
                    <tr>
                        <td>Diskon ({{ $showPenjualan->diskon }}%)</td>
                        <td class="text-right">- {{ number_format($diskonAmount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($showPenjualan->ppn > 0)
                    <tr>
                        <td>PPN ({{ $showPenjualan->ppn_rate }}%)</td>
                        <td class="text-right">{{ number_format($showPenjualan->ppn, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ number_format($showPenjualan->total, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Bayar</td>
                        <td class="text-right">{{ number_format($showPenjualan->bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Kembalian</td>
                        <td class="text-right">{{ number_format($showPenjualan->kembalian, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.kasir.print', $showPenjualan->id) }}" target="_blank" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak Billing
            </a>
        </div>
    </div>
</div>