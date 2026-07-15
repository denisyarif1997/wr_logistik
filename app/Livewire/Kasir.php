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
use Livewire\Component;

class Kasir extends Component
{
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

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
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

        return view('livewire.kasir', compact('gudangs', 'kasAccounts', 'ppnMasters', 'subtotal', 'total', 'kembalian'));
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
     * Hitung harga jual barang berdasarkan harga beli + margin (%).
     * Fallback ke harga_jual jika margin kosong.
     */
    public static function hitungHargaJual($barang)
    {
        $basePrice = $barang->harga_beli_terakhir ?? 0;
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
            return;
        }

        $this->addToCart($barang);
        $this->barcode = '';
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

        // Reload default PPN
        $defaultPpn = Ppn::getCurrentRate();
        if ($defaultPpn) {
            $this->ppn_rate = $defaultPpn->rate;
            $this->ppn_id = $defaultPpn->id;
        }
    }

    public function updatedGudangId()
    {
        $this->cart = [];
    }

    public function bayarSekarang()
    {
        $this->validate();
        $total = $this->getTotalProperty();

        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Cart kosong.', type: 'error');
            return;
        }

        if ($this->bayar < $total) {
            $this->dispatch('notify', message: 'Uang bayar kurang dari total.', type: 'error');
            return;
        }

        if (!$this->kas_id) {
            $this->dispatch('notify', message: 'Silakan pilih Kas / Rekening tujuan terlebih dahulu.', type: 'error');
            return;
        }

        DB::transaction(function () use ($total) {
            $userId = Auth::id();
            $kembalian = $this->bayar - $total;

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
                'status' => 'selesai',
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

                // Kurangi stok
                $stok = Stok::where('barang_id', $item['barang_id'])
                    ->where('gudang_id', $this->gudang_id)
                    ->first();
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

            // Jurnal: Debit -> Kas, Kredit -> Pendapatan
            $kas = Akun::find($this->kas_id);
            $jurnal = Jurnal::create([
                'no_jurnal' => 'PJL-' . str_pad($penjualan->id, 5, '0', STR_PAD_LEFT),
                'tanggal' => $this->tanggal,
                'keterangan' => 'Penjualan ' . $noPenjualan,
                'referensi_id' => $penjualan->id,
                'referensi_tipe' => 'Penjualan',
                'inserted_user' => $userId,
            ]);

            $jurnal->details()->create([
                'akun_id' => $this->kas_id,
                'nama_akun' => $kas ? $kas->nama_akun : 'Kas',
                'debit' => $total,
                'kredit' => 0,
            ]);
            $jurnal->details()->create([
                'akun_id' => 10,
                'nama_akun' => 'Pendapatan dari Penjualan Barang',
                'debit' => 0,
                'kredit' => $total,
            ]);

            $this->lastSale = $penjualan->load('details.barang');
        });

        $this->dispatch('notify', message: 'Penjualan berhasil disimpan.', type: 'success');
        $this->cart = [];
        $this->bayar = 0;
        $this->keterangan = '';
        $this->barcode = '';
        $this->diskon = 0;
        $this->ppn_rate = 0;
        $this->ppn_amount = 0;
        $this->ppn_id = null;

        $defaultPpn = Ppn::getCurrentRate();
        if ($defaultPpn) {
            $this->ppn_rate = $defaultPpn->rate;
            $this->ppn_id = $defaultPpn->id;
        }
    }

    public function generateNoPenjualan()
    {
        $today = now()->format('Ymd');
        $prefix = 'PJL-' . $today . '-';

        $last = ModelsPenjualan::where('no_penjualan', 'like', $prefix . '%')
            ->orderBy('no_penjualan', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->no_penjualan, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }
}