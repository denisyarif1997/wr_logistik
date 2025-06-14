<?php

namespace App\Livewire;

use App\Models\Stok as ModelsStok;
use Livewire\Component;
use Livewire\WithPagination;

class Stok extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

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
            ->groupBy('barang_id', 'gudang_id')
            ->paginate(10);

        return view('livewire.stok.index', compact('stoks'));
    }
}
