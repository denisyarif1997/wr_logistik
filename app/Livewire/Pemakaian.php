<?php

namespace App\Livewire;

use App\Models\{Barang, Departemen, Gudang, Pemakaian as ModelsPemakaian, PemakaianDetail, Stok, Jurnal, JurnalDetail, KartuStok, PenerimaanDetail};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class Pemakaian extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $pemakaian_id, $no_pemakaian, $tanggal_pakai, $departemen_id, $gudang_id, $diajukan_oleh;
    public $search = '', $isOpen = false, $viewing = null;
    public $startDate, $endDate;
    public $details = [];
    public $barangSearch = []; // Track search per row

    protected $rules = [
        'no_pemakaian' => 'required|unique:pemakaian,no_pemakaian',
        'tanggal_pakai' => 'required|date',
        'departemen_id' => 'required|exists:departemen,id',
        'gudang_id' => 'required|exists:gudang,id',
        'diajukan_oleh' => 'required',
        'details.*.barang_id' => 'required|exists:barang,id',
        'details.*.qty' => 'required|numeric|min:1',
    ];

    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->tanggal_pakai = date('Y-m-d');
        $this->resetForm();
    }

    public function render()
    {
        $pemakaians = ModelsPemakaian::with(['departemen', 'gudang', 'details.barang'])
            ->whereRaw('LOWER(no_pemakaian) LIKE ?', ['%' . strtolower($this->search) . '%'])
            ->whereBetween('tanggal_pakai', [$this->startDate, $this->endDate])
            ->latest()->paginate(10);

        // Optimized Barang fetching per row
        $barangResults = [];
        if ($this->gudang_id) {
            foreach ($this->barangSearch as $index => $term) {
                if (strlen($term) >= 2) {
                    $results = Barang::whereHas('stok', function ($q) {
                        $q->where('gudang_id', $this->gudang_id)->where('stok_akhir', '>', 0);
                    })
                    ->with(['stok' => fn($q) => $q->where('gudang_id', $this->gudang_id)])
                    ->whereRaw('LOWER(nama_barang) LIKE ?', ['%' . strtolower($term) . '%'])
                    ->limit(10)->get();

                    // If exact match with current selection, hide dropdown
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

        return view('livewire.pemakaian.index', [
            'pemakaians' => $pemakaians,
            // Only load the full Departemen/Gudang lists when the form modal is open,
            // otherwise they are queried on every list render for nothing.
            'departemens' => $this->isOpen ? Departemen::all() : collect(),
            'gudangs' => $this->isOpen ? Gudang::all() : collect(),
            'barangResults' => $barangResults,
        ]);
    }

    public function selectBarang($index, $id, $name)
    {
        $this->details[$index]['barang_id'] = $id;
        $this->barangSearch[$index] = $name;
    }

    public function updatingSearch() { $this->resetPage(); }

    public function updatedGudangId()
    {
        $this->reset(['details', 'barangSearch']);
        $this->addDetail();
    }

    public function addDetail() 
    { 
        $this->details[] = ['barang_id' => '', 'qty' => 1]; 
        $this->barangSearch[count($this->details) - 1] = '';
    }

    public function removeDetail($index) 
    { 
        unset($this->details[$index]); 
        unset($this->barangSearch[$index]);
        $this->details = array_values($this->details); 
        $this->barangSearch = array_values($this->barangSearch);
    }

    public function create() 
    { 
        $this->resetForm(); 
        $this->no_pemakaian = $this->generateNoPemakaian();
        $this->openModal(); 
    }

    public function generateNoPemakaian()
    {
        // Format: PMK-YYYYMMDD-001
        $today = now()->format('Ymd');
        $prefix = 'PMK-' . $today . '-';

        // Ambil nomor terakhir hari ini
        $last = ModelsPemakaian::whereRaw('LOWER(no_pemakaian) LIKE ?', [strtolower($prefix) . '%'])
            ->orderBy('no_pemakaian', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->no_pemakaian, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function edit($id)
    {
        $p = ModelsPemakaian::with('details.barang')->findOrFail($id);
        $this->fill($p->only('id', 'no_pemakaian', 'tanggal_pakai', 'departemen_id', 'gudang_id', 'diajukan_oleh'));
        $this->pemakaian_id = $p->id;
        
        $this->details = [];
        $this->barangSearch = [];
        foreach ($p->details as $index => $d) {
            $this->details[$index] = ['barang_id' => $d->barang_id, 'qty' => $d->qty];
            $this->barangSearch[$index] = $d->barang->nama_barang ?? '';
        }

        $this->openModal();
    }

    public function view($id) { $this->viewing = ModelsPemakaian::with('details.barang')->findOrFail($id); }
    public function closeViewModal() { $this->viewing = null; }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; }

    private function resetForm()
    {
        $this->reset(['pemakaian_id', 'no_pemakaian', 'departemen_id', 'gudang_id', 'diajukan_oleh', 'details', 'barangSearch']);
        $this->tanggal_pakai = date('Y-m-d');
        $this->addDetail();
    }

    private function validateStok($detail)
    {
        $stok = Stok::where('barang_id', $detail['barang_id'])->where('gudang_id', $this->gudang_id)->first();
        if (!$stok || $stok->stok_akhir < $detail['qty']) {
            $barang = Barang::find($detail['barang_id']);
            throw ValidationException::withMessages([
                'details.*.qty' => 'Stok barang ' . ($barang->nama_barang ?? 'tidak diketahui') . ' tidak mencukupi. Sisa: ' . ($stok->stok_akhir ?? 0),
            ]);
        }
    }

    public function store()
    {
        $rules = $this->rules;
        if ($this->pemakaian_id) {
            $rules['no_pemakaian'] = 'required|unique:pemakaian,no_pemakaian,' . $this->pemakaian_id;
        }
        $this->validate($rules);

        DB::transaction(function () {
            $details = collect($this->details)->groupBy('barang_id')->map(function ($items) {
                return ['barang_id' => $items[0]['barang_id'], 'qty' => $items->sum('qty')];
            })->values()->toArray();

            if ($this->pemakaian_id) {
                $old = ModelsPemakaian::with('details')->find($this->pemakaian_id);
                foreach ($old->details as $d) {
                    $stok = Stok::where('barang_id', $d->barang_id)->where('gudang_id', $old->gudang_id)->first();
                    if ($stok) { 
                        $stok->stok_akhir += $d->qty; 
                        $stok->save(); 

                        \App\Models\KartuStok::create([
                            'tanggal' => date('Y-m-d'),
                            'barang_id' => $d->barang_id,
                            'gudang_id' => $old->gudang_id,
                            'jenis_transaksi' => 'masuk',
                            'qty_masuk' => $d->qty,
                            'qty_keluar' => 0,
                            'stok_akhir' => $stok->stok_akhir,
                            'referensi_id' => $old->id,
                            'referensi_tipe' => 'Pemakaian',
                            'keterangan' => 'Koreksi Pemakaian (Edit) ' . $old->no_pemakaian,
                            'inserted_user' => auth()->id(),
                        ]);
                    }
                }
            }

            foreach ($details as $d) { $this->validateStok($d); }

            $pemakaian = ModelsPemakaian::updateOrCreate(
                ['id' => $this->pemakaian_id],
                [
                    'no_pemakaian' => $this->no_pemakaian,
                    'tanggal_pakai' => $this->tanggal_pakai,
                    'departemen_id' => $this->departemen_id,
                    'gudang_id' => $this->gudang_id,
                    'diajukan_oleh' => $this->diajukan_oleh,
                ]
            );

            $pemakaian->details()->delete();

            $total_nilai_pemakaian = 0;
            foreach ($details as $d) {
                $pemakaian->details()->create($d);
                $stok = Stok::where('barang_id', $d['barang_id'])->where('gudang_id', $this->gudang_id)->first();
                if ($stok) { 
                    $stok->stok_akhir -= $d['qty']; 
                    $stok->save(); 

                    \App\Models\KartuStok::create([
                        'tanggal' => $this->tanggal_pakai,
                        'barang_id' => $d['barang_id'],
                        'gudang_id' => $this->gudang_id,
                        'jenis_transaksi' => 'keluar',
                        'qty_masuk' => 0,
                        'qty_keluar' => $d['qty'],
                        'stok_akhir' => $stok->stok_akhir,
                        'referensi_id' => $pemakaian->id,
                        'referensi_tipe' => 'Pemakaian',
                        'keterangan' => 'Pemakaian No ' . $this->no_pemakaian,
                        'inserted_user' => auth()->id(),
                    ]);
                }
                // Calculate total value for Jurnal using HPP
                $barang = Barang::find($d['barang_id']);
                if ($barang && $barang->hpp) {
                    $total_nilai_pemakaian += $barang->hpp * $d['qty'];
                }
            }

            // Update HPP for all barang after stock changes
            $this->updateHppForAllBarang();

            // Create Jurnal entry for Pemakaian (only for new records)
            if (!$this->pemakaian_id && $total_nilai_pemakaian > 0) {
                $jurnal = Jurnal::create([
                    'no_jurnal' => 'PM-' . str_pad($pemakaian->id, 5, '0', STR_PAD_LEFT),
                    'tanggal' => $this->tanggal_pakai,
                    'keterangan' => 'Pemakaian Barang No ' . $this->no_pemakaian,
                    'referensi_id' => $pemakaian->id,
                    'referensi_tipe' => 'Pemakaian',
                    'inserted_user' => auth()->id(),
                ]);

                // Get COA from settings
                $coaPersediaan = \App\Models\Setting::getValue('coa_persediaan', 4);
                $coaBeban = \App\Models\Setting::getValue('coa_beban_pemakaian_barang', 6);
                
                $akunPersediaan = \App\Models\Akun::find($coaPersediaan);
                $akunBeban = \App\Models\Akun::find($coaBeban);

                // Debit: Beban Pemakaian
                $jurnal->details()->create([
                    'akun_id' => $coaBeban,
                    'nama_akun' => $akunBeban ? $akunBeban->nama_akun : 'Biaya Pemakaian Barang',
                    'debit' => $total_nilai_pemakaian,
                    'kredit' => 0,
                ]);

                // Credit: Persediaan Barang
                $jurnal->details()->create([
                    'akun_id' => $coaPersediaan,
                    'nama_akun' => $akunPersediaan ? $akunPersediaan->nama_akun : 'Persediaan Barang',
                    'debit' => 0,
                    'kredit' => $total_nilai_pemakaian,
                ]);
            }
        });

        $this->dispatch('notify', message: $this->pemakaian_id ? 'Pemakaian diperbarui.' : 'Pemakaian berhasil disimpan.', type: 'success');
        $this->closeModal();
        $this->resetForm();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $pemakaian = ModelsPemakaian::with('details')->findOrFail($id);
            foreach ($pemakaian->details as $d) {
                $stok = Stok::where('barang_id', $d->barang_id)->where('gudang_id', $pemakaian->gudang_id)->first();
                if ($stok) { 
                    $stok->stok_akhir += $d->qty; 
                    $stok->save(); 

                    \App\Models\KartuStok::create([
                        'tanggal' => date('Y-m-d'),
                        'barang_id' => $d->barang_id,
                        'gudang_id' => $pemakaian->gudang_id,
                        'jenis_transaksi' => 'masuk',
                        'qty_masuk' => $d->qty,
                        'qty_keluar' => 0,
                        'stok_akhir' => $stok->stok_akhir,
                        'referensi_id' => $pemakaian->id,
                        'referensi_tipe' => 'Pemakaian',
                        'keterangan' => 'Batal Pemakaian ' . $pemakaian->no_pemakaian,
                        'inserted_user' => auth()->id(),
                    ]);
                }
            }
            $pemakaian->details()->delete();
            $pemakaian->delete();
        });

        $this->dispatch('notify', message: 'Pemakaian berhasil dihapus.', type: 'success');
    }

    /**
     * Update HPP (Harga Pokok Penjualan) for all barang based on current stock
     * HPP is calculated as weighted average price from all stock movements
     */
    private function updateHppForAllBarang()
    {
        // Get all barang
        $barangs = Barang::all();
        
        foreach ($barangs as $barang) {
            // Get all stock entries for this barang to calculate total quantity
            $totalQty = Stok::where('barang_id', $barang->id)->sum('stok_akhir');
            
            if ($totalQty <= 0) {
                continue;
            }
            
            // Calculate weighted average price from all incoming stock movements
            $kartuStokMasuk = KartuStok::where('barang_id', $barang->id)
                ->where('jenis_transaksi', 'masuk')
                ->where('referensi_tipe', 'Penerimaan')
                ->get();
            
            $totalQtyForAvg = 0;
            $totalValueForAvg = 0;
            
            foreach ($kartuStokMasuk as $kartu) {
                // Get the penerimaan detail to find the purchase price
                $penerimaanDetail = PenerimaanDetail::where('penerimaan_id', $kartu->referensi_id)
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
}
