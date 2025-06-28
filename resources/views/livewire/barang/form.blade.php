    <div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $barang_id ? 'Edit Barang' : 'Create Barang' }}</h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="form-group">
                <label for="kode_barang">Kode Barang</label>
                <input type="text" class="form-control" id="kode_barang" placeholder="Enter Kode Barang" wire:model="kode_barang">
                @error('kode_barang') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" class="form-control" id="nama_barang" placeholder="Enter Nama Barang" wire:model="nama_barang">
                @error('nama_barang') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="satuan">Satuan</label>
                <select wire:model="satuan" class="form-control">
                    <option value="">Select Satuan</option>
                    @foreach ($satuans as $satuan)
                        <option value="{{ $satuan->id }}">{{ $satuan->kode_satuan }}</option>
                    @endforeach
                </select>
                @error('satuan') <span class="text-danger">{{ $message }}</span>@enderror

            </div>
            <div class="form-group">
                <label for="stok_minimum">Stok Minimum</label>
                <input type="number" class="form-control" id="stok_minimum" placeholder="Enter Stok Minimum" wire:model="stok_minimum">
                @error('stok_minimum') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="harga_beli_terakhir">Harga Beli Terakhir</label>
                <input type="number" class="form-control" id="harga_beli_terakhir" placeholder="Enter Harga Beli Terakhir" wire:model="harga_beli_terakhir">
                @error('harga_beli_terakhir') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">{{ $barang_id ? 'Update' : 'Save' }}</button>
            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div> 