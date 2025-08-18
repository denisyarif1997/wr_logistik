<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pemakaian Barang</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Pemakaian</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.pemakaian.form')
            @endif

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
                        <th>No. Pemakaian</th>
                        <th>Tanggal Pakai</th>
                        <th>Departemen</th>
                        <th>Gudang</th>
                        <th>Diajukan Oleh</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemakaians as $pemakaian)
                        <tr>
                            <td>{{ $pemakaian->no_pemakaian }}</td>
                            <td>{{ $pemakaian->tanggal_pakai }}</td>
                            <td>{{ $pemakaian->departemen->nama_departemen ?? 'N/A' }}</td>
                            <td>{{ $pemakaian->gudang->nama_gudang ?? 'N/A' }}</td>
                            <td>{{ $pemakaian->diajukan_oleh }}</td>
                            <td>
                                @if ($pemakaian->no_pemakaian != null)
                                <button wire:click="view({{ $pemakaian->id }})" class="btn btn-sm btn-info">Show</button>
                                @else
                                <button wire:click="edit({{ $pemakaian->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $pemakaian->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $pemakaians->links() }}
        </div>
    </div>

    @if($viewing)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pemakaian Details: {{ $viewing->no_pemakaian }}</h5>
                    <button type="button" class="close" wire:click="closeViewModal()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($viewing->details as $detail)
                            <tr>
                                <td>{{ $detail->barang->nama_barang ?? 'N/A' }}</td>
                                <td>{{ $detail->qty }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeViewModal()">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" wire:click="closeViewModal()"></div>
    @endif
</div> 