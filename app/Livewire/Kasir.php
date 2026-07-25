<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Akun;
use App\Models\Ppn;
use App\Models\Penjualan as ModelsPenjualan;
use App\Models\PenjualanDetail;
use App\Models\Stok;
use App\Models\KartuStok;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Kasir extends Component
{
    use WithPagination;

    protected $rules = [
        'tanggal' => 'required|date',
        'gudang_id' => 'required|exists:gudang,id',
        'kas_id' => 'required|exists:akun,id',
        'bayar' => 'required|numeric|min:0',
        'diskon' => 'nullable|numeric|min:0|max:100',
        'ppn_rate' => 'nullable|numeric|min:0',
    ];

    public $barcode = '';
    public $barcodeSearch = '';
    public $gudang_id;
    public $kas_id;
    public $tanggal;
    public $keterangan = '';

    // Cart: each item = ['barang_id', 'kode', 'nama', 'barcode', 'harga', 'qty', 'subtotal', 'stok', 'diskon_persen']
    public $cart = [];

    public $bayar = 0;
    public $lastSale = null; // untuk struk

    // Diskon & PPN
    public $diskon = 0; // global diskon %
    public $ppn_rate = 0; // PPN rate %
    public $ppn_amount = 0; // calculated PPN amount
    public $ppn_id = null;

    public $isView = false;
    public $viewPenjualan = null;
    public $isEdit = false;
    public $editPenjualanId = null;
    public $piutang_id = null; // Akun piutang untuk penjualan belum lunas
    public $status = 'draft'; // Status penjualan: draft (belum lunas/piutang), selesai (lunas), retur.
                               // Catatan: status "kunci" adalah peninggalan versi lama dan tidak
                               // dipakai lagi untuk transaksi baru; hanya mungkin muncul di data lama.
    public $jumlahBayar = 0; // Jumlah pembayaran tambahan

    // Guard flag: mencegah eksekusi ganda (double-click / double-submit)
    public $processing = false;

    // Daftar Penjualan properties
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $startDate, $endDate;

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');

        $firstGudang = Gudang::first();
        $this->gudang_id = $firstGudang ? $firstGudang->id : null;

        $firstKas = Akun::where('tipe', 'aset')
            ->where(function ($q) {
                $q->whereRaw('LOWER(nama_akun) LIKE ?', ['%kas%'])
                    ->orWhereRaw('LOWER(nama_akun) LIKE ?', ['%bank%']);
            })
            ->first();
        $this->kas_id = $firstKas ? $firstKas->id : null;

        // Auto load default PPN rate
        $defaultPpn = Ppn::getCurrentRate();
        if ($defaultPpn) {
            $this->ppn_rate = $defaultPpn->rate;
            $this->ppn_id = $defaultPpn->id;
        }

        // Check if editing an existing penjualan
        if (request()->has('edit')) {
            $penjualan = ModelsPenjualan::with(['details.barang', 'gudang', 'creator'])->find(request('edit'));
            if ($penjualan) {
                $this->isEdit = true;
                $this->editPenjualanId = $penjualan->id;
                $this->tanggal = $penjualan->tanggal->format('Y-m-d');
                $this->gudang_id = $penjualan->gudang_id;
                $this->kas_id = $penjualan->kas_id;
                $this->diskon = $penjualan->diskon;
                $this->ppn_rate = $penjualan->ppn_rate;
                $this->ppn_amount = $penjualan->ppn;
                $this->bayar = $penjualan->bayar;
                $this->keterangan = $penjualan->keterangan;

                // Load cart from penjualan details
                $this->cart = $penjualan->details->map(function ($detail) {
                    $stok = Stok::where('barang_id', $detail->barang_id)
                        ->where('gudang_id', $this->gudang_id)
                        ->first();

                    return [
                        'barang_id' => $detail->barang_id,
                        'kode' => $detail->barang->kode_barang ?? '',
                        'nama' => $detail->barang->nama_barang ?? '',
                        'barcode' => $detail->barang->barcode ?? '',
                        'harga' => $detail->harga_satuan,
                        'qty' => $detail->qty,
                        'subtotal' => $detail->subtotal,
                        'stok' => $stok ? $stok->stok_akhir + $detail->qty : $detail->qty, // Tambah qty lama agar bisa dihapus
                        'diskon_persen' => $detail->diskon,
                    ];
                })->toArray();
            }
        }

        // Check if viewing an existing penjualan
        if (request()->has('view')) {
            $penjualan = ModelsPenjualan::with(['details.barang', 'gudang', 'creator'])->find(request('view'));
            if ($penjualan) {
                $this->isView = true;
                $this->viewPenjualan = $penjualan;
                $this->tanggal = $penjualan->tanggal->format('Y-m-d');
                $this->gudang_id = $penjualan->gudang_id;
                $this->kas_id = $penjualan->kas_id;
                $this->diskon = $penjualan->diskon;
                $this->ppn_rate = $penjualan->ppn_rate;
                $this->ppn_amount = $penjualan->ppn;
                $this->bayar = $penjualan->bayar;
                $this->keterangan = $penjualan->keterangan;

                // Load cart from penjualan details
                $this->cart = $penjualan->details->map(function ($detail) {
                    return [
                        'barang_id' => $detail->barang_id,
                        'kode' => $detail->barang->kode_barang ?? '',
                        'nama' => $detail->barang->nama_barang ?? '',
                        'barcode' => $detail->barang->barcode ?? '',
                        'harga' => $detail->harga_satuan,
                        'qty' => $detail->qty,
                        'subtotal' => $detail->subtotal,
                        'stok' => 0, // Will be updated if needed
                        'diskon_persen' => $detail->diskon,
                    ];
                })->toArray();
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $gudangs = Gudang::all();
        $kasAccounts = Akun::where('tipe', 'aset')
            ->where(function ($q) {
                $q->whereRaw('LOWER(nama_akun) LIKE ?', ['%kas%'])
                  ->orWhereRaw('LOWER(nama_akun) LIKE ?', ['%bank%']);
            })
            ->orderBy('kode_akun')
            ->get();
        $ppnMasters = Ppn::active()->orderBy('rate', 'asc')->get();

        $subtotal = $this->getSubTotalProperty();
        $total = $this->getTotalProperty();
        $kembalian = $this->bayar > $total ? $this->bayar - $total : 0;
        $quickCashOptions = $this->getQuickCashOptionsProperty();

        // Get penjualan list
        $penjualans = ModelsPenjualan::with(['gudang', 'creator', 'details'])
            ->where(function ($query) {
                $query->whereRaw('LOWER(no_penjualan) LIKE ?', ['%' . strtolower($this->search) . '%']);
            })
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.kasir', compact(
            'gudangs',
            'kasAccounts',
            'ppnMasters',
            'subtotal',
            'total',
            'kembalian',
            'penjualans',
            'quickCashOptions'
        ));
    }

    public function getSubTotalProperty()
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getTotalProperty()
    {
        $subtotal = $this->getSubTotalProperty();
        // Apply global diskon %
        $diskonAmount = $subtotal * ($this->diskon / 100);
        $afterDiskon = $subtotal - $diskonAmount;
        // Add PPN
        $ppnValue = $afterDiskon * ($this->ppn_rate / 100);
        $this->ppn_amount = $ppnValue;
        return $afterDiskon + $ppnValue;
    }

    /**
     * Standar kasir: sediakan opsi nominal cepat (uang pas + pembulatan ke atas)
     * supaya kasir tidak perlu mengetik manual setiap transaksi.
     */
    public function getQuickCashOptionsProperty()
    {
        $total = (float) $this->getTotalProperty();
        if ($total <= 0) {
            return [];
        }

        $options = [(int) ceil($total)]; // uang pas
        $steps = [5000, 10000, 20000, 50000, 100000];

        foreach ($steps as $step) {
            $rounded = (int) (ceil($total / $step) * $step);
            if ($rounded > $total) {
                $options[] = $rounded;
            }
        }

        $options = array_values(array_unique($options));
        sort($options);

        return array_slice($options, 0, 4);
    }

    public function setBayar($amount)
    {
        $this->bayar = (float) $amount;
    }

    public function hitungItemSubtotal($harga, $qty, $diskonPersen)
    {
        $totalHarga = $harga * $qty;
        $diskonAmount = $totalHarga * ($diskonPersen / 100);
        return $totalHarga - $diskonAmount;
    }

    public function updatedDiskon()
    {
        $this->recalculateCart();
    }

    public function updatedPpnRate()
    {
        // No recalc needed, just update total display
        $this->getTotalProperty();
    }

    public function updatedPpnId($value)
    {
        if ($value) {
            $ppn = Ppn::find($value);
            if ($ppn) {
                $this->ppn_rate = $ppn->rate;
            }
        } else {
            $this->ppn_rate = 0;
        }
    }

    private function recalculateCart()
    {
        foreach ($this->cart as $key => $item) {
            $this->cart[$key]['subtotal'] = $this->hitungItemSubtotal(
                $item['harga'],
                $item['qty'],
                $item['diskon_persen'] ?? 0
            );
        }
    }

    /**
     * Hitung harga jual barang berdasarkan HPP + margin (%).
     * Fallback ke harga_jual jika margin kosong.
     */
    public static function hitungHargaJual($barang)
    {
        $basePrice = $barang->hpp ?? $barang->harga_beli_terakhir ?? 0;
        $margin = $barang->margin_jual ?? 0;

        if ($margin > 0) {
            return $basePrice * (1 + $margin / 100);
        }

        return $barang->harga_jual ?? $basePrice;
    }

    /**
     * Scan / input barcode (bisa manual diketik atau dari scanner barcode).
     */
    public function scanBarcode()
    {
        $code = trim($this->barcode);
        if (empty($code)) {
            return;
        }

        $barang = Barang::where('barcode', $code)->first();

        if (!$barang) {
            $barang = Barang::where('kode_barang', $code)->first();
        }

        if (!$barang || !$barang->is_jual) {
            $this->dispatch('notify', message: 'Barang tidak ditemukan atau tidak dapat dijual.', type: 'error');
            $this->barcode = '';
            $this->dispatch('focus-barcode');
            return;
        }

        $this->addToCart($barang);
        $this->barcode = '';
        $this->dispatch('focus-barcode');
    }

    /**
     * Tambah barang ke cart (bisa dari pencarian manual).
     */
    public function addToCart($barang)
    {
        if (is_numeric($barang)) {
            $barang = Barang::find($barang);
        }
        if (!$barang || !$barang->is_jual) {
            $this->dispatch('notify', message: 'Barang tidak dapat dijual.', type: 'error');
            return;
        }

        $harga = self::hitungHargaJual($barang);

        // Cek stok di gudang terpilih
        // Catatan: ini adalah cek "soft" untuk UX cepat saat input. Validasi final
        // (dengan row-lock) tetap dilakukan ulang di validateAndLockStock() saat
        // transaksi benar-benar disimpan, supaya tidak terjadi overselling.
        $stok = Stok::where('barang_id', $barang->id)
            ->where('gudang_id', $this->gudang_id)
            ->first();
        $stokAkhir = $stok ? $stok->stok_akhir : 0;

        if ($stokAkhir <= 0) {
            $this->dispatch('notify', message: 'Stok ' . $barang->nama_barang . ' habis di gudang ini.', type: 'error');
            return;
        }

        // Jika sudah ada di cart, tambah qty
        foreach ($this->cart as $key => $item) {
            if ($item['barang_id'] == $barang->id) {
                $newQty = $item['qty'] + 1;
                if ($newQty > $stokAkhir) {
                    $this->dispatch('notify', message: 'Qty melebihi stok tersedia (' . $stokAkhir . ').', type: 'error');
                    return;
                }
                $this->cart[$key]['qty'] = $newQty;
                $this->cart[$key]['subtotal'] = $this->hitungItemSubtotal(
                    $item['harga'],
                    $newQty,
                    $item['diskon_persen'] ?? 0
                );
                return;
            }
        }

        $this->cart[] = [
            'barang_id' => $barang->id,
            'kode' => $barang->kode_barang,
            'nama' => $barang->nama_barang,
            'barcode' => $barang->barcode,
            'harga' => $harga,
            'qty' => 1,
            'subtotal' => $harga,
            'stok' => $stokAkhir,
            'diskon_persen' => 0,
        ];

        $this->dispatch('focus-barcode');
    }

    public function updateQty($key, $qty)
    {
        $qty = (float) $qty;
        if ($qty <= 0) {
            $this->removeItem($key);
            return;
        }
        if (isset($this->cart[$key])) {
            if ($qty > $this->cart[$key]['stok']) {
                $this->dispatch('notify', message: 'Qty melebihi stok tersedia (' . $this->cart[$key]['stok'] . ').', type: 'error');
                return;
            }
            $this->cart[$key]['qty'] = $qty;
            $this->cart[$key]['subtotal'] = $this->hitungItemSubtotal(
                $this->cart[$key]['harga'],
                $qty,
                $this->cart[$key]['diskon_persen'] ?? 0
            );
        }
    }

    public function updateDiskonItem($key, $diskonPersen)
    {
        $diskonPersen = (float) $diskonPersen;
        if ($diskonPersen < 0) $diskonPersen = 0;
        if ($diskonPersen > 100) $diskonPersen = 100;

        if (isset($this->cart[$key])) {
            $this->cart[$key]['diskon_persen'] = $diskonPersen;
            $this->cart[$key]['subtotal'] = $this->hitungItemSubtotal(
                $this->cart[$key]['harga'],
                $this->cart[$key]['qty'],
                $diskonPersen
            );
        }
    }

    public function removeItem($key)
    {
        unset($this->cart[$key]);
        $this->cart = array_values($this->cart);
    }

    public $showReturnHistoryModal = false;
    public $returnHistory = [];
    public $selectedReturnSale = null;

    public function clearCart()
    {
        $this->cart = [];
        $this->bayar = 0;
        $this->keterangan = '';
        $this->lastSale = null;
        $this->diskon = 0;
        $this->ppn_rate = 0;
        $this->ppn_amount = 0;
        $this->ppn_id = null;
        $this->isEdit = false;
        $this->editPenjualanId = null;

        // Reload default PPN
        $defaultPpn = Ppn::getCurrentRate();
        if ($defaultPpn) {
            $this->ppn_rate = $defaultPpn->rate;
            $this->ppn_id = $defaultPpn->id;
        }

        $this->dispatch('focus-barcode');
    }

    public function resetKasir()
    {
        $this->isView = false;
        $this->viewPenjualan = null;
        $this->clearCart();
    }

    public function updatedGudangId()
    {
        if (!empty($this->cart)) {
            $this->dispatch(
                'notify',
                message: 'Gudang diganti, keranjang otomatis dikosongkan karena stok berbeda per gudang.',
                type: 'info'
            );
        }
        $this->cart = [];
    }

    /**
     * Standar kasir: kunci baris stok (row lock) dan validasi ulang jumlah yang
     * tersedia TEPAT SEBELUM transaksi disimpan. Ini WAJIB dilakukan di dalam
     * DB::transaction agar tidak terjadi overselling ketika dua kasir menjual
     * barang yang sama secara bersamaan.
     *
     * @throws \Exception jika stok tidak mencukupi
     * @return array<int, Stok> map barang_id => model Stok yang sudah dikunci
     */
    private function validateAndLockStock(array $cart, $gudangId): array
    {
        $lockedStocks = [];

        // Urutkan berdasarkan barang_id supaya urutan locking konsisten
        // antar transaksi -> mengurangi risiko deadlock.
        $sortedCart = collect($cart)->sortBy('barang_id');

        foreach ($sortedCart as $item) {
            $stok = Stok::where('barang_id', $item['barang_id'])
                ->where('gudang_id', $gudangId)
                ->lockForUpdate()
                ->first();

            $stokAkhir = $stok ? $stok->stok_akhir : 0;

            if ($stokAkhir < $item['qty']) {
                throw new \RuntimeException(
                    "Stok {$item['nama']} tidak mencukupi (tersedia: {$stokAkhir}, diminta: {$item['qty']})."
                );
            }

            $lockedStocks[$item['barang_id']] = $stok;
        }

        return $lockedStocks;
    }

    /**
     * Simpan penjualan — SATU aksi untuk semua kasus.
     *
     * Statusnya ditentukan otomatis dari nilai "bayar" yang diisi kasir:
     * - bayar >= total  -> transaksi LUNAS, status "selesai", kas wajib diisi,
     *   jurnal: Debit Kas / Kredit Pendapatan.
     * - bayar <  total  -> transaksi BELUM LUNAS, status "draft" (piutang),
     *   jurnal: Debit Piutang / Kredit Pendapatan. Sisa tagihan dilunasi
     *   nanti lewat bayarPenjualan().
     *
     * Ini menggantikan bayarSekarang() + simpanDraft() yang sebelumnya
     * terpisah padahal logikanya nyaris identik dan hanya beda di satu
     * kondisi (apakah bayar sudah cukup).
     */
    public function simpanPenjualan()
    {
        // Guard: cegah eksekusi ganda akibat double-click / double-submit
        if ($this->processing) {
            return;
        }
        $this->processing = true;

        try {
            $this->validate([
                'tanggal' => 'required|date',
                'gudang_id' => 'required|exists:gudang,id',
                'kas_id' => 'nullable|exists:akun,id',
                'bayar' => 'required|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0|max:100',
                'ppn_rate' => 'nullable|numeric|min:0',
            ]);

            if (empty($this->cart)) {
                $this->dispatch('notify', message: 'Cart kosong.', type: 'error');
                return;
            }

            $total = $this->getTotalProperty();
            $isLunas = $this->bayar >= $total;

            // Kas hanya wajib diisi kalau uangnya benar-benar diterima sekarang (lunas).
            // Untuk transaksi belum lunas (piutang), kas boleh dikosongkan dulu.
            if ($isLunas && !$this->kas_id) {
                $this->dispatch('notify', message: 'Silakan pilih Kas / Rekening tujuan terlebih dahulu.', type: 'error');
                return;
            }

            DB::transaction(function () use ($total, $isLunas) {
                $userId = Auth::id();
                $kembalian = $isLunas ? $this->bayar - $total : 0;

                // Kunci & validasi ulang stok sebelum memproses transaksi
                $lockedStocks = $this->validateAndLockStock($this->cart, $this->gudang_id);

                $noPenjualan = $this->generateNoPenjualan();

                $penjualan = ModelsPenjualan::create([
                    'no_penjualan' => $noPenjualan,
                    'tanggal' => $this->tanggal,
                    'gudang_id' => $this->gudang_id,
                    'kas_id' => $this->kas_id,
                    'diskon' => $this->diskon,
                    'ppn' => $this->ppn_amount,
                    'ppn_rate' => $this->ppn_rate,
                    'total' => $total,
                    'bayar' => $this->bayar,
                    'kembalian' => $kembalian,
                    'status' => $isLunas ? 'selesai' : 'draft',
                    'keterangan' => $this->keterangan,
                    'inserted_user' => $userId,
                    'updated_user' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($this->cart as $item) {
                    $penjualan->details()->create([
                        'barang_id' => $item['barang_id'],
                        'qty' => $item['qty'],
                        'harga_satuan' => $item['harga'],
                        'diskon' => $item['diskon_persen'] ?? 0,
                        'subtotal' => $item['subtotal'],
                    ]);

                    // Kurangi stok menggunakan baris yang sudah dikunci
                    $stok = $lockedStocks[$item['barang_id']] ?? null;
                    if ($stok) {
                        $stok->stok_akhir -= $item['qty'];
                        if ($stok->stok_akhir < 0) {
                            $stok->stok_akhir = 0;
                        }
                        $stok->save();

                        KartuStok::create([
                            'tanggal' => $this->tanggal,
                            'barang_id' => $item['barang_id'],
                            'gudang_id' => $this->gudang_id,
                            'jenis_transaksi' => 'keluar',
                            'qty_masuk' => 0,
                            'qty_keluar' => $item['qty'],
                            'stok_akhir' => $stok->stok_akhir,
                            'referensi_id' => $penjualan->id,
                            'referensi_tipe' => 'Penjualan',
                            'keterangan' => 'Penjualan ' . $noPenjualan,
                            'inserted_user' => $userId,
                        ]);
                    }
                }

                // Jurnal ditulis lewat helper yang sama dengan alur pelunasan piutang,
                // supaya logikanya konsisten di satu tempat saja.
                $this->updateJurnal($penjualan, $total, $isLunas);

                $this->lastSale = $penjualan->load('details.barang');
            });

            $this->dispatch(
                'notify',
                message: $isLunas ? 'Penjualan berhasil disimpan dan lunas.' : 'Penjualan disimpan, menunggu pelunasan.',
                type: 'success'
            );
            $this->clearCart();
        } catch (\RuntimeException $e) {
            // Error bisnis (mis. stok tidak cukup) -> tampilkan pesan apa adanya
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        } catch (\Throwable $e) {
            Log::error('Gagal memproses penjualan: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('notify', message: 'Terjadi kesalahan sistem saat memproses penjualan. Silakan coba lagi.', type: 'error');
        } finally {
            $this->processing = false;
        }
    }

    public function generateNoPenjualan()
    {
        $today = now()->format('Ymd');
        $prefix = 'PJL-' . $today . '-';

        // lockForUpdate() WAJIB dipanggil di dalam DB::transaction() supaya
        // dua transaksi yang berjalan bersamaan tidak mendapat nomor yang sama.
        $last = ModelsPenjualan::where('no_penjualan', 'like', $prefix . '%')
            ->orderBy('no_penjualan', 'desc')
            ->lockForUpdate()
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->no_penjualan, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Update HPP (Harga Pokok Penjualan) for all barang based on current stock
     * HPP is calculated as weighted average price from all stock movements.
     *
     * Catatan: method ini sebelumnya tidak pernah dipanggil di mana pun.
     * Sebaiknya dijadwalkan sebagai scheduled command/job terpisah (mis.
     * php artisan schedule) daripada dijalankan sinkron di dalam alur kasir,
     * karena melakukan looping ke seluruh tabel Barang di sini akan
     * memperlambat setiap transaksi penjualan.
     */
    private function updateHppForAllBarang()
    {
        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            $totalQty = Stok::where('barang_id', $barang->id)->sum('stok_akhir');

            if ($totalQty <= 0) {
                continue;
            }

            $kartuStokMasuk = KartuStok::where('barang_id', $barang->id)
                ->where('jenis_transaksi', 'masuk')
                ->where('referensi_tipe', 'Penerimaan')
                ->get();

            $totalQtyForAvg = 0;
            $totalValueForAvg = 0;

            foreach ($kartuStokMasuk as $kartu) {
                $penerimaanDetail = \App\Models\PenerimaanDetail::where('penerimaan_id', $kartu->referensi_id)
                    ->where('barang_id', $barang->id)
                    ->first();

                if ($penerimaanDetail) {
                    $harga = $penerimaanDetail->harga_satuan;
                    $qty = $kartu->qty_masuk;
                    $totalQtyForAvg += $qty;
                    $totalValueForAvg += $harga * $qty;
                }
            }

            if ($totalQtyForAvg > 0) {
                $hpp = $totalValueForAvg / $totalQtyForAvg;
                $barang->hpp = round($hpp, 2);
                $barang->save();
            }
        }
    }

    /**
     * Edit penjualan yang ada
     */
    public function editPenjualan($id)
    {
        $penjualan = ModelsPenjualan::with(['details.barang', 'gudang'])->find($id);

        if (!$penjualan) {
            $this->dispatch('notify', message: 'Penjualan tidak ditemukan.', type: 'error');
            return;
        }

        $this->isEdit = true;
        $this->editPenjualanId = $penjualan->id;
        $this->tanggal = $penjualan->tanggal->format('Y-m-d');
        $this->gudang_id = $penjualan->gudang_id;
        $this->kas_id = $penjualan->kas_id;
        $this->diskon = $penjualan->diskon;
        $this->ppn_rate = $penjualan->ppn_rate;
        $this->ppn_amount = $penjualan->ppn;
        $this->bayar = $penjualan->bayar;
        $this->keterangan = $penjualan->keterangan;

        $this->cart = $penjualan->details->map(function ($detail) {
            $stok = Stok::where('barang_id', $detail->barang_id)
                ->where('gudang_id', $this->gudang_id)
                ->first();

            return [
                'barang_id' => $detail->barang_id,
                'kode' => $detail->barang->kode_barang ?? '',
                'nama' => $detail->barang->nama_barang ?? '',
                'barcode' => $detail->barang->barcode ?? '',
                'harga' => $detail->harga_satuan,
                'qty' => $detail->qty,
                'subtotal' => $detail->subtotal,
                'stok' => $stok ? $stok->stok_akhir + $detail->qty : $detail->qty,
                'diskon_persen' => $detail->diskon,
            ];
        })->toArray();
    }

    /**
     * Update penjualan yang ada
     */
    public function updatePenjualan()
    {
        if ($this->processing) {
            return;
        }
        $this->processing = true;

        try {
            $this->validate([
                'tanggal' => 'required|date',
                'gudang_id' => 'required|exists:gudang,id',
                'kas_id' => 'nullable|exists:akun,id',
                'diskon' => 'nullable|numeric|min:0|max:100',
                'ppn_rate' => 'nullable|numeric|min:0',
            ]);

            if (empty($this->cart)) {
                $this->dispatch('notify', message: 'Cart kosong.', type: 'error');
                return;
            }

            $penjualan = ModelsPenjualan::with('details')->find($this->editPenjualanId);

            if (!$penjualan) {
                $this->dispatch('notify', message: 'Penjualan tidak ditemukan.', type: 'error');
                return;
            }

            $total = $this->getTotalProperty();
            $userId = Auth::id();
            $isLunas = $this->bayar >= $total;

            DB::transaction(function () use ($penjualan, $total, $isLunas, $userId) {
                $oldDetails = $penjualan->details;

                // Kembalikan stok lama (dengan lock) terlebih dahulu
                $oldBarangIds = $oldDetails->pluck('barang_id')->unique()->sort();
                foreach ($oldBarangIds as $barangId) {
                    $stok = Stok::where('barang_id', $barangId)
                        ->where('gudang_id', $penjualan->gudang_id)
                        ->lockForUpdate()
                        ->first();
                    if ($stok) {
                        $qtyLama = $oldDetails->where('barang_id', $barangId)->sum('qty');
                        $stok->stok_akhir += $qtyLama;
                        $stok->save();
                    }
                }

                // Hapus kartu stok & detail lama yang terkait dengan penjualan ini
                KartuStok::where('referensi_id', $penjualan->id)
                    ->where('referensi_tipe', 'Penjualan')
                    ->delete();
                $penjualan->details()->delete();

                // Validasi & kunci stok untuk cart baru (setelah stok lama dikembalikan)
                $lockedStocks = $this->validateAndLockStock($this->cart, $this->gudang_id);

                $newStatus = $isLunas ? 'selesai' : 'draft';
                $penjualan->update([
                    'tanggal' => $this->tanggal,
                    'gudang_id' => $this->gudang_id,
                    'kas_id' => $this->kas_id,
                    'diskon' => $this->diskon,
                    'ppn' => $this->ppn_amount,
                    'ppn_rate' => $this->ppn_rate,
                    'total' => $total,
                    'bayar' => $this->bayar,
                    'kembalian' => $isLunas ? $this->bayar - $total : 0,
                    'status' => $newStatus,
                    'keterangan' => $this->keterangan,
                    'updated_user' => $userId,
                    'updated_at' => now(),
                ]);

                foreach ($this->cart as $item) {
                    $penjualan->details()->create([
                        'barang_id' => $item['barang_id'],
                        'qty' => $item['qty'],
                        'harga_satuan' => $item['harga'],
                        'diskon' => $item['diskon_persen'] ?? 0,
                        'subtotal' => $item['subtotal'],
                    ]);

                    $stok = $lockedStocks[$item['barang_id']] ?? null;
                    if ($stok) {
                        $stok->stok_akhir -= $item['qty'];
                        if ($stok->stok_akhir < 0) {
                            $stok->stok_akhir = 0;
                        }
                        $stok->save();

                        KartuStok::create([
                            'tanggal' => $this->tanggal,
                            'barang_id' => $item['barang_id'],
                            'gudang_id' => $this->gudang_id,
                            'jenis_transaksi' => 'keluar',
                            'qty_masuk' => 0,
                            'qty_keluar' => $item['qty'],
                            'stok_akhir' => $stok->stok_akhir,
                            'referensi_id' => $penjualan->id,
                            'referensi_tipe' => 'Penjualan',
                            'keterangan' => 'Penjualan ' . $penjualan->no_penjualan,
                            'inserted_user' => $userId,
                        ]);
                    }
                }

                if ($isLunas) {
                    $this->updateJurnal($penjualan, $total, $isLunas);
                }

                $this->lastSale = $penjualan->load('details.barang');
            });

            $this->dispatch('notify', message: 'Penjualan berhasil diupdate.', type: 'success');
            $this->isEdit = false;
            $this->editPenjualanId = null;
            $this->clearCart();
        } catch (\RuntimeException $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        } catch (\Throwable $e) {
            Log::error('Gagal mengupdate penjualan: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('notify', message: 'Terjadi kesalahan sistem saat mengupdate penjualan. Silakan coba lagi.', type: 'error');
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Update jurnal penjualan
     */
    private function updateJurnal($penjualan, $total, $isLunas)
    {
        $userId = Auth::id();
        $coaPiutang = \App\Models\Setting::getValue('coa_piutang', 10);
        $coaPendapatan = \App\Models\Setting::getValue('coa_pendapatan_penjualan', 10);

        $tanggalJurnal = $penjualan->tanggal ?? $this->tanggal;

        $existingJurnal = Jurnal::withTrashed()
            ->where('referensi_id', $penjualan->id)
            ->where('referensi_tipe', 'Penjualan')
            ->first();

        if ($existingJurnal) {
            if ($existingJurnal->trashed()) {
                $existingJurnal->restore();
            }

            $existingJurnal->update([
                'tanggal' => $tanggalJurnal,
                'keterangan' => 'Penjualan ' . $penjualan->no_penjualan,
                'updated_user' => $userId,
            ]);

            $existingJurnal->details()->withTrashed()->forceDelete();

            if ($isLunas && $penjualan->kas_id) {
                $akunKas = Akun::find($penjualan->kas_id);
                $akunPendapatan = Akun::find($coaPendapatan);

                $existingJurnal->details()->create([
                    'akun_id' => $penjualan->kas_id,
                    'nama_akun' => $akunKas ? $akunKas->nama_akun : 'Kas',
                    'debit' => $total,
                    'kredit' => 0,
                ]);
                $existingJurnal->details()->create([
                    'akun_id' => $coaPendapatan,
                    'nama_akun' => $akunPendapatan ? $akunPendapatan->nama_akun : 'Pendapatan Penjualan',
                    'debit' => 0,
                    'kredit' => $total,
                ]);
            } else {
                $akunPiutang = Akun::find($coaPiutang);
                $akunPendapatan = Akun::find($coaPendapatan);

                $existingJurnal->details()->create([
                    'akun_id' => $coaPiutang,
                    'nama_akun' => $akunPiutang ? $akunPiutang->nama_akun : 'Piutang Dagang',
                    'debit' => $total,
                    'kredit' => 0,
                ]);
                $existingJurnal->details()->create([
                    'akun_id' => $coaPendapatan,
                    'nama_akun' => $akunPendapatan ? $akunPendapatan->nama_akun : 'Pendapatan Penjualan',
                    'debit' => 0,
                    'kredit' => $total,
                ]);
            }
        } else {
            $jurnal = Jurnal::create([
                'no_jurnal' => 'PJL-' . str_pad($penjualan->id, 5, '0', STR_PAD_LEFT),
                'tanggal' => $tanggalJurnal,
                'keterangan' => 'Penjualan ' . $penjualan->no_penjualan,
                'referensi_id' => $penjualan->id,
                'referensi_tipe' => 'Penjualan',
                'inserted_user' => $userId,
            ]);

            if ($isLunas && $penjualan->kas_id) {
                $akunKas = Akun::find($penjualan->kas_id);
                $akunPendapatan = Akun::find($coaPendapatan);

                $jurnal->details()->create([
                    'akun_id' => $penjualan->kas_id,
                    'nama_akun' => $akunKas ? $akunKas->nama_akun : 'Kas',
                    'debit' => $total,
                    'kredit' => 0,
                ]);
                $jurnal->details()->create([
                    'akun_id' => $coaPendapatan,
                    'nama_akun' => $akunPendapatan ? $akunPendapatan->nama_akun : 'Pendapatan Penjualan',
                    'debit' => 0,
                    'kredit' => $total,
                ]);
            } else {
                $akunPiutang = Akun::find($coaPiutang);
                $akunPendapatan = Akun::find($coaPendapatan);

                $jurnal->details()->create([
                    'akun_id' => $coaPiutang,
                    'nama_akun' => $akunPiutang ? $akunPiutang->nama_akun : 'Piutang Dagang',
                    'debit' => $total,
                    'kredit' => 0,
                ]);
                $jurnal->details()->create([
                    'akun_id' => $coaPendapatan,
                    'nama_akun' => $akunPendapatan ? $akunPendapatan->nama_akun : 'Pendapatan Penjualan',
                    'debit' => 0,
                    'kredit' => $total,
                ]);
            }
        }
    }

    /**
     * Retur penjualan (hanya untuk status draft dan kunci)
     */
    public function returPenjualan($id)
    {
        if ($this->processing) {
            return;
        }
        $this->processing = true;

        try {
            $penjualan = ModelsPenjualan::with('details')->find($id);

            if (!$penjualan) {
                $this->dispatch('notify', message: 'Penjualan tidak ditemukan.', type: 'error');
                return;
            }

            if ($penjualan->status === 'selesai') {
                $this->dispatch('notify', message: 'Penjualan sudah selesai, tidak dapat diretur.', type: 'error');
                return;
            }

            if ($penjualan->status === 'retur') {
                $this->dispatch('notify', message: 'Penjualan sudah dalam status retur.', type: 'error');
                return;
            }

            DB::transaction(function () use ($penjualan) {
                foreach ($penjualan->details as $detail) {
                    $stok = Stok::where('barang_id', $detail->barang_id)
                        ->where('gudang_id', $penjualan->gudang_id)
                        ->lockForUpdate()
                        ->first();
                    if ($stok) {
                        $stok->stok_akhir += $detail->qty;
                        $stok->save();

                        KartuStok::create([
                            'tanggal' => $penjualan->tanggal,
                            'barang_id' => $detail->barang_id,
                            'gudang_id' => $penjualan->gudang_id,
                            'jenis_transaksi' => 'masuk',
                            'qty_masuk' => $detail->qty,
                            'qty_keluar' => 0,
                            'stok_akhir' => $stok->stok_akhir,
                            'referensi_id' => $penjualan->id,
                            'referensi_tipe' => 'Penjualan',
                            'keterangan' => 'Retur penjualan ' . $penjualan->no_penjualan,
                            'inserted_user' => Auth::id(),
                        ]);
                    }
                }

                $penjualan->jurnal()->delete();

                $penjualan->update([
                    'status' => 'retur',
                    'bayar' => 0,
                    'kembalian' => 0,
                    'keterangan' => trim(($penjualan->keterangan ? $penjualan->keterangan . ' | ' : '') . 'Retur penjualan'),
                    'updated_user' => Auth::id(),
                    'updated_at' => now(),
                ]);
            });

            $this->dispatch('notify', message: 'Penjualan berhasil diretur dan disimpan sebagai retur.', type: 'success');
        } finally {
            $this->processing = false;
        }
    }

    public function viewReturnHistory($penjualanId)
    {
        $penjualan = ModelsPenjualan::with('details.barang')->find($penjualanId);

        if (!$penjualan) {
            $this->dispatch('notify', message: 'Penjualan tidak ditemukan.', type: 'error');
            return;
        }

        $this->selectedReturnSale = $penjualan;
        $this->returnHistory = KartuStok::with(['barang', 'gudang'])
            ->where('referensi_id', $penjualanId)
            ->where('referensi_tipe', 'Penjualan')
            ->orderBy('tanggal', 'desc')
            ->get();
        $this->showReturnHistoryModal = true;
    }

    public function closeReturnHistory()
    {
        $this->showReturnHistoryModal = false;
        $this->returnHistory = [];
        $this->selectedReturnSale = null;
    }

    /**
     * Bayar penjualan (tambah pembayaran untuk penjualan yang belum lunas).
     *
     * Begitu total pembayaran mencapai/melebihi total tagihan, transaksi
     * OTOMATIS ditutup (status -> "selesai") di sini. Tidak perlu lagi
     * tombol "Tutup Penjualan" terpisah seperti sebelumnya — itu bahkan
     * berisiko karena dulu bisa menutup transaksi yang belum lunas.
     */
    public function bayarPenjualan($id)
    {
        if ($this->processing) {
            return;
        }
        $this->processing = true;

        try {
            $penjualan = ModelsPenjualan::find($id);

            if (!$penjualan) {
                $this->dispatch('notify', message: 'Penjualan tidak ditemukan.', type: 'error');
                return;
            }

            if ($penjualan->status === 'selesai') {
                $this->dispatch('notify', message: 'Penjualan sudah selesai.', type: 'error');
                return;
            }

            $jumlahBayar = (float) $this->jumlahBayar;

            if ($jumlahBayar <= 0) {
                $this->dispatch('notify', message: 'Jumlah pembayaran harus lebih dari 0.', type: 'error');
                return;
            }

            $total = $penjualan->total;
            $bayarBaru = $penjualan->bayar + $jumlahBayar;
            $isLunas = $bayarBaru >= $total;
            $kembalian = $isLunas ? $bayarBaru - $total : 0;

            DB::transaction(function () use ($penjualan, $bayarBaru, $kembalian, $total, $isLunas) {
                $penjualan->update([
                    'bayar' => $bayarBaru,
                    'kembalian' => $kembalian,
                    'status' => $isLunas ? 'selesai' : $penjualan->status,
                ]);

                if ($isLunas) {
                    $this->updateJurnal($penjualan, $total, true);
                }
            });

            $this->dispatch(
                'notify',
                message: $isLunas ? 'Pembayaran berhasil, penjualan lunas dan selesai.' : 'Pembayaran berhasil diproses.',
                type: 'success'
            );
            $this->jumlahBayar = 0;
        } finally {
            $this->processing = false;
        }
    }
}
