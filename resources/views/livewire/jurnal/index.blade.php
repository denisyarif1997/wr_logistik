<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Jurnal</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Cari No Jurnal atau Keterangan...">
                </div>
            </div>

            <table class="table table-bordered table-sm">
                <thead class="table">
                    <tr>
                        <th>No</th>
                        <th>No Jurnal</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Referensi ID</th>
                        <th>Referensi Tipe</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jurnals as $index => $row)
                        <tr>
                            <td>{{ $jurnals->firstItem() + $index }}</td>
                            <td>{{ $row->no_jurnal }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $row->keterangan }}</td>
                            <td>{{ $row->referensi_id ?? '-' }}</td>
                            <td>{{ $row->referensi_tipe ?? '-' }}</td>
                            <td>{{ $row->kode_akun ?? '-' }}</td>
                            <td>{{ $row->nama_akun ?? '-' }}</td>
                            <td class="text-end">{{ number_format($row->debit ?? 0, 2) }}</td>
                            <td class="text-end">{{ number_format($row->kredit ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Data jurnal tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $jurnals->links() }}
            </div>
        </div>
    </div>
</div>
