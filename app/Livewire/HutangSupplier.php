<?php

namespace App\Livewire;

use App\Models\Penerimaan;
use App\Models\Pembayaran;
use App\Models\Suppliers;
use Livewire\Component;
use Livewire\WithPagination;

class HutangSupplier extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $supplier_id = '';
    public $status = ''; // semua, lunas, belum_lunas

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Penerimaan::with(['pembelian.supplier', 'pembayaran'])
            ->when($this->search, function ($query) {
                $query->where('no_penerimaan', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pembelian.supplier', function ($q) {
                        $q->where('nama_supplier', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->supplier_id, function ($query) {
                $query->whereHas('pembelian', function ($q) {
                    $q->where('supplier_id', $this->supplier_id);
                });
            });

        $penerimaans = $query->orderBy('tanggal_terima', 'desc')->paginate(10);

        $suppliers = Suppliers::orderBy('nama_supplier')->get();

        $data = $penerimaans->map(function ($penerimaan) {
            $totalPembayaran = $penerimaan->pembayaran()
                ->where('status', 'lunas')
                ->sum('jumlah_bayar');
            
            $totalTagihan = $penerimaan->details->sum('subtotal') - $penerimaan->diskon + $penerimaan->ppn + $penerimaan->biaya_lain;
            $sisaHutang = $totalTagihan - $totalPembayaran;

            return [
                'id' => $penerimaan->id,
                'no_penerimaan' => $penerimaan->no_penerimaan,
                'tanggal' => $penerimaan->tanggal_terima,
                'supplier' => $penerimaan->pembelian->supplier->nama_supplier ?? '-',
                'total_tagihan' => $totalTagihan,
                'total_bayar' => $totalPembayaran,
                'sisa_hutang' => $sisaHutang,
                'status' => $sisaHutang <= 0 ? 'lunas' : 'belum_lunas',
            ];
        });

        if ($this->status === 'lunas') {
            $data = $data->where('status', 'lunas');
        } elseif ($this->status === 'belum_lunas') {
            $data = $data->where('status', 'belum_lunas');
        }

        $totalHutang = $data->where('status', 'belum_lunas')->sum('sisa_hutang');
        $totalLunas = $data->where('status', 'lunas')->sum('total_tagihan');

        return view('livewire.hutang-supplier.index', [
            'penerimaans' => $penerimaans,
            'data' => $data,
            'suppliers' => $suppliers,
            'totalHutang' => $totalHutang,
            'totalLunas' => $totalLunas,
        ]);
    }

    public function resetFilter()
    {
        $this->search = '';
        $this->supplier_id = '';
        $this->status = '';
    }
}