@if ($isOpen)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $jurnal_id ? 'Edit Jurnal' : 'Tambah Jurnal' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>No Jurnal</label>
                        <input type="text" wire:model="no_jurnal" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Tanggal</label>
                        <input type="date" wire:model="tanggal" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Keterangan</label>
                        <textarea wire:model="keterangan" class="form-control"></textarea>
                    </div>

                    {{-- Detail Jurnal --}}
                    <h6>Detail Jurnal</h6>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Kredit</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $i => $detail)
                                <tr>
                                    <td>
                                        <select wire:model="details.{{ $i }}.akun_id" class="form-select">
                                            <option value="">-- pilih akun --</option>
                                            @foreach ($allAkun as $akun)
                                                <option value="{{ $akun->id }}">
                                                    {{ $akun->kode_akun }} - {{ $akun->nama_akun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" wire:model="details.{{ $i }}.debit"
                                            class="form-control text-end">
                                    </td>
                                    <td>
                                        <input type="number" wire:model="details.{{ $i }}.kredit"
                                            class="form-control text-end">
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm"
                                            wire:click="removeDetail({{ $i }})">x</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-secondary btn-sm" wire:click="addDetail">+ Tambah Baris</button>
                </div>
                <div class="modal-footer">
                    <button wire:click="store" class="btn btn-success">Simpan</button>
                    <button wire:click="closeModal" class="btn btn-secondary">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endif
