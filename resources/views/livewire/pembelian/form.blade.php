<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            @if($isShow)
                View Pembelian
            @elseif($pembelian_id)
                Edit Pembelian
            @else
                Create Pembelian
            @endif
        </h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            {{-- Master Fields --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="no_po">No. PO</label>
                        <input type="text" class="form-control" id="no_po" wire:model.lazy="no_po" @if($isShow) readonly @endif>
                        @error('no_po') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal_po">Tanggal PO</label>
                        <input type="date" class="form-control" id="tanggal_po" wire:model.lazy="tanggal_po" @if($isShow) readonly @endif>
                        @error('tanggal_po') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group position-relative">
                        <label for="supplier_id">Supplier</label>
                        @if($isShow)
                            <input type="text" class="form-control" value="{{ $supplierSearch }}" readonly>
                        @else
                            <input type="text" class="form-control" placeholder="Cari Supplier..." 
                                   wire:model.live="supplierSearch" autocomplete="off">
                            <input type="hidden" wire:model="supplier_id">
                            
                            @if(!empty($supplierSearch) && !empty($suppliers) && $supplier_id == null)
                                <div class="list-group position-absolute w-100 shadow" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                    @foreach($suppliers as $supplier)
                                        <button type="button" class="list-group-item list-group-item-action py-2"
                                                wire:click="selectSupplier({{ $supplier->id }}, '{{ $supplier->nama_supplier }}')">
                                            {{ $supplier->nama_supplier }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                        @error('supplier_id') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" wire:model.lazy="status" @if($isShow) disabled @endif>
                            <option value="draft">Draft</option>
                            @if($pembelian_id)
                                <option value="approved">Approved</option>
                                <option value="canceled">Canceled</option>
                            @endif
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span>@enderror
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
                        <th>Harga Satuan</th>
                        <th>Diskon (@)</th>
                        <th>PPN (%)</th>
                        <th>Subtotal</th>
                        @if(!$isShow)
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $index => $detail)
                    <tr>
                        <td style="width: 300px;">
                            <div class="position-relative">
                                @if($isShow)
                                    <input type="text" class="form-control shadow-none border-0 bg-transparent" value="{{ $barangSearch[$index] ?? '' }}" readonly>
                                @else
                                    <input type="text" class="form-control" placeholder="Cari Barang..." 
                                           wire:model.live="barangSearch.{{ $index }}" autocomplete="off">
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
                        <td>
                            <input type="number" wire:model.lazy="details.{{$index}}.qty" class="form-control" min="1" @if($isShow) readonly @endif>
                            @error('details.'.$index.'.qty') <span class="text-danger">{{ $message }}</span>@enderror
                        </td>
                        <td>
                            <input type="number" wire:model.lazy="details.{{$index}}.harga_satuan" class="form-control" min="0" @if($isShow) readonly @endif>
                            @error('details.'.$index.'.harga_satuan') <span class="text-danger">{{ $message }}</span>@enderror
                        </td>
                        <td>
                            <input type="number" wire:model.lazy="details.{{$index}}.diskon" class="form-control" min="0" @if($isShow) readonly @endif>
                            @error('details.'.$index.'.diskon') <span class="text-danger">{{ $message }}</span>@enderror
                        </td>
                        <td>
                            <input type="number" wire:model.lazy="details.{{$index}}.ppn" class="form-control" min="0" @if($isShow) readonly @endif>
                            @error('details.'.$index.'.ppn') <span class="text-danger">{{ $message }}</span>@enderror
                        </td>
                        <td>
                            <input type="text" readonly class="form-control" value="Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}">
                        </td>
                        @if(!$isShow)
                            <td>
                                <button type="button" wire:click="removeDetail({{ $index }})" class="btn btn-danger btn-sm">Remove</button>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                {{-- Tambahkan footer untuk total --}}
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Subtotal (Termasuk PPN Item):</strong></td>
                        <td colspan="{{ $isShow ? '1' : '2' }}">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right" 
                                       value="{{ number_format($this->subTotal, 0, ',', '.') }}" readonly>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Diskon (Global):</strong></td>
                        <td colspan="{{ $isShow ? '1' : '2' }}">
                            <input type="number" wire:model.lazy="diskon" class="form-control text-right" min="0" @if($isShow) readonly @endif>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>PPN (Global):</strong></td>
                        <td colspan="{{ $isShow ? '1' : '2' }}">
                            <div class="input-group">
                                <input type="number" wire:model.lazy="ppn" class="form-control text-right" min="0" @if($isShow) readonly @endif>
                                @if(!$isShow)
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Hitung PPN
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" wire:click.prevent="calculateGlobalPPNWithRate(10)">10%</a>
                                            <a class="dropdown-item" href="#" wire:click.prevent="calculateGlobalPPNWithRate(11)">11%</a>
                                            <a class="dropdown-item" href="#" wire:click.prevent="calculateGlobalPPNWithRate(12)">12%</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" wire:click.prevent="calculateGlobalPPNWithRate(0)">Reset (0%)</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Biaya Lain-lain:</strong></td>
                        <td colspan="{{ $isShow ? '1' : '2' }}">
                            <input type="number" wire:model.lazy="biaya_lain" class="form-control text-right" min="0" @if($isShow) readonly @endif>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Grand Total:</strong></td>
                        <td colspan="{{ $isShow ? '1' : '2' }}">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control font-weight-bold text-success text-right" 
                                       value="{{ number_format($this->total, 0, ',', '.') }}" readonly>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

            @if(!$isShow)
                <button type="button" wire:click="addDetail" class="btn btn-success btn-sm mt-3">Add Barang</button>
            @endif

            <hr>

            @if(!$isShow)
                <button type="submit" class="btn btn-primary">{{ $pembelian_id ? 'Update' : 'Save' }}</button>
            @endif

            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div>