<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pembelian</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Pembelian</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.pembelian.form')
            @endif
        

            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search by PO Number...">
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>No Pembelian</th>
                        <th>Tanggal PO</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Updated By</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembelians as $pembelian)
                        <tr>
                            <td>{{ $pembelian->id }}</td>
                            <td>{{ $pembelian->no_po }}</td>
                            <td>{{ $pembelian->tanggal_po }}</td>
                            <td>{{ $pembelian->supplier->nama_supplier ?? 'N/A' }}</td>
                            <td>{{ $pembelian->status }}</td>
                            <td>{{ $pembelian->creator->name ?? '-' }}</td>
                            <td>{{ $pembelian->created_at }}</td>
                            <td>{{ $pembelian->updater->name ?? '-' }}</td>
                            <td>{{ $pembelian->updated_at }}</td>
                            {{-- <td>{{ $pembelian->deleter->name ?? '-' }}</td> --}}
                            
                            <td>
                                @if(strtolower($pembelian->status) !== 'received')
                                    <button wire:click="edit({{ $pembelian->id }})" class="btn btn-sm btn-primary">Edit</button>
                                    <button wire:click="delete({{ $pembelian->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                                @else
                                    <button wire:click="show({{ $pembelian->id }})" class="btn btn-sm btn-secondary">Show</button>
                                @endif
                            </td>                            
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $pembelians->links() }}
        </div>
    </div>
</div> 