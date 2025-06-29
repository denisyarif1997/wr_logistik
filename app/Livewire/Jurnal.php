<?php

namespace App\Livewire;

use App\Models\Jurnal as ModelsJurnal;
use Illuminate\Support\Facades\DB;
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

    public $details = [];
    public $allAkun = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
{
    $search = $this->search;

    $jurnals = DB::table('jurnal as j')
        ->leftJoin('jurnal_detail as d', 'j.id', '=', 'd.jurnal_id')
        ->leftJoin('akun as a', 'd.akun_id', '=', 'a.id')
        ->select(
            'j.id',
            'j.no_jurnal',
            'j.tanggal',
            'j.keterangan',
            'j.referensi_id',
            'j.referensi_tipe',
            'a.kode_akun',
            'a.nama_akun',
            'd.debit',
            'd.kredit'
        )
        ->where(function ($query) use ($search) {
            $query->where('j.no_jurnal', 'like', "%{$search}%")
                ->orWhere('j.keterangan', 'like', "%{$search}%");
        })
        ->orderBy('j.tanggal', 'desc')
        ->paginate(10);

    // return view('livewire.jurnal.index', [
    //     'jurnals' => $jurnals
    return view('livewire.jurnal.index', [
        'jurnals' => $jurnals
    ]);
}

}