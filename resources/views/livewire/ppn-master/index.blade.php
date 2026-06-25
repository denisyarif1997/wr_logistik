<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Master PPN</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" wire:click="create">
                    <i class="fas fa-plus"></i> Tambah PPN
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kode PPN</th>
                        <th>Rate (%)</th>
                        <th>Keterangan</th>
                        <th>Tanggal Berlaku</th>
                        <th>Status</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ppnMasters as $ppn)
                    <tr>
                        <td><strong>{{ $ppn->kode_ppn }}</strong></td>
                        <td>{{ $ppn->rate }}%</td>
                        <td>{{ $ppn->keterangan ?? '-' }}</td>
                        <td>{{ $ppn->tanggal_berlaku ? $ppn->tanggal_berlaku->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($ppn->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" wire:click="edit({{ $ppn->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-info btn-sm" wire:click="toggleActive({{ $ppn->id }})" title="{{ $ppn->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="fas fa-{{ $ppn->is_active ? 'times' : 'check' }}"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" wire:click="delete({{ $ppn->id }})" onclick="confirmDelete({{ $ppn->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data PPN Master</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $ppnMasters->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($isOpen)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $ppn_master_id ? 'Edit PPN Master' : 'Tambah PPN Master' }}</h5>
                    <button type="button" class="close" wire:click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="kode_ppn">Kode PPN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode_ppn" wire:model="kode_ppn" placeholder="Contoh: PPN_10">
                            @error('kode_ppn') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="rate">Rate (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="rate" wire:model="rate" step="0.01" min="0" max="100" placeholder="Contoh: 10">
                            @error('rate') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" wire:model="keterangan" rows="3" placeholder="Deskripsi PPN..."></textarea>
                            @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="tanggal_berlaku">Tanggal Berlaku</label>
                            <input type="date" class="form-control" id="tanggal_berlaku" wire:model="tanggal_berlaku">
                            @error('tanggal_berlaku') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" wire:model="is_active">
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                            @error('is_active') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="store">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" style="display: block;"></div>
    @endif

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(id);
                }
            });
        }
    </script>
</div>