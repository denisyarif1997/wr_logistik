<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Pembelian as ModelsPembelian;
use App\Models\PembelianDetail;
use App\Models\Suppliers;
use App\Models\Ppn;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class Pembelian extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $no_po, $tanggal_po, $supplier_id, $status;
    public $ppn = 0, $diskon = 0, $biaya_lain = 0; // Added fields
    public $ppn_rate = 0; // PPN rate for calculation
    public $ppn_master_id = null; // Selected PPN master ID
    public $pembelian_id;
    public $isOpen = false;
    public $search = '';
    public $supplierSearch = '';
    public $startDate, $endDate;

    public $isShow = false;
    public $showPembelian;

    public $details = [];
    public $barangSearch = []; // Track search per row

    protected $rules = [
        'no_po' => 'required|unique:pembelian,no_po',
        'tanggal_po' => 'required|date',
        'supplier_id' => 'required|exists:suppliers,id',
        'status' => 'required|in:draft,approved,received,canceled',
        'details.*.barang_id' => 'required|exists:barang,id',
        'details.*.qty' => 'required|numeric|min:1',
        'details.*.harga_satuan' => 'required|numeric|min:0',
        'details.*.diskon' => 'nullable|numeric|min:0',
        'details.*.ppn' => 'nullable|numeric|min:0',
        'ppn_master_id' => 'nullable|exists:ppn,id',
        'ppn' => 'nullable|numeric|min:0',
        'diskon' => 'nullable|numeric|min:0',
        'biaya_lain' => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        $this->addDetail();
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function addDetail()
    {
        $this->details[] = ['barang_id' => '', 'qty' => 1, 'harga_satuan' => 0, 'diskon' => 0, 'ppn' => 0, 'subtotal' => 0];
        $this->barangSearch[count($this->details) - 1] = '';
    }

    public function updatedPpnMasterId($value)
    {
        if ($value) {
            $ppnMaster = Ppn::find($value);
            if ($ppnMaster) {
                $this->ppn_rate = $ppnMaster->rate;
                $this->calculateGlobalPPN();
            }
        } else {
            $this->ppn_rate = 0;
            $this->ppn = 0;
        }
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);
        $this->details = array_values($this->details);
    }
    
    public function updatedDetails($value, $key)
    {
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if (in_array($field, ['qty', 'harga_satuan', 'diskon', 'ppn'])) {
            $qty = $this->details[$index]['qty'] ?: 0;
            $harga = $this->details[$index]['harga_satuan'] ?: 0;
            $diskon_persen = $this->details[$index]['diskon'] ?: 0;
            $ppn_rate = $this->details[$index]['ppn'] ?: 0;
            
            // Calculate DPP with percentage discount
            $total_harga = $qty * $harga;
            $diskon_amount = $total_harga * ($diskon_persen / 100);
            $dpp = $total_harga - $diskon_amount;
            // Calculate PPN Amount
            $ppn_amount = $dpp * ($ppn_rate / 100);
            // Subtotal
            $this->details[$index]['subtotal'] = $dpp + $ppn_amount;
        }
        
        $this->calculateGlobalPPN();
    }
    
    public function updatedDiskon()
    {
        $this->calculateGlobalPPN();
    }

    public function updatedPpnRate()
    {
        $this->calculateGlobalPPN();
    }

    public function calculateGlobalPPN()
    {
        // Base amount = Sum of item subtotals (which includes item PPN)
        // Global discount is applied as percentage
        $subtotal = $this->getSubTotalProperty();
        $diskon_persen = $this->diskon ?: 0;
        $diskon_amount = $subtotal * ($diskon_persen / 100);
        $taxable = max(0, $subtotal - $diskon_amount);
        
        $this->ppn = round($taxable * ($this->ppn_rate / 100), 2);
    }

    public function calculateGlobalPPNWithRate($rate)
    {
        $this->ppn_rate = $rate;
        $this->calculateGlobalPPN();
    }

    public function selectSupplier($id, $name)
    {
        $this->supplier_id = $id;
        $this->supplierSearch = $name;
    }

    public function selectBarang($index, $id, $name)
    {
        $this->details[$index]['barang_id'] = $id;
        $this->barangSearch[$index] = $name;
        
        // Auto-fill price if needed
        $barang = Barang::find($id);
        if ($barang) {
            $this->details[$index]['harga_satuan'] = $barang->harga_beli_terakhir ?? $barang->harga_jual ?? 0;
            // Trigger calculation
            $this->updatedDetails(null, $index . '.harga_satuan');
        }
    }

    public function render()
    {
        $pembelians = ModelsPembelian::with(['supplier', 'creator', 'updater', 'deleter'])
            ->where(function ($query) {
                $query->whereRaw('LOWER(no_po) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereHas('supplier', function ($q) {
                        $q->whereRaw('LOWER(nama_supplier) LIKE ?', ['%' . strtolower($this->search) . '%']);
                    });
            })
            ->whereBetween('tanggal_po', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Optimized fetching for Supplier
        $suppliers = [];
        if (strlen($this->supplierSearch) >= 2) {
            $suppliers = Suppliers::whereRaw('LOWER(nama_supplier) LIKE ?', ['%' . strtolower($this->supplierSearch) . '%'])
                ->limit(10)->get();
            
            // If the search exactly matches an existing selection, don't show dropdown
            if ($suppliers->count() == 1 && $suppliers->first()->id == $this->supplier_id && $suppliers->first()->nama_supplier == $this->supplierSearch) {
                $suppliers = [];
            }
        }

        // Optimized fetching for Barang
        $barangResults = [];
        foreach ($this->barangSearch as $index => $term) {
            if (strlen($term) >= 2) {
                $results = Barang::whereRaw('LOWER(nama_barang) LIKE ?', ['%' . strtolower($term) . '%'])
                    ->limit(10)->get();
                
                // If the search exactly matches an existing selection, don't show dropdown
                if ($results->count() == 1 && isset($this->details[$index]['barang_id']) && $results->first()->id == $this->details[$index]['barang_id'] && $results->first()->nama_barang == $term) {
                    $barangResults[$index] = [];
                } else {
                    $barangResults[$index] = $results;
                }
            } else {
                $barangResults[$index] = [];
            }
        }

        // Get active PPN masters for dropdown
        $ppnMasters = Ppn::active()->orderBy('rate', 'asc')->get();

        return view('livewire.pembelian.index', compact('pembelians', 'suppliers', 'barangResults', 'ppnMasters'));
    }

    public function getTotalProperty()
    {
        $subtotal = collect($this->details)->sum('subtotal');
        return $subtotal - $this->diskon + $this->ppn + $this->biaya_lain;
    }

    public function getSubTotalProperty()
    {
        return collect($this->details)->sum('subtotal');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->no_po = $this->generateNoPO(); // Set nomor PO otomatis
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->reset([
            'pembelian_id',
            'no_po',
            'tanggal_po',
            'supplier_id',
            'status',
            'details',
            'isShow',
            'isOpen',
            'ppn',
            'ppn_rate',
            'ppn_master_id',
            'diskon',
            'biaya_lain'
        ]);
    }
    

    private function resetInputFields()
    {
        $this->pembelian_id = null;
        $this->no_po = '';
        $this->tanggal_po = date('Y-m-d');
        $this->supplier_id = '';
        $this->supplierSearch = '';
        $this->status = 'draft';
        $this->ppn = 0;
        $this->ppn_rate = 0;
        $this->ppn_master_id = null;
        $this->diskon = 0;
        $this->biaya_lain = 0;
        $this->details = [];
        $this->addDetail();
    }

    public function edit($id)
    {
        $pembelian = ModelsPembelian::with('details.barang', 'supplier')->findOrFail($id);
        
        $this->pembelian_id = $pembelian->id;
        $this->no_po = $pembelian->no_po;
        $this->tanggal_po = $pembelian->tanggal_po;
        $this->supplier_id = $pembelian->supplier_id;
        $this->supplierSearch = $pembelian->supplier->nama_supplier ?? '';
        $this->status = $pembelian->status;
        $this->ppn = $pembelian->ppn;
        $this->diskon = $pembelian->diskon;
        $this->biaya_lain = $pembelian->biaya_lain;
        
        $this->details = [];
        $this->barangSearch = [];

        foreach ($pembelian->details as $index => $detail) {
            $qty = $detail->qty;
            $harga = $detail->harga_satuan;
            $diskon_persen = $detail->diskon;
            $ppn_rate = $detail->ppn;
            
            // Calculate with percentage discount
            $total_harga = $qty * $harga;
            $diskon_amount = $total_harga * ($diskon_persen / 100);
            $dpp = $total_harga - $diskon_amount;
            $ppn_amount = $dpp * ($ppn_rate / 100);
            $subtotal = $dpp + $ppn_amount;

            $this->details[$index] = [
                'id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'qty' => $qty,
                'harga_satuan' => $harga,
                'diskon' => $diskon_persen,
                'ppn' => $ppn_rate,
                'subtotal' => $subtotal,
            ];
            $this->barangSearch[$index] = $detail->barang->nama_barang ?? '';
        }
        
        // Calculate reverse PPN Rate
        $subtotal = collect($this->details)->sum('subtotal');
        $diskon_persen = $this->diskon ?: 0;
        $diskon_amount = $subtotal * ($diskon_persen / 100);
        $taxable = max(0, $subtotal - $diskon_amount);
        if ($taxable > 0 && $this->ppn > 0) {
            $this->ppn_rate = round(($this->ppn / $taxable) * 100, 2);
        } else {
            $this->ppn_rate = 0;
        }

        // Try to find matching PPN master
        $this->ppn_master_id = null;
        if ($this->ppn_rate > 0) {
            $ppnMaster = Ppn::whereRaw('ABS(rate - ?) < 0.01', [$this->ppn_rate])->first();
            if ($ppnMaster) {
                $this->ppn_master_id = $ppnMaster->id;
            }
        }
        
        $this->openModal();
    }

    public function store()
    {
        $this->validate(
            $this->pembelian_id
                ? array_merge($this->rules, ['no_po' => 'required|unique:pembelian,no_po,' . $this->pembelian_id])
                : $this->rules
        );
    
        DB::transaction(function () {
            $userId = Auth::id();
        
            $data = [
                'no_po' => $this->no_po,
                'tanggal_po' => $this->tanggal_po,
                'supplier_id' => $this->supplier_id,
                'status' => $this->status,
                'ppn' => $this->ppn,
                'ppn_master_id' => $this->ppn_master_id,
                'diskon' => $this->diskon,
                'biaya_lain' => $this->biaya_lain,
                'updated_user' => $userId, 
            ];
        
            if (!$this->pembelian_id) {
                $data['inserted_user'] = $userId;
            }
        
            $pembelian = ModelsPembelian::updateOrCreate(
                ['id' => $this->pembelian_id],
                $data
            );
        
            $pembelian->details()->delete();
        
            foreach ($this->details as $index => $detail) {
                $qty = $detail['qty'];
                $harga = $detail['harga_satuan'];
                $diskon_persen = $detail['diskon'] ?? 0;
                $ppn_rate = $detail['ppn'] ?? 0;
                
                // Calculate with percentage discount
                $total_harga = $qty * $harga;
                $diskon_amount = $total_harga * ($diskon_persen / 100);
                $dpp = $total_harga - $diskon_amount;
                $ppn_amount = $dpp * ($ppn_rate / 100);
                $subtotal = $dpp + $ppn_amount;

                $pembelian->details()->create([
                    'barang_id' => $detail['barang_id'],
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'diskon' => $diskon_persen,
                    'ppn' => $ppn_rate,
                    'subtotal' => $subtotal,
                ]);
            }
        });
        
        $message = $this->pembelian_id
            ? 'Pembelian updated successfully.'
            : 'Pembelian created successfully.';
    
        $this->dispatch('notify', message: $message, type: 'success');
    
        $this->closeModal();
        $this->resetInputFields();
    }

    public function show($id)
    {
        $pembelian = ModelsPembelian::with('supplier', 'details.barang')->findOrFail($id);

        $this->pembelian_id = $pembelian->id;
        $this->no_po = $pembelian->no_po;
        $this->tanggal_po = $pembelian->tanggal_po ? $pembelian->tanggal_po->format('Y-m-d') : null;
        $this->supplier_id = $pembelian->supplier_id;
        $this->supplierSearch = $pembelian->supplier->nama_supplier ?? '';
        $this->status = $pembelian->status;
        $this->ppn = $pembelian->ppn;
        $this->diskon = $pembelian->diskon;
        $this->biaya_lain = $pembelian->biaya_lain;

        $this->details = [];
        $this->barangSearch = [];

        foreach ($pembelian->details as $index => $detail) {
            $qty = $detail->qty;
            $harga = $detail->harga_satuan;
            $diskon_persen = $detail->diskon;
            $ppn_rate = $detail->ppn;
            
            // Calculate with percentage discount
            $total_harga = $qty * $harga;
            $diskon_amount = $total_harga * ($diskon_persen / 100);
            $dpp = $total_harga - $diskon_amount;
            $ppn_amount = $dpp * ($ppn_rate / 100);
            $subtotal = $dpp + $ppn_amount;

            $this->details[$index] = [
                'barang_id' => $detail->barang_id,
                'qty' => $qty,
                'harga_satuan' => $harga,
                'diskon' => $diskon_persen,
                'ppn' => $ppn_rate,
                'subtotal' => $subtotal,
            ];
            $this->barangSearch[$index] = $detail->barang->nama_barang ?? '';
        }

        // Try to find matching PPN master for display
        $this->ppn_master_id = null;
        $subtotal = collect($this->details)->sum('subtotal');
        $diskon_persen = $this->diskon ?: 0;
        $diskon_amount = $subtotal * ($diskon_persen / 100);
        $taxable = max(0, $subtotal - $diskon_amount);
        if ($taxable > 0 && $this->ppn > 0) {
            $ppnRate = round(($this->ppn / $taxable) * 100, 2);
            $ppnMaster = Ppn::whereRaw('ABS(rate - ?) < 0.01', [$ppnRate])->first();
            if ($ppnMaster) {
                $this->ppn_master_id = $ppnMaster->id;
            }
        }

        $this->isShow = true;
        $this->isOpen = true;
    }

    
