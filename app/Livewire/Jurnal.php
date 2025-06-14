<?php

namespace App\Livewire;

use App\Models\Jurnal as ModelsJurnal;
use Livewire\Component;
use Livewire\WithPagination;

class Jurnal extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $no_jurnal, $tanggal, $keterangan;
    public $jurnal_id;
    public $isOpen = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public $details = [];
    public $allAkun = [];

    public function render()
    {
        $jurnals = ModelsJurnal::with(['details.akun', 'referensi'])
            ->where('no_jurnal', 'like', "%{$this->search}%")
            ->orWhere('keterangan', 'like', "%{$this->search}%")
            ->orderBy('tanggal', 'desc')
            ->paginate(5);

        return view('livewire.jurnal.index', compact('jurnals'));
    }
}
