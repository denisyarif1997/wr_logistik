<?php

namespace App\Livewire;

use App\Models\Stok as ModelsStok;
use Livewire\Component;
use Livewire\WithPagination;

class Stok extends Component
{
    use WithPagination;

    public $search = '';
    public $gudangFilter = '';
    public $namaFilter = '';
    public $history = [];
    public $showHistoryModal = false;
    public $selectedBarang = null;
    public $selectedGudang = null;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGudangFilter()
    {
        $this->resetPage();
    }

    public function updatingNamaFilter()
    {
        $this->resetPage();
    }

    public function viewHistory($barangId, $gudangId)
    {
        $this->history = \App\Models\KartuStok::with(['barang', 'gudang'])
            ->where('barang_id', $barangId)
            ->where('gudang_id', $gudangId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        $this->selectedBarang = \App\Models\Barang::find($barangId);
        $this->selectedGudang = \App\Models\Gudang::find($gudangId);
        $this->showHistoryModal = true;
    }

    public function closeHistory()
    {
        $this->showHistoryModal = false;
        $this->history = [];
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        $namaTerm = '%' . $this->namaFilter . '%';

        // Get all warehouses for dropdown
        $gudangs = \App\Models\Gudang::orderBy('nama_gudang')->get();

        $stoks = ModelsStok::selectRaw('barang_id, gudang_id, SUM(stok_akhir) as total_qty')
            ->with(['barang', 'gudang'])
            ->when($this->search, function ($query) use ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereHas('barang', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('nama_barang', 'like', $searchTerm)
                                 ->orWhere('kode_barang', 'like', $searchTerm);
                    });
                });
            })
            ->when($this->gudangFilter, function ($query) {
                $query->where('gudang_id', $this->gudangFilter);
            })
            ->when($this->namaFilter, function ($query) use ($namaTerm) {
                $query->whereHas('barang', function ($subQuery) use ($namaTerm) {
                    $subQuery->where('nama_barang', 'like', $namaTerm);
                });
            })
            ->groupBy('barang_id', 'gudang_id')
            // ->orderBy('id')
            ->paginate(10);

        return view('livewire.stok.index', compact('stoks', 'gudangs'));
    }
}
