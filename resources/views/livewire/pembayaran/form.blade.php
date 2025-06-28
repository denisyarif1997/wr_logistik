<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            @if($isShow)
                View Pembayaran
            @elseif($pembayaran_id)
                Edit Pembayaran #{{ $pembayaran_id }}
            @else
                Create Pembayaran
            @endif
        </h3>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="form-group">
                <label for="penerimaan_id">Pilih Penerimaan</label>
                <select class="form-control" id="penerimaan_id" wire:model="penerimaan_id" @if($isShow) disabled @endif>
                    <option value="">Select Penerimaan</option>
                    @foreach($penerimaans as $penerimaan)
                        <option value="{{ $penerimaan->id }}">
                           Id Penerimaan : {{ $penerimaan->id }} - No Faktur : {{ $penerimaan->no_penerimaan }} - Tanggal Terima : {{ $penerimaan->tanggal_terima }} - Gudang : {{ $penerimaan->gudang->nama_gudang ?? '-' }}
                        </option>
                    @endforeach
                </select>
                @error('penerimaan_id') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="tanggal_bayar">Tanggal Bayar</label>
                <input type="date" class="form-control" id="tanggal_bayar" wire:model="tanggal_bayar" @if($isShow) readonly @endif>
                @error('tanggal_bayar') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="jumlah_bayar">Jumlah Bayar</label>
                <input type="number" step="0.01" class="form-control" id="jumlah_bayar" placeholder="Masukkan jumlah bayar" wire:model="jumlah_bayar" @if($isShow) readonly @endif>
                @error('jumlah_bayar') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="metode_bayar">Metode Bayar</label>
                <input type="text" class="form-control" id="metode_bayar" placeholder="Contoh: tunai, transfer" wire:model="metode_bayar" @if($isShow) readonly @endif>
                @error('metode_bayar') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control" id="keterangan" wire:model="keterangan" @if($isShow) readonly @endif></textarea>
                @error('keterangan') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="status">Status Pembayaran</label>
                <select class="form-control" id="status" wire:model="status" @if($isShow) disabled @endif>
                    <option value="pending">Pending</option>
                    <option value="lunas">Lunas</option>
                    <option value="gagal">Gagal</option>
                </select>
                @error('status') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            @unless($isShow)
                <button type="submit" class="btn btn-primary">{{ $pembayaran_id ? 'Update' : 'Save' }}</button>
            @endunless

            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div>
