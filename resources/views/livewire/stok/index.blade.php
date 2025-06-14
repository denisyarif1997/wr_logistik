<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stok Barang</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.500ms="search" type="text" class="form-control" placeholder="Cari kode/nama barang...">
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
