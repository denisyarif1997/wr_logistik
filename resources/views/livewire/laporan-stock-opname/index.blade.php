<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Stock Opname</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="date" wire:model="startDate" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <input type="date" wire:model="endDate" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <select wire:model="gudang_id" class="form-control form-control-sm">
                        <option value="">Semua Gudang</option>
                        @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
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
                            <th>Barang</th>
                            <th>Qty Sistem</th>
                            <th>Qty Fisik</th>
                            <th>Selisih</th>
                            <th>Total Selisih</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockOpnames as $opname)
                        @foreach($opname->details as $detail)
                        <tr>
                            <td>{{ $opname->no_opname }}</td>
                            <td>{{ $opname->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $opname->gudang->nama_gudang ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $opname->jenis === 'tambah' ? 'success' : ($opname->jenis === 'kurang' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($opname->jenis) }}
                                </span>
                            </td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->qty_sistem }}</td>
                            <td>{{ $detail->qty_fisik }}</td>
                            <td class="{{ $detail->selisih >= 0 ? 'text-success' : 'text-danger' }}">{{ $detail->selisih }}</td>
                            <td>Rp {{ number_format($detail->total_selisih, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $opname->status === 'approved' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($opname->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $stockOpnames->links() }}
        </div>
    </div>
</div>