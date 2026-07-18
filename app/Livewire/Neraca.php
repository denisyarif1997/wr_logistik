<?php

namespace App\Livewire;

use App\Models\Akun;
use App\Models\JurnalDetail;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Neraca extends Component
{
    public $endDate;
    public $totalAset = 0;
    public $totalLiabilitas = 0;
    public $totalEkuitas = 0;
    public $labaRugiPeriode = 0;

    public function mount()
    {
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        // Get all asset accounts
        $akunAset = Akun::where('tipe', 'aset')->orderBy('kode_akun')->get();
        
        // Get all liability accounts
        $akunLiabilitas = Akun::where('tipe', 'liabilitas')->orderBy('kode_akun')->get();
        

        
        // Get all equity accounts
        $akunEkuitas = Akun::where('tipe', 'ekuitas')->orderBy('kode_akun')->get();

        $asetData = [];
        $liabilitasData = [];
        $ekuitasData = [];

        // Calculate asset totals (Debit - Kredit)
        foreach ($akunAset as $akun) {
            $debit = JurnalDetail::where('akun_id', $akun->id)
                ->whereHas('jurnal', function($query) {
                    $query->where('tanggal', '<=', $this->endDate);
                })
                ->sum('debit');
            
            $kredit = JurnalDetail::where('akun_id', $akun->id)
                ->whereHas('jurnal', function($query) {
                    $query->where('tanggal', '<=', $this->endDate);
                })
                ->sum('kredit');
            
            $saldo = $debit - $kredit;
            
            $asetData[] = [
                'kode' => $akun->kode_akun,
                'nama' => $akun->nama_akun,
                'total' => abs($saldo)
            ];
        }

        // Calculate liability totals (Kredit - Debit)
        foreach ($akunLiabilitas as $akun) {
            $debit = JurnalDetail::where('akun_id', $akun->id)
                ->whereHas('jurnal', function($query) {
                    $query->where('tanggal', '<=', $this->endDate);
                })
                ->sum('debit');
            
            $kredit = JurnalDetail::where('akun_id', $akun->id)
                ->whereHas('jurnal', function($query) {
                    $query->where('tanggal', '<=', $this->endDate);
                })
                ->sum('kredit');
            
            $saldo = $kredit - $debit;
            
            $liabilitasData[] = [
                'kode' => $akun->kode_akun,
                'nama' => $akun->nama_akun,
                'total' => abs($saldo)
            ];
        }

        // Calculate equity totals (Kredit - Debit)
        foreach ($akunEkuitas as $akun) {
            $debit = JurnalDetail::where('akun_id', $akun->id)
                ->whereHas('jurnal', function($query) {
                    $query->where('tanggal', '<=', $this->endDate);
                })
                ->sum('debit');
            
            $kredit = JurnalDetail::where('akun_id', $akun->id)
                ->whereHas('jurnal', function($query) {
                    $query->where('tanggal', '<=', $this->endDate);
                })
                ->sum('kredit');
            
            $saldo = $kredit - $debit;
            
            $ekuitasData[] = [
                'kode' => $akun->kode_akun,
                'nama' => $akun->nama_akun,
                'total' => abs($saldo)
            ];
        }

        // Calculate Laba Rugi for current period to add to equity
        $pendapatanTotal = JurnalDetail::whereHas('jurnal', function($query) {
                $query->where('tanggal', '<=', $this->endDate);
            })
            ->whereHas('akun', function($query) {
                $query->where('tipe', 'pendapatan');
            })
            ->sum('kredit');

        $bebanTotal = JurnalDetail::whereHas('jurnal', function($query) {
                $query->where('tanggal', '<=', $this->endDate);
            })
            ->whereHas('akun', function($query) {
                $query->where('tipe', 'beban');
            })
            ->sum('debit');

        $this->labaRugiPeriode = $pendapatanTotal - $bebanTotal;

        $this->totalAset = array_sum(array_column($asetData, 'total'));
        $this->totalLiabilitas = array_sum(array_column($liabilitasData, 'total'));
        $this->totalEkuitas = array_sum(array_column($ekuitasData, 'total')) + $this->labaRugiPeriode;

        return view('livewire.neraca.index', [
            'asetData' => $asetData,
            'liabilitasData' => $liabilitasData,
            'ekuitasData' => $ekuitasData,
            'totalAset' => $this->totalAset,
            'totalLiabilitas' => $this->totalLiabilitas,
            'totalEkuitas' => $this->totalEkuitas,
            'labaRugiPeriode' => $this->labaRugiPeriode,
        ]);
    }

    public function resetFilter()
    {
        $this->endDate = now()->format('Y-m-d');
    }
}
