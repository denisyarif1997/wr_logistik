{{-- Payment Modal --}}
<div>
@if($viewPenjualan)
<div class="modal fade" id="modalBayar{{ $viewPenjualan->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bayar Penjualan {{ $viewPenjualan->no_penjualan }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Total Penjualan</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($viewPenjualan->total,0,',','.') }}" readonly>
                </div>
                <div class="form-group">
                    <label>Sudah Bayar</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($viewPenjualan->bayar,0,',','.') }}" readonly>
                </div>
                <div class="form-group">
                    <label>Kurang Bayar</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($viewPenjualan->total - $viewPenjualan->bayar,0,',','.') }}" readonly>
                </div>
                <div class="form-group">
                    <label>Jumlah Bayar</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                        <input type="number" class="form-control" wire:model="jumlahBayar" 
                               min="0" max="{{ $viewPenjualan->total - $viewPenjualan->bayar }}" 
                               placeholder="Masukkan jumlah pembayaran">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" 
                        wire:click="bayarPenjualan({{ $viewPenjualan->id }})"
                        wire:loading.attr="disabled"
                        onclick="return confirm('Proses pembayaran ini?')">
                    <i class="fas fa-check"></i> Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<div>
    <div class="row">
        {{-- KOLOM KIRI: INPUT & CART --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kasir - Penjualan</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        @if($isView)
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Mode Lihat - Penjualan sudah diproses, tidak dapat diubah.
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" wire:model="tanggal" {{ $isView ? 'readonly disabled' : '' }}>
                        </div>
                        <div class="col-md-3">
                            <label>Gudang</label>
                            <select class="form-control" wire:model="gudang_id" {{ $isView ? 'disabled' : '' }}>
                                @foreach($gudangs as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Kas / Bank</label>
                            <select class="form-control" wire:model="kas_id" {{ $isView ? 'disabled' : '' }}>
                                @foreach($kasAccounts as $k)
                                    <option value="{{ $k->id }}">{{ $k->kode_akun }} - {{ $k->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Keterangan (Opsional)</label>
                            <input type="text" class="form-control" wire:model="keterangan" placeholder="Catatan penjualan..." {{ $isView ? 'readonly disabled' : '' }}>
                        </div>
                    </div>

                    @if(!$isView)
                    <hr>

                    {{-- SCANNER BARCODE --}}
                    <div class="form-group">
                        <label for="barcode"><i class="fas fa-barcode mr-1"></i> Scan / Input Barcode</label>
                        <div class="input-group">
                            <input type="text" id="barcode" class="form-control form-control-lg"
                                   placeholder="Scan atau ketik barcode lalu Enter..."
                                   wire:model.defer="barcode"
                                   wire:keydown.enter.prevent="scanBarcode"
                                   autofocus>
                            <div class="input-group-append">
                                <button class="btn btn-primary" wire:click="scanBarcode" type="button">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Bisa diisi manual (ketik lalu Enter) atau discan langsung dengan scanner barcode (otomatis Enter).</small>
                    </div>

                    {{-- PENCARIAN MANUAL --}}
                    <div class="form-group mt-3">
                        <label>Cari Barang Manual</label>
                        <div class="input-group">
                            <input type="text" class="form-control" wire:model.live="barcodeSearch" placeholder="Ketik nama barang...">
                        </div>
                        @if(!empty($barcodeSearch) && strlen($barcodeSearch) >= 2)
                            @php
                                $manualResults = App\Models\Barang::whereRaw('LOWER(nama_barang) LIKE ?', ['%' . strtolower($barcodeSearch) . '%'])->limit(8)->get();
                            @endphp
                            <div class="list-group mt-1 shadow" style="max-height:200px; overflow-y:auto;">
                                @forelse($manualResults as $mb)
                                    @php
                                        $hargaJual = \App\Livewire\Kasir::hitungHargaJual($mb);
                                        $stokItem = \App\Models\Stok::where('barang_id', $mb->id)
                                            ->where('gudang_id', $gudang_id)
                                            ->first();
                                        $stokAkhir = $stokItem ? $stokItem->stok_akhir : 0;
                                    @endphp
                                    <button type="button" class="list-group-item list-group-item-action"
                                            wire:click="addToCart({{ $mb->id }})">
                                        <div class="d-flex w-100 justify-content-between">
                                            <strong>{{ $mb->nama_barang }}</strong>
                                            <span>Rp {{ number_format($hargaJual,0,',','.') }}</span>
                                        </div>
                                        <div class="d-flex w-100 justify-content-between">
                                            <small>
                                                @if($mb->barcode) <span class="text-muted">{{ $mb->barcode }}</span> @endif
                                                <span class="badge badge-{{ $stokAkhir > 0 ? 'success' : 'danger' }}">
                                                    Stok: {{ number_format($stokAkhir,0,',','.') }}
                                                </span>
                                            </small>
                                        </div>
                                    </button>
                                @empty
                                    <div class="list-group-item">Tidak ditemukan</div>
                                @endforelse
                            </div>
                        @endif
                    </div>

                    <hr>
                    @endif

                    {{-- CART --}}
                    <h5>Item Keranjang</h5>
                    @if(count($cart) == 0)
                        <div class="alert alert-info">Belum ada item. Scan barcode atau cari barang manual.</div>
                    @else
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th style="width:80px;">Qty</th>
                                    <th style="width:80px;">Diskon %</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $key => $item)
                                    <tr>
                                        <td>{{ $item['kode'] }}</td>
                                        <td>
                                            {{ $item['nama'] }}
                                            @if($item['barcode']) <br><small class="text-muted">{{ $item['barcode'] }}</small> @endif
                                        </td>
                                        <td>Rp {{ number_format($item['harga'],0,',','.') }}</td>
                                        <td>
                                            @if($isView)
                                                {{ $item['qty'] }}
                                            @else
                                                <input type="number" min="1" class="form-control form-control-sm"
                                                       value="{{ $item['qty'] }}"
                                                       wire:change="updateQty({{ $key }}, $event.target.value)">
                                            @endif
                                            <small class="text-muted">Stok: {{ $item['stok'] }}</small>
                                        </td>
                                        <td>
                                            @if($isView)
                                                {{ $item['diskon_persen'] ?? 0 }}%
                                            @else
                                                <input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm"
                                                       value="{{ $item['diskon_persen'] ?? 0 }}"
                                                       wire:change="updateDiskonItem({{ $key }}, $event.target.value)">
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($item['subtotal'],0,',','.') }}</td>
                                        <td>
                                            @if(!$isView)
                                                <button class="btn btn-danger btn-sm" wire:click="removeItem({{ $key }})">X</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TOTAL & BAYAR --}}
        <div class="col-md-4">
            <div class="card card-teal-400">
                <div class="card-header">
                    <h3 class="card-title">Pembayaran</h3>
                </div>
                <div class="card-body">
                    {{-- Diskon Global --}}
                    <div class="form-group">
                        <label>Diskon Global (%)</label>
                        <input type="number" min="0" max="100" step="0.1" class="form-control" wire:model.lazy="diskon" placeholder="0" {{ $isView ? 'readonly disabled' : '' }}>
                    </div>

                    {{-- PPN --}}
                    <div class="form-group">
                        <label>PPN</label>
                        <select class="form-control" wire:model="ppn_id" {{ $isView ? 'disabled' : '' }}>
                            <option value="">Tanpa PPN</option>
                            @foreach($ppnMasters as $p)
                                <option value="{{ $p->id }}">{{ $p->kode_ppn ?? 'PPN' }} - {{ $p->rate }}%</option>
                            @endforeach
                        </select>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-1">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal,0,',','.') }}</span>
                    </div>
                    @if($diskon > 0)
                    <div class="d-flex justify-content-between mb-1 text-danger">
                        <span>Diskon ({{ $diskon }}%)</span>
                        <span>- Rp {{ number_format($subtotal * $diskon / 100,0,',','.') }}</span>
                    </div>
                    @endif
                    @if($ppn_rate > 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span>PPN ({{ $ppn_rate }}%)</span>
                        <span>Rp {{ number_format($ppn_amount,0,',','.') }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total</strong>
                        <strong class="text-lg">Rp {{ number_format($total,0,',','.') }}</strong>
                    </div>

                    <div class="form-group">
                        <label>Uang Bayar</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                            <input type="number" min="0" class="form-control" wire:model.lazy="bayar" placeholder="0" {{ $isView ? 'readonly disabled' : '' }}>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Kembalian</span>
                        <strong class="text-success">Rp {{ number_format($kembalian,0,',','.') }}</strong>
                    </div>

                    @if(!$isView)
                        @if($isEdit)
                            <button class="btn btn-warning btn-block btn-lg" wire:click="updatePenjualan"
                                    {{ count($cart) == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-save"></i> UPDATE PENJUALAN
                            </button>
                            <button class="btn btn-secondary btn-block mt-2" wire:click="clearCart">Batal Edit</button>
                        @else
                            <button class="btn btn-success btn-block btn-lg" wire:click="bayarSekarang"
                                    {{ count($cart) == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cash-register"></i> BAYAR & SIMPAN
                            </button>
                            <button class="btn btn-info btn-block mt-2" wire:click="simpanDraft"
                                    {{ count($cart) == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-save"></i> SIMPAN DRAFT
                            </button>
                            <button class="btn btn-default btn-block mt-2" wire:click="clearCart">Bersihkan</button>
                        @endif
                    @else
                        @if($viewPenjualan && $viewPenjualan->status != 'selesai')
                            @if($viewPenjualan->status == 'draft')
                                <button class="btn btn-primary btn-block btn-lg" wire:click="kunciPenjualan({{ $viewPenjualan->id }})"
                                        onclick="return confirm('Kunci penjualan ini? Status akan berubah menjadi kunci.')">
                                    <i class="fas fa-lock"></i> KUNCI PENJUALAN
                                </button>
                            @endif
                            @if($viewPenjualan->bayar < $viewPenjualan->total)
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Penjualan ini belum lunas. Total: Rp {{ number_format($viewPenjualan->total,0,',','.') }}, 
                                    Bayar: Rp {{ number_format($viewPenjualan->bayar,0,',','.') }}, 
                                    Kurang: Rp {{ number_format($viewPenjualan->total - $viewPenjualan->bayar,0,',','.') }}
                                </div>
                                <button class="btn btn-success btn-block btn-lg mt-2" 
                                        onclick="$('#modalBayar{{ $viewPenjualan->id }}').modal('show')">
                                    <i class="fas fa-money-bill-wave"></i> BAYAR SEKARANG
                                </button>
                            @endif
                            @if($viewPenjualan->bayar >= $viewPenjualan->total)
                                <button class="btn btn-success btn-block mt-2" wire:click="tutupPenjualan({{ $viewPenjualan->id }})"
                                        onclick="return confirm('Tutup penjualan ini? Status akan berubah menjadi selesai.')">
                                    <i class="fas fa-check"></i> TUTUP PENJUALAN
                                </button>
                            @endif
                        @endif
                        <a href="{{ route('admin.kasir.print', $viewPenjualan->id) }}" target="_blank" class="btn btn-primary btn-block btn-lg mt-2">
                            <i class="fas fa-print"></i> Cetak Billing
                        </a>
                        <button type="button" class="btn btn-secondary btn-block btn-lg mt-2" wire:click="resetKasir">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    @endif
                </div>
            </div>

            {{-- STRUK --}}
            @if($lastSale)
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Struk Penjualan</h3></div>
                    <div class="card-body">
                        <p class="mb-1"><strong>No:</strong> {{ $lastSale->no_penjualan }}</p>
                        <p class="mb-1"><strong>Tanggal:</strong> {{ $lastSale->tanggal }}</p>
                        <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($lastSale->total,0,',','.') }}</p>
                        <p class="mb-1"><strong>Bayar:</strong> Rp {{ number_format($lastSale->bayar,0,',','.') }}</p>
                        @if($lastSale->diskon > 0)
                        <p class="mb-1"><strong>Diskon:</strong> {{ $lastSale->diskon }}%</p>
                        @endif
                        @if($lastSale->ppn > 0)
                        <p class="mb-1"><strong>PPN:</strong> Rp {{ number_format($lastSale->ppn,0,',','.') }}</p>
                        @endif
                        <p class="mb-1"><strong>Kembalian:</strong> Rp {{ number_format($lastSale->kembalian,0,',','.') }}</p>
                        <hr>
                        <ul class="list-unstyled mb-0">
                            @foreach($lastSale->details as $d)
                                <li>{{ $d->barang->nama_barang ?? '-' }} x {{ $d->qty }} = Rp {{ number_format($d->subtotal,0,',','.') }}</li>
                            @endforeach
                        </ul>
                        <hr>
                        <a href="{{ route('admin.kasir.print', $lastSale->id) }}" target="_blank" class="btn btn-primary btn-block btn-sm">
                            <i class="fas fa-print"></i> Cetak Billing
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Return History Modal --}}
    @if($showReturnHistoryModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Histori Retur: {{ $selectedReturnSale->no_penjualan ?? '-' }}</h5>
                        <button type="button" class="close" wire:click="closeReturnHistory" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th>Gudang</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Stok Akhir</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($returnHistory as $history)
                                        <tr>
                                            <td>{{ $history->tanggal->format('d-m-Y') }}</td>
                                            <td>{{ $history->barang->nama_barang ?? '-' }}</td>
                                            <td>{{ $history->gudang->nama_gudang ?? '-' }}</td>
                                            <td>{{ number_format($history->qty_masuk, 0, ',', '.') }}</td>
                                            <td>{{ number_format($history->qty_keluar, 0, ',', '.') }}</td>
                                            <td>{{ number_format($history->stok_akhir, 0, ',', '.') }}</td>
                                            <td>{{ $history->keterangan }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada histori retur.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeReturnHistory">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- DAFTAR PENJUALAN --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Penjualan</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Cari no penjualan...">
                                <div class="input-group-append">
                                    <button wire:click="$refresh" class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" wire:model="startDate">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" wire:model="endDate">
                        </div>
                        <div class="col-md-2">
                            <button wire:click="$refresh" class="btn btn-info btn-block"><i class="fas fa-filter"></i> Filter</button>
                        </div>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No Penjualan</th>
                                <th>Waktu</th>
                                <th>Gudang</th>
                                <th>Total</th>
                                <th>Bayar</th>
                                <th>Kembalian</th>
                                <th>Status</th>
                                <th>Kasir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penjualans as $p)
                                <tr>
                                    <td>{{ $p->no_penjualan }}</td>
                                    <td>
                                        {{ $p->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}
                                    </td>
                                    <td>{{ $p->gudang->nama_gudang ?? '-' }}</td>
                                    <td class="text-right">{{ number_format($p->total, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($p->bayar, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($p->kembalian, 0, ',', '.') }}</td>
                                     <td>
                                         @php
                                             $statusClass = 'secondary';
                                             if($p->status == 'selesai') $statusClass = 'success';
                                             elseif($p->status == 'kunci') $statusClass = 'info';
                                             elseif($p->status == 'draft') $statusClass = 'warning';
                                             elseif($p->status == 'batal') $statusClass = 'danger';
                                         @endphp
                                         <span class="badge badge-{{ $statusClass }}">
                                             {{ ucfirst($p->status) }}
                                         </span>
                                     </td>
                                     <td>{{ $p->creator->name ?? '-' }}</td>
                                     <td>
                                         <a href="{{ route('admin.kasir.index', ['view' => $p->id]) }}" class="btn btn-sm btn-secondary">
                                             <i class="fas fa-eye"></i>
                                         </a>
                                         @if($p->status == 'draft' || $p->status == 'kunci')
                                             <a href="{{ route('admin.kasir.index', ['edit' => $p->id]) }}" class="btn btn-sm btn-warning">
                                                 <i class="fas fa-edit"></i>
                                             </a>
                                             <button class="btn btn-sm btn-danger" wire:click="returPenjualan({{ $p->id }})"
                                                     onclick="return confirm('Retur penjualan ini? Stok akan dikembalikan.')">
                                                 <i class="fas fa-undo"></i>
                                             </button>
                                         @endif
                                         <a href="{{ route('admin.kasir.print', $p->id) }}" target="_blank" class="btn btn-sm btn-info">
                                             <i class="fas fa-print"></i>
                                         </a>
                                         <button class="btn btn-sm btn-secondary" wire:click="viewReturnHistory({{ $p->id }})" type="button">
                                             <i class="fas fa-history"></i>
                                         </button>
                                     </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $penjualans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>