<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Jurnal</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search by journal number or description...">
                </div>
            </div>

            @foreach ($jurnals as $jurnal)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">{{ $jurnal->no_jurnal }}</h5>
                    <div class="card-tools">
                        <span class="badge badge-info">{{ $jurnal->tanggal }}</span>
                        <span class="badge badge-secondary">{{ $jurnal->referensi_tipe }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>Keterangan:</strong> {{ $jurnal->keterangan }}</p>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jurnal->details as $detail)
                            <tr>
                                <td>{{ $detail->akun->kode_akun ?? '' }} - {{ $detail->akun->nama_akun ?? 'N/A' }}</td>
                                <td class="text-right">{{ number_format($detail->debit, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->kredit, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach

            {{ $jurnals->links() }}
        </div>
    </div>
</div> 