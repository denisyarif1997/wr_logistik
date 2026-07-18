<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\StockOpname as ModelsStockOpname;
use App\Models\StockOpnameDetail;
use App\Models\Stok;
use App\Models\KartuStok;
use App\Models\Jurnal;
use App\Models\Akun;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;


class StockOpname extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $stock_opname_id, $no_opname, $tanggal, $gudang_id, $jenis, $keterangan, $status;
    public $isOpen = false;
    public $isShow = false;
    public $search = '';
    public $startDate, $endDate;
    public $details = [];
    public $barangSearch = [];

    protected $rules = [
        'no_opname' => 'required|unique:stock_opname,no_opname',
        'tanggal' => 'required|date',
        'gudang_id' => 'required|exists:gudang,id',
        'jenis' => 'required|in:tambah,kurang,rusak,hilang',
        'keterangan' => 'nullable|string',
        'status' => 'required|in:draft,approved',
        'details.*.barang_id' => 'required|exists:barang,id',
        'details.*.qty_fisik' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->tanggal = date('Y-m-d');
        $this->jenis = 'tambah';
    }

    public function addDetail()
    {
        $this->details[] = ['barang_id' => '', 'qty_fisik' => 0, 'keterangan' => ''];
        $this->barangSearch[count($this->details) - 1] = '';
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);
        unset($this->barangSearch[$index]);
        $this->details = array_values($this->details);
        $this->barangSearch = array_values($this->barangSearch);
    }

    public function selectBarang($index, $id, $name)
    {
        $this->details[$index]['barang_id'] = $id;
        $this->barangSearch[$index] = $name;
        
        // Auto-fill qty_sistem from current stock
        $stok = Stok::where('barang_id', $id)
            ->where('gudang_id', $this->gudang_id)
            ->first();
        $this->details[$index]['qty_sistem'] = $stok ? $stok->stok_akhir : 0;
        $this->details[$index]['harga_satuan'] = Barang::find($id)->hpp ?? 0;
        
        // Calculate selisih
        $this->calculateSelisih($index);
    }

    public function calculateSelisih($index)
    {
        $qty_sistem = (float) ($this->details[$index]['qty_sistem'] ?? 0);
        $qty_fisik = (float) ($this->details[$index]['qty_fisik'] ?? 0);
        $harga = (float) ($this->details[$index]['harga_satuan'] ?? 0);
        
        $selisih = $qty_fisik - $qty_sistem;
        $this->details[$index]['selisih'] = $selisih;
        $this->details[$index]['total_selisih'] = abs($selisih) * $harga;
    }

    public function render()
    {
        $stockOpnames = ModelsStockOpname::with(['gudang', 'creator', 'details.barang'])
            ->where(function ($query) {
                $query->whereRaw('LOWER(no_opname) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereRaw('LOWER(jenis) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereHas('gudang', function ($q) {
                        $q->whereRaw('LOWER(nama_gudang) LIKE ?', ['%' . strtolower($this->search) . '%']);
                    });
            })
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $barangResults = [];
        if ($this->gudang_id) {
            foreach ($this->barangSearch as $index => $term) {
                if (strlen($term) >= 2) {
                    $results = Barang::whereHas('stok', function ($q) {
                        $q->where('gudang_id', $this->gudang_id);
                    })
                        ->with(['stok' => fn($q) => $q->where('gudang_id', $this->gudang_id)])
                        ->whereRaw('LOWER(nama_barang) LIKE ?', ['%' . strtolower($term) . '%'])
                        ->limit(10)->get();

                    if ($results->count() == 1 && isset($this->details[$index]['barang_id']) && $results->first()->id == $this->details[$index]['barang_id'] && $results->first()->nama_barang == $term) {
                        $barangResults[$index] = [];
                    } else {
                        $barangResults[$index] = $results;
                    }
                } else {
                    $barangResults[$index] = [];
                }
            }
        }

        $gudangs = Gudang::all();

        return view('livewire.stock-opname.index', compact('stockOpnames', 'barangResults', 'gudangs'));
    }

    public function generateNoOpname()
    {
        $today = now()->format('Ymd');
        $prefix = 'OPN-' . $today . '-';

        $last = ModelsStockOpname::whereRaw('LOWER(no_opname) LIKE ?', [strtolower($prefix) . '%'])
            ->orderBy('no_opname', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->no_opname, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->no_opname = $this->generateNoOpname();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
        $this->isShow = false;
    }

    private function resetInputFields()
    {
        $this->stock_opname_id = null;
        $this->no_opname = '';
        $this->tanggal = date('Y-m-d');
        $this->gudang_id = '';
        $this->jenis = 'tambah';
        $this->keterangan = '';
        $this->status = 'draft';
        $this->details = [];
        $this->barangSearch = [];
    }

    public function edit($id)
    {

        Gate::authorize('so-edit');
        $stockOpname = ModelsStockOpname::with('details.barang', 'gudang')->findOrFail($id);

        $this->stock_opname_id = $stockOpname->id;
        $this->no_opname = $stockOpname->no_opname;
        $this->tanggal = $stockOpname->tanggal;
        $this->gudang_id = $stockOpname->gudang_id;
        $this->jenis = $stockOpname->jenis;
        $this->keterangan = $stockOpname->keterangan;
        $this->status = $stockOpname->status;

        $this->details = [];
        $this->barangSearch = [];
        foreach ($stockOpname->details as $index => $detail) {
            $this->details[$index] = [
                'id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'qty_sistem' => $detail->qty_sistem,
                'qty_fisik' => $detail->qty_fisik,
                'selisih' => $detail->selisih,
                'harga_satuan' => $detail->harga_satuan,
                'total_selisih' => $detail->total_selisih,
                'keterangan' => $detail->keterangan,
            ];
            $this->barangSearch[$index] = $detail->barang->nama_barang ?? '';
        }

        $this->openModal();
    }

    public function show($id)
    {
        $stockOpname = ModelsStockOpname::with('details.barang', 'gudang')->findOrFail($id);

        $this->stock_opname_id = $stockOpname->id;
        $this->no_opname = $stockOpname->no_opname;
        $this->tanggal = $stockOpname->tanggal ? $stockOpname->tanggal->format('Y-m-d') : null;
        $this->gudang_id = $stockOpname->gudang_id;
        $this->jenis = $stockOpname->jenis;
        $this->keterangan = $stockOpname->keterangan;
        $this->status = $stockOpname->status;

        $this->details = [];
        $this->barangSearch = [];
        foreach ($stockOpname->details as $index => $detail) {
            $this->details[$index] = [
                'id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'qty_sistem' => $detail->qty_sistem,
                'qty_fisik' => $detail->qty_fisik,
                'selisih' => $detail->selisih,
                'harga_satuan' => $detail->harga_satuan,
                'total_selisih' => $detail->total_selisih,
                'keterangan' => $detail->keterangan,
            ];
            $this->barangSearch[$index] = $detail->barang->nama_barang ?? '';
        }

        $this->isShow = true;
        $this->isOpen = true;
    }

    public function store()
    {
        // Gate::authorize('so-proses');
        $this->validate(
            $this->stock_opname_id
                ? array_merge($this->rules, ['no_opname' => 'required|unique:stock_opname,no_opname,' . $this->stock_opname_id])
                : $this->rules
        );

        DB::transaction(function () {
            $userId = Auth::id();

            $stockOpname = ModelsStockOpname::updateOrCreate(
                ['id' => $this->stock_opname_id],
                [
                    'no_opname' => $this->no_opname,
                    'tanggal' => $this->tanggal,
                    'gudang_id' => $this->gudang_id,
                    'jenis' => $this->jenis,
                    'keterangan' => $this->keterangan,
                    'status' => $this->status,
                    'updated_user' => $userId,
                ]
            );

            if (!$this->stock_opname_id) {
                $stockOpname->inserted_user = $userId;
                $stockOpname->save();
            }

            $stockOpname->details()->delete();

            foreach ($this->details as $detail) {
                $qty_sistem = (int) ($detail['qty_sistem'] ?? 0);
                $qty_fisik = (int) ($detail['qty_fisik'] ?? 0);
                $selisih = $qty_fisik - $qty_sistem;
                $harga = (float) ($detail['harga_satuan'] ?? 0);

                $stockOpname->details()->create([
                    'barang_id' => $detail['barang_id'],
                    'qty_sistem' => $qty_sistem,
                    'qty_fisik' => $qty_fisik,
                    'selisih' => $selisih,
                    'harga_satuan' => $harga,
                    'total_selisih' => abs($selisih) * $harga,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }
        });

        $message = $this->stock_opname_id
            ? 'Stock Opname berhasil diperbarui.'
            : 'Stock Opname berhasil disimpan.';

        $this->dispatch('notify', message: $message, type: 'success');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $stockOpname = ModelsStockOpname::findOrFail($id);
        $stockOpname->update(['deleted_by' => Auth::id()]);
        $stockOpname->delete();
        $this->dispatch('notify', message: 'Stock Opname berhasil dihapus.', type: 'success');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Approve stock opname -> update stock & create kartu stok
     */
    public function validateStockOpname($id)
    {
        // Gate::authorize('so-proses');
        if (Gate::denies('so-proses')) {
         $this->dispatch('notify',
         message: 'Anda tidak mempunyai hak akses!',
            type: 'error'
        );
        return;
        }
        
        $stockOpname = ModelsStockOpname::with('details')->find($id);

        if ($stockOpname && strtolower($stockOpname->status) === 'draft') {
            DB::transaction(function () use ($stockOpname) {
                $totalSelisih = 0;
                
                foreach ($stockOpname->details as $detail) {
                    $stok = Stok::where('barang_id', $detail->barang_id)
                        ->where('gudang_id', $stockOpname->gudang_id)
                        ->first();

                    if (!$stok) {
                        $stok = Stok::create([
                            'barang_id' => $detail->barang_id,
                            'gudang_id' => $stockOpname->gudang_id,
                            'stok_akhir' => 0,
                        ]);
                    }

                    $selisih = $detail->selisih;
                    $stok->stok_akhir += $selisih;
                    if ($stok->stok_akhir < 0) {
                        $stok->stok_akhir = 0;
                    }
                    $stok->save();

                    $jenisLabel = match ($stockOpname->jenis) {
                        'tambah' => 'Stock Opname Tambah',
                        'kurang' => 'Stock Opname Kurang',
                        'rusak' => 'Stock Opname Rusak',
                        'hilang' => 'Stock Opname Hilang',
                        default => 'Stock Opname',
                    };

                    $jenisTransaksi = $selisih >= 0 ? 'masuk' : 'keluar';
                    $qtyMasuk = $selisih >= 0 ? $selisih : 0;
                    $qtyKeluar = $selisih < 0 ? abs($selisih) : 0;

                    KartuStok::create([
                        'tanggal' => $stockOpname->tanggal,
                        'barang_id' => $detail->barang_id,
                        'gudang_id' => $stockOpname->gudang_id,
                        'jenis_transaksi' => $jenisTransaksi,
                        'qty_masuk' => $qtyMasuk,
                        'qty_keluar' => $qtyKeluar,
                        'stok_akhir' => $stok->stok_akhir,
                        'referensi_id' => $stockOpname->id,
                        'referensi_tipe' => 'StockOpname',
                        'keterangan' => $jenisLabel . ' - ' . $stockOpname->no_opname,
                        'inserted_user' => auth()->id(),
                    ]);

                    $totalSelisih += $detail->total_selisih;
                }

                $stockOpname->status = 'approved';
                $stockOpname->updated_user = auth()->id();
                $stockOpname->save();

                $this->createJurnalStockOpname($stockOpname, $totalSelisih);
            });

            $this->dispatch('notify', message: 'Stock Opname berhasil di-approve.', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Approval gagal. Status stock opname tidak sesuai.', type: 'error');
        }
    }

    /**
     * Unapprove stock opname -> revert stock
     */
    public function unvalidasi($id)
    {
        // Gate::authorize('so-cancelproses');

        if (Gate::denies('so-cancelproses')) {
         $this->dispatch('notify',
         message: 'Anda tidak mempunyai hak akses!',
            type: 'error'
        );
        return;
        }
        $stockOpname = ModelsStockOpname::with('details')->find($id);

        if ($stockOpname && strtolower($stockOpname->status) === 'approved') {
            DB::transaction(function () use ($stockOpname) {
                foreach ($stockOpname->details as $detail) {
                    $stok = Stok::where('barang_id', $detail->barang_id)
                        ->where('gudang_id', $stockOpname->gudang_id)
                        ->first();

                    if ($stok) {
                        $stok->stok_akhir -= $detail->selisih;
                        if ($stok->stok_akhir < 0) {
                            $stok->stok_akhir = 0;
                        }
                        $stok->save();

                        KartuStok::create([
                            'tanggal' => date('Y-m-d'),
                            'barang_id' => $detail->barang_id,
                            'gudang_id' => $stockOpname->gudang_id,
                            'jenis_transaksi' => 'masuk',
                            'qty_masuk' => abs($detail->selisih) < $detail->selisih ? 0 : abs($detail->selisih),
                            'qty_keluar' => abs($detail->selisih) < $detail->selisih ? abs($detail->selisih) : 0,
                            'stok_akhir' => $stok->stok_akhir,
                            'referensi_id' => $stockOpname->id,
                            'referensi_tipe' => 'StockOpname',
                            'keterangan' => 'Batal Approve Stock Opname ' . $stockOpname->no_opname,
                            'inserted_user' => auth()->id(),
                        ]);
                    }
                }

                $stockOpname->status = 'draft';
                $stockOpname->updated_user = auth()->id();
                $stockOpname->save();

                // Delete journal
                $jurnalLama = Jurnal::where('referensi_tipe', 'StockOpname')
                    ->where('referensi_id', $stockOpname->id)
                    ->first();
                if ($jurnalLama) {
                    $jurnalLama->details()->delete();
                    $jurnalLama->delete();
                }
            });

            $this->dispatch('notify', message: 'Approval stock opname berhasil dibatalkan.', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Unapprove gagal. Status stock opname tidak sesuai.', type: 'error');
        }
    }

    /**
     * Create journal for stock opname
     */
    private function createJurnalStockOpname($stockOpname, $totalSelisih)
    {
        $jurnal = Jurnal::create([
            'no_jurnal' => 'OPN-' . str_pad($stockOpname->id, 5, '0', STR_PAD_LEFT),
            'tanggal' => $stockOpname->tanggal,
            'keterangan' => 'Stock Opname - ' . $stockOpname->no_opname,
            'referensi_id' => $stockOpname->id,
            'referensi_tipe' => 'StockOpname',
            'inserted_user' => auth()->id(),
        ]);

        // Get COA from settings
        $coaPersediaan = Setting::getValue('coa_persediaan', 4);
        $coaBeban = Setting::getValue('coa_beban_operasional', 6);
        
        $akunPersediaan = Akun::find($coaPersediaan);
        $akunBeban = Akun::find($coaBeban);

        if ($stockOpname->jenis === 'tambah') {
            // Debit: Persediaan Barang, Kredit: Kas/Beban
            $jurnal->details()->create([
                'akun_id' => $coaPersediaan,
                'nama_akun' => $akunPersediaan ? $akunPersediaan->nama_akun : 'Persediaan Barang',
                'debit' => $totalSelisih,
                'kredit' => 0,
            ]);
        } else {
            // Debit: Beban, Kredit: Persediaan Barang
            $jurnal->details()->create([
                'akun_id' => $coaBeban,
                'nama_akun' => $akunBeban ? $akunBeban->nama_akun : 'Beban Operasional',
                'debit' => $totalSelisih,
                'kredit' => 0,
            ]);
            
            $jurnal->details()->create([
                'akun_id' => $coaPersediaan,
                'nama_akun' => $akunPersediaan ? $akunPersediaan->nama_akun : 'Persediaan Barang',
                'debit' => 0,
                'kredit' => $totalSelisih,
            ]);
        }
    }
}