<?php

namespace App\Livewire;

use App\Models\Akun;
use App\Models\JurnalDetail;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LabaRugi extends Component
{
    public $startDate;
    public $endDate;
    public $pendapatanTotal = 0;
    public $bebanTotal = 0;
    public $labaRugi = 0;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        // Get all revenue accounts
        $akunPendapatan = Akun::where('tipe', 'pendapatan')->orderBy('kode_akun')->get();
        
        // Get all expense accounts
        $akunBeban = Akun::where('tipe', 'beban')->orderBy('kode_akun')->get();

        $pendapatanData = [];
        $bebanData = [];

        // Calculate revenue totals
        foreach ($akunPendapatan as $akun) {
            $total = JurnalDetail::where('akun_id', $akun->id)
                ->whereHas('jurnal', function($query) {
                    $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
                })
                ->sum('kredit'); // Revenue is recorded as credit
            
            if ($total > 0) {
                $pendapatanData[] = [
                    'kode' => $akun->kode_akun,
                    'nama' => $akun->nama_akun,
                    'total' => $total
                ];
            }
        }

        // Calculate expense totals
        foreach ($akunBeban as $akun) {
            $total = JurnalDetail::where('akun_id', $akun->id)
                ->whereHas('jurnal', function($query) {
                    $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
                })
                ->sum('debit'); // Expense is recorded as debit
            
            if ($total > 0) {
                $bebanData[] = [
                    'kode' => $akun->kode_akun,
                    'nama' => $akun->nama_akun,
                    'total' => $total
                ];
            }
        }

        $this->pendapatanTotal = array_sum(array_column($pendapatanData, 'total'));
        $this->bebanTotal = array_sum(array_column($bebanData, 'total'));
        $this->labaRugi = $this->pendapatanTotal - $this->bebanTotal;

        return view('livewire.laba-rugi.index', [
            'pendapatanData' => $pendapatanData,
            'bebanData' => $bebanData,
            'pendapatanTotal' => $this->pendapatanTotal,
            'bebanTotal' => $this->bebanTotal,
            'labaRugi' => $this->labaRugi,
        ]);
    }

    public function resetFilter()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }
}