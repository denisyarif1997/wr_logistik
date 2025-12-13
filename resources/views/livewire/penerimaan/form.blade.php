<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            @if($isShow)
                View Penerimaan
            @elseif($penerimaan_id)
                Edit Penerimaan {{ $penerimaan_id }}
            @else
                Create Penerimaan
            @endif
        </h3>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pembelian_id">No. Pembelian</label>
                        <select class="form-control" id="pembelian_id" wire:model.live="pembelian_id" @if($isShow || $penerimaan_id) disabled @endif>
                            <option value="">Select Pembelian</option>
                            @foreach($pembelians as $pembelian)
                                <option value="{{ $pembelian->id }}">
                                    {{ $pembelian->no_po }} - {{ $pembelian->supplier->nama_supplier }} ({{ $pembelian->created_at->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('pembelian_id') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="no_penerimaan">No. Faktur</label>
                        <input type="text" class="form-control" id="no_penerimaan" placeholder="Enter No. Faktur" wire:model="no_penerimaan" @if($isShow) readonly @endif>
                        @error('no_penerimaan') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_terima">Tanggal Terima</label>
                        <input type="date" class="form-control" id="tanggal_terima" wire:model="tanggal_terima" @if($isShow) readonly @endif>
                        @error('tanggal_terima') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gudang_id">Gudang</label>
                        <select class="form-control" id="gudang_id" wire:model="gudang_id" @if($isShow) disabled @endif>
                            <option value="">Select Gudang</option>
                            @foreach($gudangs as $gudang)
                                <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                            @endforeach
                        </select>
                        @error('gudang_id') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="diterima_oleh">Diterima Oleh</label>
                        <input type="text" class="form-control" id="diterima_oleh" placeholder="Enter Diterima Oleh" wire:model="diterima_oleh" @if($isShow) readonly @endif>
                        @error('diterima_oleh') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <hr>

            <h4 class="mb-3">Detail Barang Diterima</h4>
            @if(count($details) > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Qty PO</th>
                            <th>Qty Diterima</th>
                            <th>Harga Satuan</th>
                            <th>Diskon (@)</th>
                            <th>PPN (%)</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $index => $detail)
                            <tr>
                                <td>
                                    {{ $detail['nama_barang'] }}
                                    <input type="hidden" wire:model="details.{{ $index }}.barang_id">
                                </td>
                                <td>
                                    {{ $detail['qty_po'] }}
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.live="details.{{ $index }}.qty_diterima" min="0" max="{{ $detail['qty_po'] }}" @if($isShow) readonly @endif>
                                    @error('details.'.$index.'.qty_diterima') <span class="text-danger">{{ $message }}</span>@enderror
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.live="details.{{ $index }}.harga_satuan" min="0" @if($isShow) readonly @endif>
                                    @error('details.'.$index.'.harga_satuan') <span class="text-danger">{{ $message }}</span>@enderror
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.live="details.{{ $index }}.diskon" min="0" @if($isShow) readonly @endif>
                                    @error('details.'.$index.'.diskon') <span class="text-danger">{{ $message }}</span>@enderror
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.live="details.{{ $index }}.ppn" min="0" @if($isShow) readonly @endif>
                                    @error('details.'.$index.'.ppn') <span class="text-danger">{{ $message }}</span>@enderror
                                </td>
                                <td>
                                    <input type="text" class="form-control text-right" value="Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}" readonly>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Subtotal:</strong></td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control text-right" 
                                           value="{{ number_format($this->subTotal, 0, ',', '.') }}" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Diskon (Global):</strong></td>
                            <td>
                                <input type="number" wire:model.live="diskon" class="form-control text-right" min="0" @if($isShow) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>PPN (Global):</strong></td>
                            <td>
                                <div class="input-group">
                                    <input type="number" wire:model.live="ppn" class="form-control text-right" min="0" @if($isShow) readonly @endif>
                                    @if(!$isShow)
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Hitung PPN
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" wire:click.prevent="calculateGlobalPPNWithRate(10)">10%</a>
                                                <a class="dropdown-item" href="#" wire:click.prevent="calculateGlobalPPNWithRate(11)">11%</a>
                                                <a class="dropdown-item" href="#" wire:click.prevent="calculateGlobalPPNWithRate(12)">12%</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#" wire:click.prevent="calculateGlobalPPNWithRate(0)">Reset (0%)</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Biaya Lain-lain:</strong></td>
                            <td>
                                <input type="number" wire:model.live="biaya_lain" class="form-control text-right" min="0" @if($isShow) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Total Nilai Penerimaan:</strong></td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control font-weight-bold text-success text-right" 
                                           value="{{ number_format($this->calculatedTotal, 0, ',', '.') }}" readonly>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            @else
                <div class="alert alert-info">
                    Silakan pilih No. Pembelian untuk melihat detail barang.
                </div>
            @endif

            <hr>

            @unless($isShow)
                <button type="submit" class="btn btn-primary">{{ $penerimaan_id ? 'Update' : 'Save' }}</button>
            @endunless

            <button wire:click="closeModal()" type="button" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div>
