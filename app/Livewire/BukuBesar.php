<?php

namespace App\Livewire;

use App\Models\Akun;
use App\Models\JurnalDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class BukuBesar extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $akun_id;
    public $startDate;
    public $endDate;
    public $search = '';
    
    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $akuns = Akun::orderBy('kode_akun')->get();
        
        $bukuBesarData = [];
        $saldoAwal = 0;
        $totalDebit = 0;
        $totalKredit = 0;
        
        if ($this->akun_id) {
            // Get opening balance (before start date)
            $openingBalance = JurnalDetail::where('akun_id', $this->akun_id)
                ->whereHas('jurnal', function($query) {
                    $query->where('tanggal', '<', $this->startDate);
                })
                ->sum('debit');
            
            $openingKredit = JurnalDetail::where('akun_id', $this->akun_id)
                ->whereHas('jurnal', function($query) {
                    $query->where('tanggal', '<', $this->startDate);
                })
                ->sum('kredit');
            
            $saldoAwal = $openingBalance - $openingKredit;
            
            // Get transaction details
            $bukuBesarData = JurnalDetail::with('jurnal')
                ->where('akun_id', $this->akun_id)
                ->whereHas('jurnal', function($query) {
                    $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
                })
                ->join('jurnal', 'jurnal_detail.jurnal_id', '=', 'jurnal.id')
                ->orderBy('jurnal.tanggal')
                ->orderBy('jurnal.id')
                ->select('jurnal_detail.*')
                ->get();
            
            $totalDebit = $bukuBesarData->sum('debit');
            $totalKredit = $bukuBesarData->sum('kredit');
        }

        return view('livewire.buku-besar.index', [
            'akuns' => $akuns,
            'bukuBesarData' => $bukuBesarData,
            'saldoAwal' => $saldoAwal,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
        ]);
    }

    public function resetFilter()
    {
        $this->akun_id = null;
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->search = '';
    }
}