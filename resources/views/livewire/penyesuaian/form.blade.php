<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            @if($isShow)
                View Penyesuaian Stok
            @elseif($penyesuaian_id)
                Edit Penyesuaian Stok
            @else
                Create Penyesuaian Stok
            @endif
        </h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="no_penyesuaian">No Penyesuaian</label>
                        <input type="text" class="form-control" id="no_penyesuaian" wire:model.lazy="no_penyesuaian" @if($isShow) readonly @endif>
                        @error('no_penyesuaian') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" wire:model.lazy="tanggal" @if($isShow) readonly @endif>
                        @error('tanggal') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="gudang_id">Gudang</label>
                        @if($isShow)
                            <span class="form-control-plaintext">{{ $gudangs->firstWhere('id', $gudang_id)->nama_gudang ?? '' }}</span>
                        @else
                            <select class="form-control" id="gudang_id" wire:model.live="gudang_id" @if($penyesuaian_id) disabled @endif>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($gudangs as $gudang)
                                    <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('gudang_id') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jenis">Jenis Penyesuaian</label>
                        <select class="form-control" id="jenis" wire:model.lazy="jenis" @if($isShow) disabled @endif>
                            <option value="penyesuaian">Penyesuaian</option>
                            <option value="hilang">Hilang</option>
                            <option value="rusak">Rusak</option>
                            <option value="retur">Retur</option>

                        </select>
                        @error('jenis') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" wire:model.lazy="keterangan" rows="2" @if($isShow) readonly @endif></textarea>
                        @error('keterangan') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <hr>

            <h4 class="mb-3">Detail Barang</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Keterangan</th>
                        @if(!$isShow)
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $index => $detail)
                    <tr>
                        <td style="width: 350px;">
                            <div class="position-relative">
                                @if($isShow)
                                    <input type="text" class="form-control shadow-none border-0 bg-transparent" value="{{ $barangSearch[$index] ?? '' }}" readonly>
                                @else
                                    <input type="text" class="form-control" placeholder="Cari Barang..." wire:model.live="barangSearch.{{ $index }}" autocomplete="off">
                                    <input type="hidden" wire:model="details.{{ $index }}.barang_id">

                                    @if(!empty($barangSearch[$index]) && isset($barangResults[$index]) && !empty($barangResults[$index]) && empty($details[$index]['barang_id']))
                                        <div class="list-group position-absolute w-100 shadow" style="z-index: 1000; max-height: 150px; overflow-y: auto;">
                                            @foreach($barangResults[$index] as $barang)
                                                <button type="button" class="list-group-item list-group-item-action py-1 px-2 small"
                                                        wire:click="selectBarang({{ $index }}, {{ $barang->id }}, '{{ $barang->nama_barang }}')">
                                                    {{ $barang->nama_barang }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                                @error('details.'.$index.'.barang_id') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </td>
                        <td style="width: 150px;">
                            <input type="number" step="0.01" wire:model.lazy="details.{{$index}}.qty" class="form-control" min="0.01" @if($isShow) readonly @endif>
                            @error('details.'.$index.'.qty') <span class="text-danger">{{ $message }}</span>@enderror
                        </td>
                        <td>
                            <input type="text" wire:model.lazy="details.{{$index}}.keterangan" class="form-control" @if($isShow) readonly @endif>
                        </td>
                        @if(!$isShow)
                            <td>
                                <button type="button" wire:click="removeDetail({{ $index }})" class="btn btn-danger btn-sm">Remove</button>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(!$isShow)
                <button type="button" wire:click="addDetail" class="btn btn-success btn-sm mt-3">Add Barang</button>
            @endif

            <hr>

            @if(!$isShow)
                <button type="submit" class="btn btn-primary">{{ $penyesuaian_id ? 'Update' : 'Save' }}</button>
            @endif

            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div>