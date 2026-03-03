<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $pemakaian_id ? 'Edit Pemakaian' : 'Create Pemakaian' }}</h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            {{-- Master Fields --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="no_pemakaian">No. Pemakaian</label>
                        <input type="text" class="form-control" id="no_pemakaian" wire:model.lazy="no_pemakaian">
                        @error('no_pemakaian') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal_pakai">Tanggal Pakai</label>
                        <input type="date" class="form-control" id="tanggal_pakai" wire:model.lazy="tanggal_pakai">
                        @error('tanggal_pakai') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="departemen_id">Departemen</label>
                        <select wire:model="departemen_id" id="departemen_id" class="form-control">
                            <option value="">Select Departemen</option>
                            @foreach($departemens as $departemen)
                                <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option>
                            @endforeach
                        </select>
                        @error('departemen_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="gudang_id">Gudang</label>
                        <select wire:model="gudang_id" id="gudang_id" class="form-control">
                            <option value="">Select Gudang</option>
                            @foreach($gudangs as $gudang)
                                <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                            @endforeach
                        </select>
                        @error('gudang_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="diajukan_oleh">Diajukan Oleh</label>
                        <input type="text" class="form-control" id="diajukan_oleh" wire:model.lazy="diajukan_oleh">
                        @error('diajukan_oleh') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <hr>

            {{-- Detail Fields --}}
            <h4 class="mb-3">Detail Barang</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $index => $detail)
                    <tr>
                        <td style="width: 300px;">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Cari Barang..." 
                                       wire:model.live="barangSearch.{{ $index }}" autocomplete="off">
                                <input type="hidden" wire:model="details.{{ $index }}.barang_id">
                                
                                @if(!empty($barangSearch[$index]) && isset($barangResults[$index]) && !empty($barangResults[$index]) && empty($details[$index]['barang_id']))
                                    <div class="list-group position-absolute w-100 shadow" style="z-index: 1000; max-height: 150px; overflow-y: auto;">
                                        @foreach($barangResults[$index] as $barang)
                                            <button type="button" class="list-group-item list-group-item-action py-1 px-2 small d-flex justify-content-between align-items-center"
                                                    wire:click="selectBarang({{ $index }}, {{ $barang->id }}, '{{ $barang->nama_barang }}')">
                                                <span>{{ $barang->nama_barang }}</span>
                                                <small class="text-muted">Stok: {{ $barang->stok->sum('stok_akhir') }}</small>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                                @error('details.'.$index.'.barang_id') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </td>
                        <td>
                            <input type="number" wire:model.lazy="details.{{$index}}.qty" class="form-control" min="1">
                             @error('details.'.$index.'.qty') <span class="text-danger">{{ $message }}</span>@enderror
                        </td>
                        <td>
                            <button type="button" wire:click="removeDetail({{$index}})" class="btn btn-danger btn-sm">Remove</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" wire:click="addDetail" class="btn btn-success btn-sm mt-3">Add Barang</button>
            
            <hr>

            <button type="submit" class="btn btn-primary">{{ $pemakaian_id ? 'Update' : 'Save' }}</button>
            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div> 