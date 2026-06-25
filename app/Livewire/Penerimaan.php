<?php

namespace App\Livewire;

use App\Models\Gudang;
use App\Models\Pembelian;
use App\Models\Barang;
use App\Models\Penerimaan as ModelsPenerimaan;
use App\Models\Jurnal;
use App\Models\Akun;
use App\Models\JurnalDetail;
use App\Models\Stok;
use App\Models\Ppn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Penerimaan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $no_penerimaan, $tanggal_terima, $pembelian_id, $gudang_id, $diterima_oleh;
    public $penerimaan_id;
    public $isOpen = false;
    public $search = '';
    public $startDate, $endDate;
    public $isShow = false;
    public $poSearch = ''; // Search for PO
    public $details = [];

    // Financial details (Editable, initialized from PO)
    public $ppn = 0;
    public $diskon = 0;
    public $biaya_lain = 0;
    public $ppn_rate = 0; // PPN rate for calculation
    public $ppn_master_id = null; // Selected PPN master ID


    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'no_penerimaan' => 'required|unique:penerimaan,no_penerimaan',
        'tanggal_terima' => 'required|date',
        'pembelian_id' => 'required|exists:pembelian,id',
        'gudang_id' => 'required|exists:gudang,id',
        'diterima_oleh' => 'required',
        'details.*.barang_id' => 'required|exists:barang,id',
        'details.*.qty_diterima' => 'required|numeric|min:0',
        'details.*.harga_satuan' => 'required|numeric|min:0',
        'details.*.diskon' => 'nullable|numeric|min:0',
        'details.*.ppn' => 'nullable|numeric|min:0',
        'ppn' => 'nullable|numeric|min:0',
        'diskon' => 'nullable|numeric|min:0',
        'biaya_lain' => 'nullable|numeric|min:0',
    ];

    public function render()
    {
        $penerimaans = ModelsPenerimaan::with(['pembelian.supplier', 'gudang'])
            ->where(function ($query) {
                $query->whereRaw('LOWER(no_penerimaan) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereHas('pembelian.supplier', function ($q) {
                        $q->whereRaw('LOWER(nama_supplier) LIKE ?', ['%' . strtolower($this->search) . '%']);
                    });
            })
            ->whereBetween('tanggal_terima', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Optimized PO fetching
        $pembelians = [];
        if (strlen($this->poSearch) >= 2) {
            $pembelians = Pembelian::where('status', 'approved')
                ->whereDoesntHave('penerimaan')
                ->where(function($q) {
                    $q->whereRaw('LOWER(no_po) LIKE ?', ['%' . strtolower($this->poSearch) . '%'])
                      ->orWhereHas('supplier', function($sq) {
                          $sq->whereRaw('LOWER(nama_supplier) LIKE ?', ['%' . strtolower($this->poSearch) . '%']);
                      });
                })
                ->limit(10)->get();
            
            // If already selected, hide dropdown
            if (count($pembelians) == 1 && $pembelians[0]->id == $this->pembelian_id && $pembelians[0]->no_po == $this->poSearch) {
                $pembelians = [];
            }
        } elseif ($this->pembelian_id) {
            $pembelians = Pembelian::where('id', $this->pembelian_id)->get();
        }

        $barangs = Barang::all();
        $gudangs = Gudang::all();
        
        // Get active PPN masters for dropdown
        $ppnMasters = Ppn::active()->orderBy('rate', 'asc')->get();

        return view('livewire.penerimaan.index', compact('penerimaans', 'pembelians', 'gudangs', 'barangs', 'ppnMasters'));
    }

    public function selectPO($id, $no_po)
    {
        $this->pembelian_id = $id;
        $this->poSearch = $no_po;
        $this->updatedPembelianId($id);
    }

    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');

        // Check if there's a penerimaan to show from session
        if (session()->has('showPenerimaanId')) {
            $penerimaanId = session()->pull('showPenerimaanId');
            $this->show($penerimaanId);
        }
    }

    public function updatedPembelianId($value)
    {
        if ($value) {
            $pembelian = Pembelian::with(['details.barang'])->find($value);
            if ($pembelian) {
                // Initialize from PO
                $this->ppn = $pembelian->ppn;
                $this->diskon = $pembelian->diskon;
                $this->biaya_lain = $pembelian->biaya_lain;
                $this->ppn_master_id = $pembelian->ppn_master_id;
                
                // Calculate reverse PPN Rate from PO
                $poSubtotal = $pembelian->details->sum('subtotal');
                $diskon_persen = $pembelian->diskon ?: 0;
                $diskon_amount = $poSubtotal * ($diskon_persen / 100);
                $taxable = max(0, $poSubtotal - $diskon_amount);
                if ($taxable > 0 && $pembelian->ppn > 0) {
                    $this->ppn_rate = round(($pembelian->ppn / $taxable) * 100, 2);
                } else {
                    $this->ppn_rate = 0;
                }

                $this->details = $pembelian->details->map(function ($detail) {
                    $qty = $detail->qty;
                    $harga = $detail->harga_satuan;
                    $diskon_persen = $detail->diskon ?? 0; // percentage
                    $ppn_rate = $detail->ppn ?? 0; // percentage
                    
                    // Calculate with percentage discount
                    $total_harga = $qty * $harga;
                    $diskon_amount = $total_harga * ($diskon_persen / 100);
                    $dpp = $total_harga - $diskon_amount;
                    $ppn_amount = $dpp * ($ppn_rate / 100);
                    $subtotal = $dpp + $ppn_amount;
                    
                    return [
                        'barang_id' => $detail->barang_id,
                        'nama_barang' => $detail->barang->nama_barang ?? 'Unknown',
                        'qty_po' => $qty,
                        'qty_diterima' => $qty, // Default to full receipt
                        'harga_satuan' => $harga,
                        'diskon' => $diskon_persen, 
                        'ppn' => $ppn_rate,
                        'subtotal' => $subtotal,
                    ];
                })->toArray();
            } else {
                $this->resetPOFields();
            }
        } else {
            $this->resetPOFields();
        }
    }
    
    public function updatedDetails($value, $key)
    {
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if ($field === 'barang_id') {
            $barang = Barang::find($value);
            if ($barang) {
                $this->details[$index]['nama_barang'] = $barang->nama_barang;
                $this->details[$index]['harga_satuan'] = $barang->harga_beli_terakhir ?? 0;
            } else {
                $this->details[$index]['nama_barang'] = '';
                $this->details[$index]['harga_satuan'] = 0;
            }
            $qty = (float) ($this->details[$index]['qty_diterima'] ?? 0);
            $harga = (float) ($this->details[$index]['harga_satuan'] ?? 0);
            $diskon_persen = (float) ($this->details[$index]['diskon'] ?? 0);
            $ppn_rate = (float) ($this->details[$index]['ppn'] ?? 0);
            
            // Calculate with percentage discount
            $total_harga = $qty * $harga;
            $diskon_amount = $total_harga * ($diskon_persen / 100);
            $dpp = $total_harga - $diskon_amount;
            $this->details[$index]['subtotal'] = $dpp + ($dpp * ($ppn_rate / 100));
        }

        if (in_array($field, ['qty_diterima', 'harga_satuan', 'diskon', 'ppn'])) {
            $qty = (float) ($this->details[$index]['qty_diterima'] ?? 0);
            $harga = (float) ($this->details[$index]['harga_satuan'] ?? 0);
            $diskon_persen = (float) ($this->details[$index]['diskon'] ?? 0);
            $ppn_rate = (float) ($this->details[$index]['ppn'] ?? 0);
            
            // Calculate with percentage discount
            $total_harga = $qty * $harga;
            $diskon_amount = $total_harga * ($diskon_persen / 100);
            $dpp = $total_harga - $diskon_amount;
            
            // Calculate PPN Amount based on percentage
            $ppn_amount = $dpp * ($ppn_rate / 100);
            
            // Subtotal = DPP + PPN Amount
            $this->details[$index]['subtotal'] = $dpp + $ppn_amount;
        }
    }
    
    public function calculateGlobalPPN()
    {
        $baseAmount = 0;
        foreach ($this->details as $detail) {
            $qty = (float) ($detail['qty_diterima'] ?? 0);
            $harga = (float) ($detail['harga_satuan'] ?? 0);
            $diskon_persen = (float) ($detail['diskon'] ?? 0);
            // Base amount for global tax is usually (Gross - Item Discounts)
            $total_harga = $qty * $harga;
            $diskon_amount = $total_harga * ($diskon_persen / 100);
            $baseAmount += $total_harga - $diskon_amount;
        }
        
        $diskon_persen = $this->diskon ?: 0;
        $diskon_amount = $baseAmount * ($diskon_persen / 100);
        $taxable = max(0, $baseAmount - $diskon_amount);
        $this->ppn = round($taxable * ($this->ppn_rate / 100), 2);
    }

    public function calculateGlobalPPNWithRate($rate)
    {
        $this->ppn_rate = $rate;
        $this->calculateGlobalPPN();
    }


    private function resetPOFields()
    {
        $this->details = [];
        $this->ppn = 0;
        $this->diskon = 0;
        $this->biaya_lain = 0;
        $this->ppn_rate = 0;
        $this->ppn_master_id = null;
    }

    public function getCalculatedTotalProperty()
    {
        $subtotal = collect($this->details)->sum('subtotal');
        return $subtotal - $this->diskon + $this->ppn + $this->biaya_lain;
    }
    
    public function getSubTotalProperty()
    {
        return collect($this->details)->sum('subtotal');
    }

    public function show($id)
    {
        $this->loadPenerimaan($id, true);
    }

    public function edit($id)
    {
        $this->loadPenerimaan($id, false);
    }
    
    private function loadPenerimaan($id, $readonly)
    {
        $penerimaan = ModelsPenerimaan::with(['pembelian.supplier', 'pembelian.details', 'gudang', 'details.barang'])->findOrFail($id);

        $this->penerimaan_id = $penerimaan->id;
        $this->no_penerimaan = $penerimaan->no_penerimaan;
        $this->tanggal_terima = $penerimaan->tanggal_terima ? $penerimaan->tanggal_terima->format('Y-m-d') : null;
        $this->pembelian_id = $penerimaan->pembelian_id;
        $this->poSearch = $penerimaan->pembelian->no_po ?? '';
        $this->gudang_id = $penerimaan->gudang_id;
        $this->diterima_oleh = $penerimaan->diterima_oleh;
        
        // Load financials from Penerimaan record (not PO)
        $this->ppn = $penerimaan->ppn;
        $this->diskon = $penerimaan->diskon;
        $this->biaya_lain = $penerimaan->biaya_lain;

        $this->details = $penerimaan->details->map(function ($detail) use ($penerimaan) {
            $qty_po = 0;
            if ($penerimaan->pembelian) {
                $poDetail = $penerimaan->pembelian->details->where('barang_id', $detail->barang_id)->first();
                $qty_po = $poDetail ? $poDetail->qty : 0;
            }

            return [
                'barang_id' => $detail->barang_id,
                'nama_barang' => $detail->barang->nama_barang ?? 'Unknown',
                'qty_po' => $qty_po,
                'qty_diterima' => $detail->qty_diterima,
                'harga_satuan' => $detail->harga_satuan,
                'diskon' => $detail->diskon,
                'ppn' => $detail->ppn,
                'subtotal' => $detail->subtotal,
            ];
        })->toArray();

        $this->isShow = $readonly;
        $this->isOpen = true;
    }

    public function create()
    {
        $this->resetInputFields();
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
        $this->poSearch = '';
    }

    private function resetInputFields()
    {
        $this->penerimaan_id = null;
        $this->no_penerimaan = '';
        $this->tanggal_terima = date('Y-m-d');
        $this->pembelian_id = '';
        $this->gudang_id = '';
        $this->diterima_oleh = '';
        $this->resetPOFields();
    }

    public function store()
    {
        $this->validate(
            $this->penerimaan_id
                ? array_merge($this->rules, ['no_penerimaan' => 'required|unique:penerimaan,no_penerimaan,' . $this->penerimaan_id])
                : $this->rules
        );

        DB::transaction(function () {
            $userId = auth()->id();

            $penerimaan = ModelsPenerimaan::updateOrCreate(
                ['id' => $this->penerimaan_id],
                [
                    'no_penerimaan' => $this->no_penerimaan,
                    'tanggal_terima' => $this->tanggal_terima,
                    'pembelian_id' => $this->pembelian_id,
                    'gudang_id' => $this->gudang_id,
                    'diterima_oleh' => $this->diterima_oleh,
                    'ppn' => $this->ppn,
                    'diskon' => $this->diskon,
                    'biaya_lain' => $this->biaya_lain,
                    'updated_user' => $userId,
                ]
            );

            if (!$this->penerimaan_id) {
                $penerimaan->inserted_user = $userId;
                $penerimaan->save();
            }

            $penerimaan->details()->delete();

            $pembelian = Pembelian::find($this->pembelian_id);
            
            foreach ($this->details as $detail) {
                $qty_rec = (float) $detail['qty_diterima'];
                $harga = (float) ($detail['harga_satuan'] ?? 0);
                $diskon_persen = (float) ($detail['diskon'] ?? 0);
                $ppn_rate = (float) ($detail['ppn'] ?? 0);
                
                // Calculate with percentage discount
                $total_harga = $qty_rec * $harga;
                $diskon_amount = $total_harga * ($diskon_persen / 100);
                $dpp = $total_harga - $diskon_amount;
                // Calculate PPN Amount
                $ppn_amount = $dpp * ($ppn_rate / 100);
                // Subtotal
                $subtotal = $dpp + $ppn_amount;

                $penerimaan->details()->create([
                    'barang_id' => $detail['barang_id'],
                    'qty_diterima' => $qty_rec,
                    'harga_satuan' => $harga,
                    'diskon' => $diskon_persen,
                    'ppn' => $ppn_rate, // Store the rate
                    'subtotal' => $subtotal,
                ]);
            }
            
            // Total Value for Journal is now explicitly calculated from the saved values
            $total_value = $this->getCalculatedTotalProperty();

            if (!$this->penerimaan_id) {
                foreach ($this->details as $detail) {
                    $stok = Stok::firstOrNew([
                        'barang_id' => $detail['barang_id'],
                        'gudang_id' => $this->gudang_id,
                    ]);
                    $stok->stok_akhir = ($stok->stok_akhir ?? 0) + $detail['qty_diterima'];
                    $stok->save();

                    // Log to Kartu Stok
                    \App\Models\KartuStok::create([
                        'tanggal' => $this->tanggal_terima,
                        'barang_id' => $detail['barang_id'],
                        'gudang_id' => $this->gudang_id,
                        'jenis_transaksi' => 'masuk',
                        'qty_masuk' => $detail['qty_diterima'],
                        'qty_keluar' => 0,
                        'stok_akhir' => $stok->stok_akhir,
                        'referensi_id' => $penerimaan->id,
                        'referensi_tipe' => 'Penerimaan',
                        'keterangan' => 'Penerimaan No ' . $this->no_penerimaan,
                        'inserted_user' => $userId,
                    ]);
                }
                
                if ($pembelian) {
                    $pembelian->status = 'received';
                    $pembelian->save();
                }

                $jurnal = Jurnal::create([
                    'no_jurnal' => 'PB-' . str_pad($penerimaan->id, 5, '0', STR_PAD_LEFT),
                    'tanggal' => $this->tanggal_terima,
                    'keterangan' => 'Penerimaan Barang dari Pembelian No ' . ($pembelian->no_po ?? '-'),
                    'referensi_id' => $penerimaan->id,
                    'referensi_tipe' => 'Penerimaan',
                    'inserted_user' => $userId,
                ]);

                $jurnal->details()->create([
                    'akun_id' => 4,
                    'nama_akun' => 'Persediaan Barang',
                    'debit' => $total_value,
                    'kredit' => 0,
                ]);

                $jurnal->details()->create([
                    'akun_id' => 5,
                    'nama_akun' => 'Hutang Usaha',
                    'debit' => 0,
                    'kredit' => $total_value,
                ]);
            }
        });

        $message = $this->penerimaan_id
            ? 'Penerimaan updated successfully.'
            : 'Penerimaan created successfully.';

        $this->dispatch('notify', message: $message, type: 'success');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $penerimaan = ModelsPenerimaan::findOrFail($id);
        $penerimaan->update(['deleted_by' => Auth::id()]);
        $penerimaan->delete();
        $this->dispatch('notify', message: 'Penerimaan deleted successfully.', type: 'success');
    }
}
