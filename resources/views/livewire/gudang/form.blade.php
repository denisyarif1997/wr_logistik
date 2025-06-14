<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $gudang_id ? 'Edit Gudang' : 'Create Gudang' }}</h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="form-group">
                <label for="nama_gudang">Nama Gudang</label>
                <input type="text" class="form-control" id="nama_gudang" placeholder="Enter Nama Gudang" wire:model="nama_gudang">
                @error('nama_gudang') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" placeholder="Enter Lokasi" wire:model="lokasi">
                @error('lokasi') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">{{ $gudang_id ? 'Update' : 'Save' }}</button>
            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div> 