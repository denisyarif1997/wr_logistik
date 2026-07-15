<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Penyesuaian Stok (Hilang / Rusak / Penyesuaian)</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">Penyesuaian Baru</button>
            </div>
        </div>
        <div class="card-body">
            @if($isOpen)
                @include('livewire.penyesuaian.form')
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
                        <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Cari No / Jenis / Gudang...">
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
                        <th>No Penyesuaian</th>
                        <th>Tanggal</th>
                        <th>Gudang</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                        <th>Action</th>
                        <th>Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penyesuaian as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->no_penyesuaian }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->gudang->nama_gudang ?? 'N/A' }}</td>
                            <td>
                                @if($item->jenis == 'hilang')
                                    <span class="badge bg-danger">Hilang</span>
                                @elseif($item->jenis == 'rusak')
                                    <span class="badge bg-warning">Rusak</span>
                                @else
                                    <span class="badge bg-info">Penyesuaian</span>
                                @endif
                            </td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ $item->creator->name ?? '-' }}</td>

                            <td>
                                @if(strtolower($item->status) === 'approved')
                                    <button wire:click="show({{ $item->id }})" class="btn btn-sm btn-secondary">Show</button>
                                @else
                                    <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-primary">Edit</button>
                                    <button wire:click="delete({{ $item->id }})" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?');">Delete</button>
                                @endif
                            </td>

                            @php
                                $status = strtolower($item->status ?? '');
                            @endphp
                            <td>
                                @switch($status)
                                    @case('draft')
                                        <button wire:click="validatePenyesuaian({{ $item->id }})" class="btn btn-success btn-sm">
                                            Approve
                                        </button>
                                        @break

                                    @case('approved')
                                        <button wire:click="unvalidasi({{ $item->id }})" class="btn btn-danger btn-sm">
                                            Unapprove
                                        </button>
                                        @break

                                    @default
                                        <span class="badge bg-warning">{{ ucfirst($item->status) }}</span>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $penyesuaian->links() }}
        </div>
    </div>
</div>