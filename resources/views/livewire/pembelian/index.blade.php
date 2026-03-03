<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Purchase Order</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">New Purchase Order</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.pembelian.form')
            @endif


            <div class="row align-items-end mb-3">
                <div class="col-md-3">
                    <label class="mb-1"><i class="far fa-calendar-alt mr-1"></i> Start Date</label>
                    <input wire:model="startDate" type="date" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="mb-1"><i class="far fa-calendar-check mr-1"></i> End Date</label>
                    <input wire:model="endDate" type="date" class="form-control form-control-sm">
                </div>
                <div class="col-md-4">
                    <label class="mb-1"><i class="fas fa-search mr-1"></i> Search</label>
                    <div class="input-group input-group-sm">
                        <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Search PO or Supplier...">
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
                        <th>Id</th>
                        <th>No PO</th>
                        <th>Tanggal PO</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Updated By</th>
                        <th>Updated At</th>
                        <th>Action</th>
                        <th>Validasi Pembelian</th>
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
                                @if(strtolower($pembelian->status) === 'approved')
                                    <button wire:click="show({{ $pembelian->id }})"
                                        class="btn btn-sm btn-secondary">Show</button>
                                @elseif(strtolower($pembelian->status) === 'received')
                                    <button wire:click="show({{ $pembelian->id }})"
                                        class="btn btn-sm btn-secondary">Show</button>
                                @else
                                    <button wire:click="edit({{ $pembelian->id }})" class="btn btn-sm btn-primary">Edit</button>
                                    <button wire:click="delete({{ $pembelian->id }})" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?');">Delete</button>
                                @endif
                                <a href="{{ route('admin.pembelian.print', $pembelian->id) }}" target="_blank" class="btn btn-sm btn-warning">Print</a>
                            </td>

                            <td>
                                @if(strtolower($pembelian->status) !== 'received')
                                    @if(strtolower($pembelian->status) === 'draft')
                                        <button wire:click="validatePembelian({{ $pembelian->id }})" class="btn btn-sm btn-success"
                                            onclick="return confirm('Yakin setujui pembelian ini?');">Approve</button>
                                    @elseif(strtolower($pembelian->status) === 'approved')
                                        <button wire:click="unvalidasi({{ $pembelian->id }})" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin batalkan approval?');">Unapprove</button>
                                    @endif
                                @else
                                    <button wire:click="show({{ $pembelian->id }})"
                                        class="btn btn-sm btn-secondary">Received</button>
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