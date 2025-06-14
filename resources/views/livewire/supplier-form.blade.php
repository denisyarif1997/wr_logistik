<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $supplier_id ? 'Edit Supplier' : 'Create Supplier' }}</h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter name" wire:model="nama_supplier">
                @error('nama_supplier') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" id="alamat" placeholder="Enter alamat" wire:model="alamat"></textarea>
                @error('alamat') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="telepon">telepon</label>
                <input type="number" class="form-control" id="telepon" placeholder="Enter telepon" wire:model="telepon">
                @error('telepon') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" wire:model="email">
                @error('email') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="npwp">NPWP</label>
                <input type="text" class="form-control" id="npwp" placeholder="Enter NPWP" wire:model="npwp">
                @error('npwp') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">{{ $supplier_id ? 'Update' : 'Save' }}</button>
            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div> 