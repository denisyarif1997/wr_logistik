<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Suppliers</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Supplier</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.supplier-form')
            @endif

            {{-- <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search suppliers...">
                </div>
            </div> --}}

             <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Search Suppliers">
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
                        <th>Name</th>
                        <th>Alamat</th>
                        <th>telepon</th>
                        <th>Email</th>
                        <th>NPWP</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->nama_supplier }}</td>
                            <td>{{ $supplier->alamat }}</td>
                            <td>{{ $supplier->telepon }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>{{ $supplier->npwp }}</td>
                            <td>
                                <button wire:click="edit({{ $supplier->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $supplier->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $suppliers->links() }}
        </div>
    </div>
</div> 