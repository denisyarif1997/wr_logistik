<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            @if($jurnal_id)
                Edit Jurnal #{{ $no_jurnal }}
            @else
                Create Jurnal Baru
            @endif
        </h3>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="{{ $jurnal_id ? 'update' : 'store' }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="no_jurnal">No Jurnal</label>
                        <input type="text" class="form-control" id="no_jurnal" wire:model="no_jurnal" readonly>
                        @error('no_jurnal') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" wire:model="tanggal">
                        @error('tanggal') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" wire:model="keterangan">
                        @error('keterangan') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5>Detail Jurnal</h5>
            
            @error('balance') 
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Akun</th>
                        <th width="20%">Debit</th>
                        <th width="20%">Kredit</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $index => $detail)
                    <tr>
                        <td>
                            <select class="form-control form-control-sm" wire:model="details.{{ $index }}.akun_id">
                                <option value="">Pilih Akun</option>
                                @foreach($akuns as $akun)
                                    <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                @endforeach
                            </select>
                            @error('details.'.$index.'.akun_id') <span class="text-danger small">{{ $message }}</span>@enderror
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm text-right" wire:model="details.{{ $index }}.debit">
                            @error('details.'.$index.'.debit') <span class="text-danger small">{{ $message }}</span>@enderror
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm text-right" wire:model="details.{{ $index }}.kredit">
                            @error('details.'.$index.'.kredit') <span class="text-danger small">{{ $message }}</span>@enderror
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger" wire:click="removeDetail({{ $index }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <button type="button" class="btn btn-sm btn-success" wire:click="addDetail">
                                <i class="fas fa-plus"></i> Tambah Baris
                            </button>
                        </td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td class="text-right">Total</td>
                        <td class="text-right">
                            {{ number_format(collect($details)->sum('debit'), 2) }}
                        </td>
                        <td class="text-right">
                            {{ number_format(collect($details)->sum('kredit'), 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
            </div>
        </form>
    </div>
</div>
