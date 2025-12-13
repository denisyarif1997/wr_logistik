<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stok Barang</h3>
        </div>
        <div class="card-body">
             <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Gudang</label>
                    <select wire:model="gudangFilter" class="form-control">
                        <option value="">Semua Gudang</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nama Barang</label>
                    <input wire:model.defer="namaFilter" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Cari nama barang...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pencarian Umum</label>
                    <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Cari kode/nama barang...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button wire:click="$refresh" class="btn btn-primary w-100" type="button">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>


            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Gudang</th>
                        <th class="text-right">Stok</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stoks as $stok)
                        <tr>
                            <td>{{ $stok->barang->kode_barang ?? 'N/A' }}</td>
                            <td>{{ $stok->barang->nama_barang ?? 'N/A' }}</td>
                            <td>{{ $stok->gudang->nama_gudang ?? 'N/A' }}</td>
                            <td class="text-right">{{ number_format($stok->total_qty, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <button wire:click="viewHistory({{ $stok->barang_id }}, {{ $stok->gudang_id }})" class="btn btn-sm btn-info">
                                    <i class="fas fa-history"></i> History
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $stoks->links() }}
        </div>
    </div>

    {{-- History Modal --}}
    @if($showHistoryModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Kartu Stok: {{ $selectedBarang->nama_barang ?? '-' }} 
                        <small class="text-muted">({{ $selectedGudang->nama_gudang ?? '-' }})</small>
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeHistory"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Transaksi</th>
                                <th>Ref</th>
                                <th class="text-right">Masuk</th>
                                <th class="text-right">Keluar</th>
                                <th class="text-right">Saldo</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $row)
                                <tr>
                                    <td>{{ $row->tanggal->format('d-m-Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $row->jenis_transaksi == 'masuk' ? 'success' : 'danger' }}">
                                            {{ ucfirst($row->jenis_transaksi) }}
                                        </span>
                                    </td>
                                    <td>{{ $row->referensi_tipe }} #{{ $row->referensi_id }}</td>
                                    <td class="text-right">{{ $row->qty_masuk > 0 ? number_format($row->qty_masuk, 0) : '-' }}</td>
                                    <td class="text-right">{{ $row->qty_keluar > 0 ? number_format($row->qty_keluar, 0) : '-' }}</td>
                                    <td class="text-right fw-bold">{{ number_format($row->stok_akhir, 0) }}</td>
                                    <td>{{ $row->keterangan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada riwayat transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeHistory">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
