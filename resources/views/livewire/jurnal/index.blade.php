<div>
    @if($isOpen)
        @include('livewire.jurnal.form')
    @else
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Jurnal</h3>
                <button wire:click="create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Buat Jurnal Manual
                </button>
            </div>
            <div class="card-body">
                <div class="row align-items-end mb-3">
                    <div class="col-md-3">
                        <label class="mb-1 small text-muted"><i class="far fa-calendar-alt mr-1"></i> Start Date</label>
                        <input wire:model.live="startDate" type="date" class="form-control form-control-sm shadow-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="mb-1 small text-muted"><i class="far fa-calendar-check mr-1"></i> End Date</label>
                        <input wire:model.live="endDate" type="date" class="form-control form-control-sm shadow-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="mb-1 small text-muted"><i class="fas fa-search mr-1"></i> Search</label>
                        <div class="input-group input-group-sm shadow-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text" class="form-control border-left-0" placeholder="Cari No Jurnal atau Keterangan...">
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>No Jurnal</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Referensi</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jurnals as $jurnal)
                            <tr>
                                <td>{{ $jurnal->no_jurnal }}</td>
                                <td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d-m-Y') }}</td>
                                <td>
                                    {{ $jurnal->keterangan }}
                                    <br>
                                    <small class="text-muted">
                                        @foreach($jurnal->details as $detail)
                                            {{ $detail->akun->nama_akun ?? '-' }} ({{ number_format($detail->debit > 0 ? $detail->debit : $detail->kredit) }}), 
                                        @endforeach
                                    </small>
                                </td>
                                <td>{{ $jurnal->referensi_tipe ?? 'Manual' }} #{{ $jurnal->referensi_id }}</td>
                                <td class="text-right">{{ number_format($jurnal->details->sum('debit'), 2) }}</td>
                                <td class="text-center">
                                    <button wire:click="edit({{ $jurnal->id }})" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="delete({{ $jurnal->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus jurnal ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data jurnal tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $jurnals->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
