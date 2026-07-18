<?php

namespace App\Livewire;

use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanStockOpname extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $startDate, $endDate;
    public $gudang_id;

    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $stockOpnames = StockOpname::with(['gudang', 'details.barang'])
            ->where(function ($query) {
                $query->whereRaw('LOWER(no_opname) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereRaw('LOWER(jenis) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereHas('gudang', function ($q) {
                        $q->whereRaw('LOWER(nama_gudang) LIKE ?', ['%' . strtolower($this->search) . '%']);
                    });
            })
            ->when($this->gudang_id, function ($query) {
                $query->where('gudang_id', $this->gudang_id);
            })
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $gudangs = \App\Models\Gudang::all();

        return view('livewire.laporan-stock-opname.index', compact('stockOpnames', 'gudangs'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}