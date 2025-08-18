<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pembayaran</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Pembayaran</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.pembayaran.form')
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Search">
                        <div class="input-group-append">
                            <button wire:click="$refresh" class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>No. Penerimaan</th>
                        <th>Tanggal Bayar</th>
                        <th>Jumlah Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                        <th>Dibuat Pada</th>
                        <th>Diperbarui Oleh</th>
                        <th>Diperbarui Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembayarans as $pembayaran)
                        <tr>
                            <td>{{ $pembayaran->id }}</td>
                            <td>{{ $pembayaran->penerimaan->no_penerimaan ?? '-' }}</td>
                            <td>{{ $pembayaran->tanggal_bayar }}</td>
                            <td>{{ number_format($pembayaran->jumlah_bayar, 2) }}</td>
                            <td>{{ $pembayaran->metode_bayar }}</td>
                            <td>{{ ucfirst($pembayaran->status) }}</td>
                            <td>{{ $pembayaran->creator->name ?? '-' }}</td>
                            <td>{{ $pembayaran->created_at }}</td>
                            <td>{{ $pembayaran->updater->name ?? '-' }}</td>
                            <td>{{ $pembayaran->updated_at }}</td>
                            <td>
                                <button wire:click="edit({{ $pembayaran->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $pembayaran->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pembayaran ini?');">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $pembayarans->links() }}
        </div>
    </div>
</div>
