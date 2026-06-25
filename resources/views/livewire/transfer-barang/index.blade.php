<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-exchange-alt mr-2"></i>Transfer Barang</h3>
            <div class="card-tools">
                @if($showForm)
                    <button wire:click="$set('showForm', false)" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                @else
                    <button wire:click="create" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Tambah Transfer
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-primary card-outline mb-3">
                    <div class="card-header">
                        <h3 class="card-title">{{ $isEdit ? 'Edit Transfer' : 'Tambah Transfer Baru' }}</h3>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <!-- No Transfer -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No. Transfer <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="no_transfer" class="form-control" placeholder="Generate Otomatis" readonly>
                                        
                                        @error('no_transfer') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Tanggal Transfer -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Transfer <span class="text-danger">*</span></label>
                                        <input type="date" wire:model="tanggal_transfer" class="form-control">
                                        @error('tanggal_transfer') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Gudang Asal -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gudang Asal <span class="text-danger">*</span></label>
                                        <select wire:model="gudang_asal_id" class="form-control">
                                            <option value="">-- Pilih Gudang Asal --</option>
                                            @foreach($gudangList as $gudang)
                                                <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                                            @endforeach
                                        </select>
                                        @error('gudang_asal_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Gudang Tujuan -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gudang Tujuan <span class="text-danger">*</span></label>
                                        <select wire:model="gudang_tujuan_id" class="form-control">
                                            <option value="">-- Pilih Gudang Tujuan --</option>
                                            @foreach($gudangList as $gudang)
                                                <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                                            @endforeach
                                        </select>
                                        @error('gudang_tujuan_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Keterangan -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <textarea wire:model="keterangan" rows="2" class="form-control" placeholder="Keterangan transfer..."></textarea>
                                        @error('keterangan') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="mb-0">Detail Barang</h4>
                                        <button type="button" wire:click="addDetail" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus mr-1"></i>Tambah Barang
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 35%">Barang</th>
                                                    <th style="width: 45%">Keterangan</th>
                                                    <th style="width: 15%">Qty</th>
                                                    <th style="width: 5%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($details as $index => $detail)
                                                    <tr>
                                                        <td>
                                                            <select wire:model="details.{{ $index }}.barang_id" class="form-control form-control-sm">
                                                                <option value="">-- Pilih Barang --</option>
                                                                @foreach($barangList as $barang)
                                                                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }} ({{ $barang->kode_barang }})</option>
                                                                @endforeach
                                                            </select>
                                                            @error('details.{{ $index }}.barang_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                                        </td>
                                                        <td>
                                                            <input type="text" wire:model="details.{{ $index }}.keterangan" class="form-control form-control-sm" placeholder="Keterangan...">
                                                        </td>
                                <td>
                                    <input type="number" step="0.01" wire:model="details.{{ $index }}.qty" class="form-control form-control-sm text-center" placeholder="0" min="0.01">
                                    @error('details.{{ $index }}.qty') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                    @if(isset($stokInfo[$detail['barang_id']]))
                                        <small class="text-muted">
                                            Stok Asal: {{ $stokInfo[$detail['barang_id']]['asal'] }} 
                                            @if($this->gudang_tujuan_id && $stokInfo[$detail['barang_id']]['tujuan'] !== null)
                                                | Stok Tujuan: {{ $stokInfo[$detail['barang_id']]['tujuan'] }}
                                            @endif
                                        </small>
                                    @endif
                                </td>
                                                        <td class="text-center">
                                                            @if(count($details) > 1)
                                                                <button type="button" wire:click="removeDetail({{ $index }})" class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @error('details') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Simpan
                                    </button>
                                    <button type="button" wire:click="$set('showForm', false)" class="btn btn-secondary">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
            @endif

            <!-- Search -->
            <div class="row align-items-end mb-3">
                <div class="col-md-12">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input wire:model.live="search" type="text" class="form-control" placeholder="Cari no. transfer...">
                        <div class="input-group-append">
                            <button wire:click="$refresh" class="btn btn-primary" type="button">
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No. Transfer</th>
                            <th>Tanggal</th>
                            <th>Gudang Asal</th>
                            <th>Gudang Tujuan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                            <tr>
                                <td><strong>{{ $transfer->no_transfer }}</strong></td>
                                <td>{{ $transfer->formatted_tanggal_transfer }}</td>
                                <td>{{ $transfer->gudangAsal->nama_gudang ?? '-' }}</td>
                                <td>{{ $transfer->gudangTujuan->nama_gudang ?? '-' }}</td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match($transfer->status) {
                                            'pending' => 'badge-warning',
                                            'selesai' => 'badge-success',
                                            'dibatalkan' => 'badge-danger',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ $transfer->status_label }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button wire:click="edit({{ $transfer->id }})" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        @if($transfer->status == 'pending')
                                            <button wire:click="proses({{ $transfer->id }})" wire:confirm="Apakah Anda yakin ingin memproses transfer ini? Stok akan dipindah." class="btn btn-success" title="Proses">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button wire:click="batalkan({{ $transfer->id }})" wire:confirm="Apakah Anda yakin ingin membatalkan transfer ini?" class="btn btn-danger" title="Batalkan">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada data transfer</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $transfers->links() }}
            </div>
        </div>
    </div>
</div>