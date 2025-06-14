<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $departemen_id ? 'Edit Departemen' : 'Create Departemen' }}</h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="form-group">
                <label for="nama_departemen">Nama Departemen</label>
                <input type="text" class="form-control" id="nama_departemen" placeholder="Enter Nama Departemen" wire:model="nama_departemen">
                @error('nama_departemen') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">{{ $departemen_id ? 'Update' : 'Save' }}</button>
            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div> 