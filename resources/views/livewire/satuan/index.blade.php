<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Satuan</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Satuan</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.satuan.form')
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
                        <th>Satuan Item</th>
                        {{-- <th>Nama Satuan</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($satuans as $satuan)
                        <tr>
                            {{-- <td>{{ $satuan->id }}</td> --}}
                            <td>{{ $satuan->kode_satuan }}</td>
                            {{-- <td>{{ $satuan->nama_satuan }}</td> --}}
                            <td>
                                <button wire:click="edit({{ $satuan->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $satuan->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $satuans->links() }}
        </div>
    </div>
</div> 