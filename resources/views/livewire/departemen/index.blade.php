<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Departemen</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Departemen</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.departemen.form')
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search by name...">
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Departemen</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departemens as $departemen)
                        <tr>
                            <td>{{ $departemen->nama_departemen }}</td>
                            <td>
                                <button wire:click="edit({{ $departemen->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $departemen->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $departemens->links() }}
        </div>
    </div>
</div> 