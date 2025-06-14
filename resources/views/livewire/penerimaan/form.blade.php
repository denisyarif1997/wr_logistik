<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $penerimaan_id ? 'Edit Penerimaan' : 'Create Penerimaan' }}</h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="form-group">
                <label for="pembelian_id">No. Pembelian</label>
                <select class="form-control" id="pembelian_id" wire:model="pembelian_id">
                    <option value="">Select Pembelian</option>
                    @foreach($pembelians as $pembelian)
                        <option value="{{ $pembelian->id }}">Id Pembelian : {{ $pembelian->id }} - No Pembelian : {{ $pembelian->no_po }} - Tanggal : {{ $pembelian->created_at->format('d/m/Y') }}
                            - Supplier : {{ $pembelian->supplier->nama_supplier }}  - Status : {{ $pembelian->status }} 
                        </option>
                    @endforeach
                </select>
                @error('pembelian_id') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="no_penerimaan">No. Penerimaan</label>
                <input type="text" class="form-control" id="no_penerimaan" placeholder="Enter No. Penerimaan" wire:model="no_penerimaan">
                @error('no_penerimaan') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="tanggal_terima">Tanggal Terima</label>
                <input type="date" class="form-control" id="tanggal_terima" wire:model="tanggal_terima">
                @error('tanggal_terima') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
           
            <div class="form-group">
                <label for="gudang_id">Gudang</label>
                <select class="form-control" id="gudang_id" wire:model="gudang_id">
                    <option value="">Select Gudang</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
                @error('gudang_id') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="diterima_oleh">Diterima Oleh</label>
                <input type="text" class="form-control" id="diterima_oleh" placeholder="Enter Diterima Oleh" wire:model="diterima_oleh">
                @error('diterima_oleh') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">{{ $penerimaan_id ? 'Update' : 'Save' }}</button>
            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div> 