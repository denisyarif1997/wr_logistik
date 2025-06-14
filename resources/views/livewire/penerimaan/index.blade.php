<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Penerimaan Barang</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Penerimaan</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.penerimaan.form')
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search by No. Penerimaan...">
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        {{-- <th>Id Penerimaan</th> --}}
                        <th>No. Penerimaan</th>
                        <th>Id Pembelian</th>
                        <th>Tanggal Terima</th>
                        <th>No. Pembelian</th>
                        <th>Gudang</th>
                        <th>Diterima Oleh</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Updatet By</th>
                        <th>Updatet At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penerimaans as $penerimaan)
                        <tr>
                            {{-- <td>{{ $penerimaan->id }}</td> --}}
                            <td>{{ $penerimaan->no_penerimaan }}</td>
                            <td>{{ $penerimaan->pembelian_id }}</td>
                            <td>{{ $penerimaan->tanggal_terima }}</td>
                            <td>{{ $penerimaan->pembelian->no_po ?? 'N/A' }}</td>
                            <td>{{ $penerimaan->gudang->nama_gudang ?? 'N/A' }}</td>
                            <td>{{ $penerimaan->diterima_oleh }}</td>
                            <td>{{ $penerimaan->creator->name ?? '-' }}</td>
                            <td>{{ $penerimaan->created_at }}</td>
                            <td>{{ $penerimaan->updater->name ?? '-' }}</td>
                            <td>{{ $penerimaan->updated_at }}</td>
                            <td>
                                @if ($penerimaan->tanggal_terima != null)
                                    {{-- <span class="badge badge-success">Selesai</span> --}}
                                 <button wire:click="show({{ $penerimaan->id }})" class="btn btn-sm btn-secondary">Show</button>
                                @else
                                <button wire:click="edit({{ $penerimaan->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $penerimaan->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>                               
                                 @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $penerimaans->links() }}
        </div>
    </div>
</div> 