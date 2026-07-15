<?php

namespace App\Livewire;

use App\Models\Penjualan;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarPenjualan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $startDate, $endDate;

    // Show
    public $isShow = false;
    public $showPenjualan;

    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function show($id)
    {
        $this->showPenjualan = Penjualan::with(['gudang', 'creator', 'details.barang'])->findOrFail($id);
        $this->isShow = true;
    }

    public function closeShow()
    {
        $this->isShow = false;
        $this->showPenjualan = null;
    }

    public function render()
    {
        $penjualans = Penjualan::with(['gudang', 'creator', 'details'])
            ->where(function ($query) {
                $query->whereRaw('LOWER(no_penjualan) LIKE ?', ['%' . strtolower($this->search) . '%']);
            })
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            // dd($penjualans);

        return view('livewire.daftar-penjualan.index', compact('penjualans'));
    }
}