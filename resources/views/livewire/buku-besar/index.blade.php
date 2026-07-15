<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Buku Besar (General Ledger)</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end mb-3">
                <div class="col-md-3">
                    <label class="mb-1 small text-muted"><i class="far fa-calendar-alt mr-1"></i> Start Date</label>
                    <input wire:model="startDate" type="date" class="form-control form-control-sm shadow-sm">
                </div>
                <div class="col-md-3">
                    <label class="mb-1 small text-muted"><i class="far fa-calendar-check mr-1"></i> End Date</label>
                    <input wire:model="endDate" type="date" class="form-control form-control-sm shadow-sm">
                </div>
                <div class="col-md-4">
                    <label class="mb-1 small text-muted"><i class="fas fa-list-alt mr-1"></i> Akun</label>
                    <select wire:model="akun_id" class="form-control form-control-sm shadow-sm">
                        <option value="">Pilih Akun</option>
                        @foreach($akuns as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm" wire:click="$refresh">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secondary btn-sm" wire:click="resetFilter">
                        <i class="fas fa-sync"></i> Reset
                    </button>
                </div>
            </div>

            @if($akun_id)
                @php
                    $selectedAkun = $akuns->find($akun_id);
                @endphp
                
                <div class="alert alert-info">
                    <strong>Akun:</strong> {{ $selectedAkun->kode_akun }} - {{ $selectedAkun->nama_akun }} ({{ $selectedAkun->tipe }})
                </div>

                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th width="10%">Tanggal</th>
                            <th width="10%">No Jurnal</th>
                            <th>Keterangan</th>
                            <th width="15%" class="text-right">Debit</th>
                            <th width="15%" class="text-right">Kredit</th>
                            <th width="15%" class="text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="font-weight-bold bg-light">
                            <td colspan="3">Saldo Awal</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">{{ number_format($saldoAwal, 2) }}</td>
                        </tr>
                        
                        @php $runningSaldo = $saldoAwal; @endphp
                        @foreach($bukuBesarData as $detail)
                            @php
                                $runningSaldo += $detail->debit - $detail->kredit;
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($detail->jurnal->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ $detail->jurnal->no_jurnal }}</td>
                                <td>{{ $detail->jurnal->keterangan }}</td>
                                <td class="text-right">{{ $detail->debit > 0 ? number_format($detail->debit, 2) : '-' }}</td>
                                <td class="text-right">{{ $detail->kredit > 0 ? number_format($detail->kredit, 2) : '-' }}</td>
                                <td class="text-right">{{ number_format($runningSaldo, 2) }}</td>
                            </tr>
                        @endforeach
                        
                        @if($bukuBesarData->count() > 0)
                            <tr class="font-weight-bold bg-light">
                                <td colspan="3">Total</td>
                                <td class="text-right">{{ number_format($totalDebit, 2) }}</td>
                                <td class="text-right">{{ number_format($totalKredit, 2) }}</td>
                                <td class="text-right">{{ number_format($runningSaldo, 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">
                    Silakan pilih akun untuk menampilkan data buku besar.
                </div>
            @endif
        </div>
    </div>
</div>