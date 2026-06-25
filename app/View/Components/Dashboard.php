<?php

namespace App\View\Components;

use App\Models\{Barang, Pembelian, Penerimaan, Pemakaian, Pembayaran, Stok, Gudang, User};
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Dashboard extends Component
{
    /**
     * Durasi cache dalam detik (5 menit untuk data summary)
     */
    private const CACHE_DURATION = 300;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // --- DATA SUMMARY (dengan cache agar tidak query setiap load) ---
        // Total Users
        $user = $this->getCachedCount('dashboard.user_count', fn() => User::count());
        view()->share('user', $user);

        // Total Jenis Barang
        $totalBarang = $this->getCachedCount('dashboard.barang_count', fn() => Barang::count());
        view()->share('totalBarang', $totalBarang);

        // Total Stok Barang (aggregate langsung di database)
        $totalStok = $this->getCachedCount('dashboard.stok_sum', fn() => Stok::sum('stok_akhir'));
        view()->share('totalStok', $totalStok);

        // Total Purchase Orders
        $pembelian = $this->getCachedCount('dashboard.pembelian_count', fn() => Pembelian::count());
        view()->share('pembelian', $pembelian);

        // Total Penerimaan
        $penerimaan = $this->getCachedCount('dashboard.penerimaan_count', fn() => Penerimaan::count());
        view()->share('penerimaan', $penerimaan);

        // Total Pemakaian
        $pemakaian = $this->getCachedCount('dashboard.pemakaian_count', fn() => Pemakaian::count());
        view()->share('pemakaian', $pemakaian);

        // --- FINANCIAL DATA (optimasi dengan query langsung di database) ---
        // Total Hutang - dioptimasi agar tidak N+1 dan tidak looping di PHP
        $totalHutang = $this->getTotalHutangOptimized();
        view()->share('totalHutang', $totalHutang);

        // Total Pembayaran Lunas
        $totalPembayaran = $this->getCachedCount('dashboard.total_pembayaran_lunas', fn() => 
            Pembayaran::where('status', 'lunas')->sum('jumlah_bayar')
        );
        view()->share('totalPembayaran', $totalPembayaran);

        // --- RECENT ACTIVITIES (tanpa cache, karena perlu real-time) ---
        // Recent Purchase Orders - dengan select spesifik agar tidak overload
        $recentPO = Pembelian::with('supplier:id,nama_supplier')
            ->select('id', 'no_po', 'supplier_id', 'tanggal_po', 'status')
            ->latest('tanggal_po')
            ->take(5)
            ->get();
        view()->share('recentPO', $recentPO);

        // Recent Penerimaan - dengan select spesifik
        $recentPenerimaan = Penerimaan::with([
                'pembelian.supplier:id,nama_supplier',
                'gudang:id,nama_gudang'
            ])
            ->select('id', 'no_penerimaan', 'pembelian_id', 'gudang_id', 'tanggal_terima')
            ->latest('tanggal_terima')
            ->take(5)
            ->get();
        view()->share('recentPenerimaan', $recentPenerimaan);

        // Recent Pemakaian - dengan select spesifik
        $recentPemakaian = Pemakaian::with([
                'departemen:id,nama_departemen',
                'gudang:id,nama_gudang'
            ])
            ->select('id', 'no_pemakaian', 'departemen_id', 'gudang_id', 'tanggal_pakai')
            ->latest('tanggal_pakai')
            ->take(5)
            ->get();
        view()->share('recentPemakaian', $recentPemakaian);

        // --- LOW STOCK ITEMS (diaktifkan kembali dengan query yang dioptimasi) ---
        // Optimasi: gunakan subquery dengan select spesifik
        $lowStockItems = $this->getLowStockItemsOptimized();
        view()->share('lowStockItems', $lowStockItems);

        // --- STOCK BY WAREHOUSE (dengan cache) ---
        $stokPerGudang = $this->getCachedData('dashboard.stok_per_gudang', function() {
            return Gudang::select('id', 'nama_gudang')
                ->withSum('stok', 'stok_akhir')
                ->get();
        });
        view()->share('stokPerGudang', $stokPerGudang);
    }

    /**
     * Hitung total hutang dengan query teroptimasi (tanpa N+1, tanpa looping PHP)
     * Menggunakan subquery untuk menghitung subtotal PenerimaanDetail langsung di DB
     */
    private function getTotalHutangOptimized(): float
    {
        // Ambil dari cache agar tidak query berat setiap load
        $cacheKey = 'dashboard.total_hutang';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            // Optimasi: hitung total hutang dengan subquery langsung di database
            // Total hutang = SUM(calculated_total) - SUM(jumlah_bayar_lunas) untuk status != lunas
            $totalTagihan = Penerimaan::selectRaw('COALESCE(SUM(
                COALESCE((SELECT SUM(subtotal) FROM penerimaan_detail WHERE penerimaan_id = penerimaan.id), 0)
                - COALESCE(diskon, 0) 
                + COALESCE(ppn, 0) 
                + COALESCE(biaya_lain, 0)
            ), 0) as total_tagihan')
                ->value('total_tagihan') ?? 0;

            $totalDibayar = Pembayaran::where('status', '!=', 'gagal')
                ->sum('jumlah_bayar') ?? 0;

            return max(0, $totalTagihan - $totalDibayar);
        });
    }

    /**
     * Ambil data low stock items dengan query teroptimasi
     */
    private function getLowStockItemsOptimized()
    {
        $cacheKey = 'dashboard.low_stock_items';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            // Optimasi: gunakan query dengan subquery untuk menghitung total stok per barang
            return Barang::select([
                    'id',
                    'kode_barang',
                    'nama_barang',
                    'stok_minimum',
                ])
                ->selectSub(function ($query) {
                    $query->selectRaw('COALESCE(SUM(stok_akhir), 0)')
                        ->from('stok')
                        ->whereColumn('barang_id', 'barang.id');
                }, 'total_stok')
                ->havingRaw('total_stok <= stok_minimum')
                ->orderByRaw('(total_stok - stok_minimum) ASC')
                ->take(5)
                ->get();
        });
    }

    /**
     * Helper: dapatkan data dari cache atau jalankan callback
     */
    private function getCachedData(string $key, callable $callback, ?int $duration = null): mixed
    {
        return Cache::remember($key, $duration ?? self::CACHE_DURATION, $callback);
    }

    /**
     * Helper: dapatkan count dari cache
     */
    private function getCachedCount(string $key, callable $callback): int
    {
        return Cache::remember($key, self::CACHE_DURATION, function () use ($callback) {
            return (int) $callback();
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard');
    }
}