<div>
    @if($isShow)
        @include('livewire.daftar-penjualan.show')
    @else
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Penjualan</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Cari no penjualan...">
                        <div class="input-group-append">
                            <button wire:click="$refresh" class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" wire:model="startDate">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" wire:model="endDate">
                </div>
                <div class="col-md-2">
                    <button wire:click="$refresh" class="btn btn-info btn-block"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No Penjualan</th>
                        <th>Waktu</th>
                        <!-- <th>Jam</ -->
                        <th>Gudang</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Kembalian</th>
                        <th>Status</th>
                        <th>Kasir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penjualans as $p)
                        <tr>
                            <td>{{ $p->no_penjualan }}</td>
                            <td>
                                {{ $p->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}
                            </td>
                            <td>{{ $p->gudang->nama_gudang ?? '-' }}</td>
                            <td class="text-right">{{ number_format($p->total, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($p->bayar, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($p->kembalian, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $p->status == 'selesai' ? 'success' : 'warning' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td>{{ $p->creator->name ?? '-' }}</td>
                            <td>
                                <button wire:click="show({{ $p->id }})" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i> Show
                                </button>
                                <a href="{{ route('admin.kasir.print', $p->id) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $penjualans->links() }}
        </div>
    </div>
    @endif
</div>
