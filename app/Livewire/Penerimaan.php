<?php

namespace App\Livewire;

use App\Models\Gudang;
use App\Models\Pembelian;
use App\Models\Penerimaan as ModelsPenerimaan;
use App\Models\Stok;
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

    public $isShow = false;

    public $showPenerimaan;
    public $details = [];


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
    ];

    public function render()
    {
        $penerimaans = ModelsPenerimaan::with(['pembelian', 'gudang'])
            ->where('no_penerimaan', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $query = Pembelian::query();

        $query->where(function ($q) {
            $q->where('status', 'approved')
              ->whereDoesntHave('penerimaan');
        });

        // If we are editing a reception, we should also include its purchase in the list
        if ($this->pembelian_id) {
            $query->orWhere('id', $this->pembelian_id);
        }

        $pembelians = $query->get();

        $gudangs = Gudang::all();
        

        return view('livewire.penerimaan.index', compact('penerimaans', 'pembelians', 'gudangs'));
    }

     public function show($id)
{
    $penerimaans = ModelsPenerimaan::with('pembelian', 'gudang')->findOrFail($id);

    $this->penerimaan_id = $penerimaans->id;
    $this->no_penerimaan = $penerimaans->no_penerimaan;
    $this->tanggal_terima = $penerimaans->tanggal_terima ? $penerimaans->tanggal_terima->format('Y-m-d') : null;
    $this->pembelian_id = $penerimaans->pembelian_id;
    $this->gudang_id = $penerimaans->gudang_id;
    $this->diterima_oleh = $penerimaans->diterima_oleh;

    // Mapping details ke format yang dipakai form
    $this->details = $penerimaans->details->map(function ($detail) {
        return [
            'barang_id' => $detail->barang_id,
            'qty_diterima' => $detail->qty_diterima,
            // 'harga_satuan' => $detail->harga_satuan,
            // 'subtotal' => $detail->qty * $detail->harga_satuan,
        ];
    })->toArray();

    $this->isShow = true;
    $this->isOpen = true; // supaya form muncul
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
    }

    private function resetInputFields()
    {
        $this->penerimaan_id = null;
        $this->no_penerimaan = '';
        $this->tanggal_terima = '';
        $this->pembelian_id = '';
        $this->gudang_id = '';
        $this->diterima_oleh = '';
    }

    public function edit($id)
    {
        $penerimaan = ModelsPenerimaan::findOrFail($id);

        $this->penerimaan_id = $penerimaan->id;
        $this->no_penerimaan = $penerimaan->no_penerimaan;
        $this->tanggal_terima = $penerimaan->tanggal_terima;
        $this->pembelian_id = $penerimaan->pembelian_id;
        $this->gudang_id = $penerimaan->gudang_id;
        $this->diterima_oleh = $penerimaan->diterima_oleh;

        $this->openModal();
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
                    'updated_user' => $userId,
                ]
            );
    
            // Jika create baru, tambahkan inserted_user
            if (!$this->penerimaan_id) {
                $penerimaan->inserted_user = $userId;
                $penerimaan->save(); // Jangan lupa simpan perubahan inserted_user
            }
    
            // Proses detail dan update stok
            $pembelian = Pembelian::with('details')->find($this->pembelian_id);
            if ($pembelian) {
                foreach ($pembelian->details as $detail) {
                    $penerimaan->details()->create([
                        'barang_id' => $detail->barang_id,
                        'qty_diterima' => $detail->qty,
                    ]);
    
                    $stok = Stok::firstOrNew([
                        'barang_id' => $detail->barang_id,
                        'gudang_id' => $this->gudang_id,
                    ]);
    
                    $stok->stok_akhir = ($stok->stok_akhir ?? 0) + $detail->qty;
                    $stok->save();
                }
    
                $pembelian->status = 'received';
                $pembelian->save();
            }
        });
    
        $message = $this->penerimaan_id
            ? 'Penerimaan updated successfully.'
            : 'Penerimaan created successfully.';
    
        $this->dispatch('notify', $message);
        $this->closeModal();
        $this->resetInputFields();
    }

   
    

    public function delete($id)
    {
        $penerimaan = ModelsPenerimaan::findOrFail($id);
        $penerimaan->update(['deleted_by' => Auth::id()]);
        $penerimaan->delete();
        $this->dispatch('notify', 'Penerimaan deleted successfully.');

    }
}
