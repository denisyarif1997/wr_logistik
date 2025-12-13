<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Pembelian as ModelsPembelian;
use App\Models\PembelianDetail;
use App\Models\Suppliers;
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
    public $pembelian_id;
    public $isOpen = false;
    public $search = '';
    public $supplierSearch = '';

    public $isShow = false;
    public $showPembelian;

    public $details = [];
    public $allBarang = [];

    protected $rules = [
        'no_po' => 'required|unique:pembelian,no_po',
        'tanggal_po' => 'required|date',
        'supplier_id' => 'required|exists:suppliers,id',
        'status' => 'required|in:draft,approved,received,canceled',
        'details.*.barang_id' => 'required|exists:barang,id',
        'details.*.qty' => 'required|numeric|min:1',
        'details.*.harga_satuan' => 'required|numeric|min:0',
        'details.*.diskon' => 'nullable|numeric|min:0',
        'details.*.ppn' => 'nullable|numeric|min:0', // Added rule
        'ppn' => 'nullable|numeric|min:0',
        'diskon' => 'nullable|numeric|min:0',
        'biaya_lain' => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        $this->allBarang = Barang::all();
        $this->addDetail();
    }

    public function addDetail()
    {
        $this->details[] = ['barang_id' => '', 'qty' => 1, 'harga_satuan' => 0, 'diskon' => 0, 'ppn' => 0, 'subtotal' => 0];
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
            $diskon = $this->details[$index]['diskon'] ?: 0;
            $ppn_rate = $this->details[$index]['ppn'] ?: 0;
            
            // Calculate DPP
            $dpp = ($qty * $harga) - ($qty * $diskon);
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
        // Base amount = Sum of (Qty * Harga - Qty * Diskon)
        // This is the taxable amount BEFORE Item PPN.
        // Wait, if items have PPN, does Global PPN apply on top?
        // Usually:
        // Option A: Global PPN applies to the Subtotal (which includes Item PPN). -> Tax on Tax.
        // Option B: Global PPN applies to the Base (DPP).
        // Given the user wants both, usually it's one or the other, or cumulative.
        // Let's assume Global PPN applies to the Net Total (Subtotal of items).
        // If Item PPN exists, Subtotal includes it.
        // Let's use the Subtotal as the base.
        
        $subtotal = $this->getSubTotalProperty();
        $taxable = max(0, $subtotal - $this->diskon);
        
        $this->ppn = round($taxable * ($this->ppn_rate / 100), 2);
    }

    public function calculateGlobalPPNWithRate($rate)
    {
        $this->ppn_rate = $rate;
        $this->calculateGlobalPPN();
    }

    public function render()
    {
        $pembelians = ModelsPembelian::with(['supplier', 'creator', 'updater', 'deleter'])
        ->where('no_po', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $suppliers = Suppliers::all();

        return view('livewire.pembelian.index', compact('pembelians', 'suppliers'));
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
        $this->diskon = 0;
        $this->biaya_lain = 0;
        $this->details = [];
        $this->addDetail();
    }

    public function edit($id)
    {
        $pembelian = ModelsPembelian::with('details')->findOrFail($id);
        
        $this->pembelian_id = $pembelian->id;
        $this->no_po = $pembelian->no_po;
        $this->tanggal_po = $pembelian->tanggal_po;
        $this->supplier_id = $pembelian->supplier_id;
        $this->status = $pembelian->status;
        $this->ppn = $pembelian->ppn;
        $this->diskon = $pembelian->diskon;
        $this->biaya_lain = $pembelian->biaya_lain;
        
        $this->details = $pembelian->details->map(function ($detail) {
            $qty = $detail->qty;
            $harga = $detail->harga_satuan;
            $diskon = $detail->diskon;
            $ppn_rate = $detail->ppn;
            
            $dpp = ($qty * $harga) - ($qty * $diskon);
            $ppn_amount = $dpp * ($ppn_rate / 100);
            $subtotal = $dpp + $ppn_amount;

            return [
                'id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'qty' => $qty,
                'harga_satuan' => $harga,
                'diskon' => $diskon,
                'ppn' => $ppn_rate,
                'subtotal' => $subtotal,
            ];
        })->toArray();
        
        // Calculate reverse PPN Rate
        $subtotal = collect($this->details)->sum('subtotal');
        $taxable = max(0, $subtotal - $this->diskon);
        if ($taxable > 0 && $this->ppn > 0) {
            $this->ppn_rate = round(($this->ppn / $taxable) * 100, 2);
        } else {
            $this->ppn_rate = 0;
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
    
        // $userId = Auth::id();
    
        DB::transaction(function () {
            $userId = Auth::id();
        
            $data = [
                'no_po' => $this->no_po,
                'tanggal_po' => $this->tanggal_po,
                'supplier_id' => $this->supplier_id,
                'status' => $this->status,
                'ppn' => $this->ppn,
                'diskon' => $this->diskon,
                'biaya_lain' => $this->biaya_lain,
                'updated_user' => $userId, // selalu diisi saat update
            ];
        
            // Hanya isi inserted_user jika create
            if (!$this->pembelian_id) {
                $data['inserted_user'] = $userId;
            }
        
            $pembelian = ModelsPembelian::updateOrCreate(
                ['id' => $this->pembelian_id],
                $data
            );
        
            $pembelian->details()->delete();
        
            foreach ($this->details as $detail) {
                $qty = $detail['qty'];
                $harga = $detail['harga_satuan'];
                $diskon = $detail['diskon'] ?? 0;
                $ppn_rate = $detail['ppn'] ?? 0;
                
                $dpp = ($qty * $harga) - ($qty * $diskon);
                $ppn_amount = $dpp * ($ppn_rate / 100);
                $subtotal = $dpp + $ppn_amount;

                $pembelian->details()->create([
                    'barang_id' => $detail['barang_id'],
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'diskon' => $diskon,
                    'ppn' => $ppn_rate,
                    'subtotal' => $subtotal,
                ]);
            }
        });
        
    
        $message = $this->pembelian_id
            ? 'Pembelian updated successfully.'
            : 'Pembelian created successfully.';
    
        $this->dispatch('notify', $message);
    
        $this->closeModal();
        $this->resetInputFields();
    }
    
    
    public function show($id)
{
    $pembelian = ModelsPembelian::with('supplier', 'details')->findOrFail($id);

    $this->pembelian_id = $pembelian->id;
    $this->no_po = $pembelian->no_po;
    $this->tanggal_po = $pembelian->tanggal_po ? $pembelian->tanggal_po->format('Y-m-d') : null;
    $this->supplier_id = $pembelian->supplier_id;
    $this->status = $pembelian->status;
    $this->ppn = $pembelian->ppn;
    $this->diskon = $pembelian->diskon;
    $this->biaya_lain = $pembelian->biaya_lain;

    // Mapping details ke format yang dipakai form
    $this->details = $pembelian->details->map(function ($detail) {
        return [
            'barang_id' => $detail->barang_id,
            'qty' => $detail->qty,
            'harga_satuan' => $detail->harga_satuan,
            'diskon' => $detail->diskon,
            'ppn' => $detail->ppn,
            'subtotal' => ($detail->qty * $detail->harga_satuan) - ($detail->qty * $detail->diskon) + ($detail->qty * $detail->ppn),
        ];
    })->toArray();

    $this->isShow = true;
    $this->isOpen = true; // supaya form muncul
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
        
        $this->dispatch('notify', 'Pembelian deleted successfully.');
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

        session()->flash('message', 'Pembelian berhasil di-approve.');
    } else {
        session()->flash('message', 'Validasi gagal. Status pembelian tidak sesuai.');
    }
}

public function unvalidasi($id)
{
    $pembelian = ModelsPembelian::find($id);

    if ($pembelian && strtolower($pembelian->status) === 'approved') {
        $pembelian->status = 'draft';
        $pembelian->save();

        session()->flash('message', 'Approval berhasil dibatalkan.');
    } else {
        session()->flash('message', 'Unapprove gagal. Status pembelian tidak sesuai.');
    }
}


}
