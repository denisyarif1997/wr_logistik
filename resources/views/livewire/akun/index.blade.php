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

            <!-- <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search by name or code...">
                </div>
            </div> -->

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
                   <buttonx-on:click.prevent="
        if (confirm('Anda yakin ingin mengapus data ini, data yang di hapus tidak bisa dikembalikan?')) {
            $wire.delete({{ $akun->id }});
        }
    "
    class="btn btn-sm btn-danger">
    Delete
</buttonx-on:click.prevent=>                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $akuns->links() }}
        </div>
    </div>
</div> 