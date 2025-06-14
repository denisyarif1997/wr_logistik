<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Gudang</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Gudang</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.gudang.form')
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search by name...">
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Gudang</th>
                        <th>Lokasi</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Updated By</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gudangs as $gudang)
                        <tr>
                            <td>{{ $gudang->nama_gudang }}</td>
                            <td>{{ $gudang->lokasi }}</td>
                            <td>{{ $gudang->creator->name ?? '-' }}</td>
                            <td>{{ $gudang->created_at }}</td>
                            <td>{{ $gudang->updater->name ?? '-' }}</td>
                            <td>{{ $gudang->updated_at }}</td>
                            <td>
                                <button wire:click="edit({{ $gudang->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $gudang->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $gudangs->links() }}
        </div>
    </div>
</div> 