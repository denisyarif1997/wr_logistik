<?php

namespace App\View\Components;

use App\Models\{Barang, Pembelian, Penerimaan, Pemakaian, Pembayaran, Stok, Gudang, User};
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Basic Counts
        $user = User::count();
        view()->share('user', $user);
        
        $totalBarang = Barang::count();
        view()->share('totalBarang', $totalBarang);
        
        $totalStok = Stok::sum('stok_akhir');
        view()->share('totalStok', $totalStok);
        
        $pembelian = Pembelian::count();
        view()->share('pembelian', $pembelian);
        
        $penerimaan = Penerimaan::count();
        view()->share('penerimaan', $penerimaan);
        
        $pemakaian = Pemakaian::count();
        view()->share('pemakaian', $pemakaian);

        // Financial Data
        $totalHutang = Pembayaran::where('status', '!=', 'lunas')
            ->with('penerimaan.details')
            ->get()
            ->sum(function($pembayaran) {
                return $pembayaran->penerimaan->calculated_total ?? 0;
            });
        view()->share('totalHutang', $totalHutang);

        $totalPembayaran = Pembayaran::where('status', 'lunas')->sum('jumlah_bayar');
        view()->share('totalPembayaran', $totalPembayaran);

        // Recent Activities
        $recentPO = Pembelian::with('supplier')->latest()->take(5)->get();
        view()->share('recentPO', $recentPO);

        $recentPenerimaan = Penerimaan::with(['pembelian.supplier', 'gudang'])->latest()->take(5)->get();
        view()->share('recentPenerimaan', $recentPenerimaan);

        $recentPemakaian = Pemakaian::with(['departemen', 'gudang'])->latest()->take(5)->get();
        view()->share('recentPemakaian', $recentPemakaian);

        // Low Stock Items
        $lowStockItems = Barang::whereHas('stok', function($q) {
            $q->select('barang_id')
                ->groupBy('barang_id')
                ->havingRaw('SUM(stok_akhir) <= stok_minimum');
        })->with(['stok' => function($q) {
            $q->selectRaw('barang_id, SUM(stok_akhir) as total_stok')
                ->groupBy('barang_id');
        }])->take(10)->get();
        view()->share('lowStockItems', $lowStockItems);

        // Stock by Warehouse
        $stokPerGudang = Gudang::withSum('stok', 'stok_akhir')->get();
        view()->share('stokPerGudang', $stokPerGudang);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard');
    }
}
