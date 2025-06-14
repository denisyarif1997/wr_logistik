<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $akun_id ? 'Edit Akun' : 'Create Akun' }}</h3>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="form-group">
                <label for="kode_akun">Kode Akun</label>
                <input type="text" class="form-control" id="kode_akun" placeholder="Enter Kode Akun" wire:model="kode_akun">
                @error('kode_akun') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="nama_akun">Nama Akun</label>
                <input type="text" class="form-control" id="nama_akun" placeholder="Enter Nama Akun" wire:model="nama_akun">
                @error('nama_akun') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="tipe">Tipe</label>
                <select class="form-control" id="tipe" wire:model="tipe">
                    <option value="aset">Aset</option>
                    <option value="liabilitas">Liabilitas</option>
                    <option value="ekuitas">Ekuitas</option>
                    <option value="pendapatan">Pendapatan</option>
                    <option value="beban">Beban</option>
                </select>
                @error('tipe') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">{{ $akun_id ? 'Update' : 'Save' }}</button>
            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div> 