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
                    <div class="form-group">
                        <label for="supplier_id">Supplier</label>
                        <select class="form-control" id="supplier_id" wire:model.lazy="supplier_id" @if($isShow) disabled @endif>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                            @endforeach
                        </select>
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
                                <option value="received">Received</option>
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
                        <th>Subtotal</th>
                        @if(!$isShow)
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $index => $detail)
                    <tr>
                        <td>
                            <select wire:model.lazy="details.{{$index}}.barang_id" class="form-control" @if($isShow) disabled @endif>
                                <option value="">Pilih Barang</option>
                                @foreach($allBarang as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                @endforeach
                            </select>
                            @error('details.'.$index.'.barang_id') <span class="text-danger">{{ $message }}</span>@enderror
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
                            <input type="text" readonly class="form-control" value="{{ $detail['subtotal'] }}">
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
                <button type="submit" class="btn btn-primary">{{ $pembelian_id ? 'Update' : 'Save' }}</button>
            @endif

            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div>
