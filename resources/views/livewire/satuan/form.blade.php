    <div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $satuan_id ? 'Edit Satuan' : 'Create Satuan' }}</h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="form-group">
                <label for="kode_satuan">Kode Satuan</label>
                <input type="text" class="form-control" id="kode_satuan" placeholder="Enter Kode Satuan" wire:model="kode_satuan">
                @error('kode_satuan') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            {{-- <div class="form-group">
                <label for="nama_satuan">Nama Satuan</label>
                <input type="text" class="form-control" id="nama_satuan" placeholder="Enter Nama Satuan" wire:model="nama_satuan">
                @error('nama_satuan') <span class="text-danger">{{ $message }}</span>@enderror
            </div> --}}
            {{-- <div class="form-group">
                <label for="satuan">Satuan</label>
                <input type="text" class="form-control" id="satuan" placeholder="Enter Satuan" wire:model="satuan">
                @error('satuan') <span class="text-danger">{{ $message }}</span>@enderror
            </div> --}}
            <button type="submit" class="btn btn-primary">{{ $satuan_id ? 'Update' : 'Save' }}</button>
            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div> 