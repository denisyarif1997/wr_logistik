<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stok Barang</h3>
        </div>
        <div class="card-body">
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
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Gudang</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stoks as $stok)
                        <tr>
                            <td>{{ $stok->barang->kode_barang ?? 'N/A' }}</td>
                            <td>{{ $stok->barang->nama_barang ?? 'N/A' }}</td>
                            <td>{{ $stok->gudang->nama_gudang ?? 'N/A' }}</td>
                            <td>{{ $stok->total_qty }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $stoks->links() }}
        </div>
    </div>
</div>
