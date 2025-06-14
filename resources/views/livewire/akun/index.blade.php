<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Akun (Chart of Accounts)</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Akun</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.akun.form')
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search by name or code...">
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Tipe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($akuns as $akun)
                        <tr>
                            <td>{{ $akun->kode_akun }}</td>
                            <td>{{ $akun->nama_akun }}</td>
                            <td>{{ $akun->tipe }}</td>
                            <td>
                                <button wire:click="edit({{ $akun->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $akun->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $akuns->links() }}
        </div>
    </div>
</div> 