<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Stock Opname</h3>
            <div class="card-tools">
                <button wire:click="create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Stock Opname
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="date" wire:model="startDate" class="form-control form-control-sm">
                </div>
                <div class="col-md-4">
                    <input type="date" wire:model="endDate" class="form-control form-control-sm">
                </div>
                <div class="col-md-4">
                    <input type="text" wire:model="search" class="form-control form-control-sm" placeholder="Cari...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>No Opname</th>
                            <th>Tanggal</th>
                            <th>Gudang</th>
                            <th>Jenis</th>
                            <th>Total Selisih</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockOpnames as $opname)
                        <tr>
                            <td>{{ $opname->no_opname }}</td>
                            <td>{{ $opname->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $opname->gudang->nama_gudang ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $opname->jenis === 'tambah' ? 'success' : ($opname->jenis === 'kurang' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($opname->jenis) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($opname->details->sum('total_selisih'), 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $opname->status === 'approved' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($opname->status) }}
                                </span>
                            </td>
                            <td>
                                <button wire:click="show({{ $opname->id }})" class="btn btn-info btn-xs">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($opname->status === 'draft')
                                <button wire:click="validateStockOpname({{ $opname->id }})" class="btn btn-success btn-xs" onclick="return confirm('Approve stock opname ini?')">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button wire:click="edit({{ $opname->id }})" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $opname->id }})" class="btn btn-danger btn-xs" onclick="return confirm('Yakin hapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                                @if($opname->status === 'approved')
                                <button wire:click="unvalidasi({{ $opname->id }})" class="btn btn-secondary btn-xs" onclick="return confirm('Batalkan approval?')">
                                    <i class="fas fa-undo"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $stockOpnames->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade {{ $isOpen ? 'show' : '' }}" tabindex="-1" style="{{ $isOpen ? 'display: block; background: rgba(0,0,0,0.5);' : '' }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isShow ? 'Detail' : ($stock_opname_id ? 'Edit' : 'Tambah') }} Stock Opname</h5>
                    <button type="button" class="close" wire:click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="store">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No Opname</label>
                                    <input type="text" wire:model="no_opname" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" wire:model="tanggal" class="form-control" {{ $isShow ? 'readonly' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gudang</label>
                                    <select wire:model="gudang_id" class="form-control" {{ $isShow ? 'disabled' : '' }}>
                                        <option value="">Pilih Gudang</option>
                                        @foreach($gudangs as $gudang)
                                        <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis</label>
                                    <select wire:model="jenis" class="form-control" {{ $isShow ? 'disabled' : '' }}>
                                        <option value="tambah">Tambah</option>
                                        <option value="kurang">Kurang</option>
                                        <option value="rusak">Rusak</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea wire:model="keterangan" class="form-control" {{ $isShow ? 'readonly' : '' }}></textarea>
                        </div>

                        <hr>
                        <h5>Detail Barang</h5>
                        
                        @if(!$isShow)
                        <div class="row mb-2">
                            <div class="col-12">
                                <button type="button" wire:click="addDetail" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Barang
                                </button>
                            </div>
                        </div>
                        @endif

                        @foreach($details as $index => $detail)
                        <div class="row mb-2 position-relative">
                            <div class="col-md-4">
                                <input type="text" wire:model.live="barangSearch.{{ $index }}" 
                                       class="form-control" placeholder="Cari barang..." 
                                       {{ $isShow ? 'readonly' : '' }}>
                                @if(!$isShow && isset($barangResults[$index]) && count($barangResults[$index]) > 0)
                                <div class="position-absolute" style="z-index: 1000; max-height: 200px; overflow-y: auto; background: #fff; border: 1px solid #ddd; width: calc(100% - 15px);">
                                    @foreach($barangResults[$index] as $barang)
                                    <a class="dropdown-item" href="#" wire:click="selectBarang({{ $index }}, {{ $barang->id }}, '{{ $barang->nama_barang }}')">
                                        {{ $barang->nama_barang }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <input type="number" wire:model="details.{{ $index }}.qty_sistem" 
                                       class="form-control" placeholder="Qty Sistem" readonly>
                            </div>
                            <div class="col-md-2">
                                <input type="number" wire:model="details.{{ $index }}.qty_fisik" 
                                       class="form-control" placeholder="Qty Fisik" 
                                       {{ $isShow ? 'readonly' : '' }} wire:change="calculateSelisih({{ $index }})">
                            </div>
                            <div class="col-md-2">
                                <input type="number" wire:model="details.{{ $index }}.selisih" 
                                       class="form-control" placeholder="Selisih" readonly>
                            </div>
                            <div class="col-md-2">
                                @if(!$isShow)
                                <button type="button" wire:click="removeDetail({{ $index }})" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        @if($isShow)
                        <div class="text-right">
                            <strong>Total Selisih: Rp {{ number_format(collect($details)->sum('total_selisih'), 0, ',', '.') }}</strong>
                        </div>
                        @endif

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                            @if(!$isShow)
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>