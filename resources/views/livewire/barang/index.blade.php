<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Barang</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Barang</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.barang.form')
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Search by name or code...">
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
                        {{-- <th>Id Barang</th> --}}
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Stok Minimum</th>
                        <th>Stok Saat Ini</th>
                        <th>Harga Beli Terakhir</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Updated By</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                        <tr>
                            {{-- <td>{{ $barang->id }}</td> --}}
                            <td>{{ $barang->kode_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->satuan }}</td>
                            <td>{{ $barang->stok_minimum }}</td>
                            <td>{{ $barang->stok_aktual ?? 0 }}</td>
                            <td>{{ $barang->harga_beli_terakhir }}</td>
                            <td>{{ $barang->creator->name ?? '-' }}</td>
                            <td>{{ $barang->created_at }}</td>
                            <td>{{ $barang->updater->name ?? '-' }}</td>
                            <td>{{ $barang->updated_at }}</td>
                            <td>
                                <button wire:click="edit({{ $barang->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $barang->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $barangs->links() }}
        </div>
    </div>
</div> 