public function generateNoPO()
{
    // Format: PO-YYYYMMDD-001
    $today = now()->format('Ymd');
    $prefix = 'PO-' . $today . '-';

    // Ambil nomor terakhir hari ini
    $last = ModelsPembelian::whereDate('created_at', now())
        ->where('no_po', 'like', $prefix . '%')
        ->orderBy('no_po', 'desc')
        ->first();

    if ($last) {
        $lastNumber = (int) substr($last->no_po, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '001';
    }

    return $prefix . $newNumber;
}


    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $pembelian = ModelsPembelian::findOrFail($id);
            $pembelian->details()->delete();
            $pembelian->delete();
        });
        
        $this->dispatch('notify', message: 'Pembelian deleted successfully.', type: 'success');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


public function validatePembelian($id)
{
    $pembelian = ModelsPembelian::find($id);

    if ($pembelian && strtolower($pembelian->status) === 'draft') {
        $pembelian->status = 'approved';
        $pembelian->save();

        $this->dispatch('notify', message: 'Pembelian berhasil di-approve.', type: 'success');
    } else {
        $this->dispatch('notify', message: 'Validasi gagal. Status pembelian tidak sesuai.', type: 'error');
    }
}

public function unvalidasi($id)
{
    $pembelian = ModelsPembelian::find($id);

    if ($pembelian && strtolower($pembelian->status) === 'approved') {
        $pembelian->status = 'draft';
        $pembelian->save();

        $this->dispatch('notify', message: 'Approval berhasil dibatalkan.', type: 'success');
    } else {
        $this->dispatch('notify', message: 'Unapprove gagal. Status pembelian tidak sesuai.', type: 'error');
    }
}


}
