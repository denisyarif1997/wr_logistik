<?php

namespace App\View\Components;

use App\Models\{Barang, Pembelian, Penerimaan, Pemakaian, Pembayaran, Stok, Gudang, User, Penjualan};
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
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
        $lowStockItems = $this->getLowStockItemsOptimized();
        view()->share('lowStockItems', $lowStockItems);

        // --- STOCK BY WAREHOUSE (dengan cache) ---
        $stokPerGudang = $this->getCachedData('dashboard.stok_per_gudang', function() {
            return Gudang::select('id', 'nama_gudang')
                ->withSum('stok', 'stok_akhir')
                ->get();
        });
        view()->share('stokPerGudang', $stokPerGudang);

        // --- SALES DATA ---
        // Total Penjualan
        $totalPenjualan = $this->getCachedCount('dashboard.penjualan_count', fn() => Penjualan::count());
        view()->share('totalPenjualan', $totalPenjualan);

        // Total Omzet (total sales)
        $totalOmzet = $this->getCachedData('dashboard.total_omzet', fn() => Penjualan::sum('total'));
        view()->share('totalOmzet', $totalOmzet);

        // Recent Penjualan
        $recentPenjualan = Penjualan::with('gudang:id,nama_gudang')
            ->select('id', 'no_penjualan', 'gudang_id', 'tanggal', 'total', 'status')
            ->latest('tanggal')
            ->take(5)
            ->get();
        view()->share('recentPenjualan', $recentPenjualan);

        // --- WEEKLY ACTIVITY DATA (untuk chart) ---
        $weeklyActivity = $this->getWeeklyActivityData();
        view()->share('weeklyActivity', $weeklyActivity);

        // --- ADDITIONAL CHART DATA ---
        // Data distribusi barang per kategori (untuk pie/doughnut chart)
        $kategoriDistribusi = $this->getKategoriDistribusi();
        view()->share('kategoriDistribusi', $kategoriDistribusi);

        // Data perbandingan bulanan (untuk bar chart)
        $monthlyComparison = $this->getMonthlyComparison();
        view()->share('monthlyComparison', $monthlyComparison);

        // Data top barang terbanyak stoknya
        $topStokBarang = $this->getTopStokBarang();
        view()->share('topStokBarang', $topStokBarang);
    }

    /**
     * Ambil data distribusi stok per gudang untuk pie chart
     */
    private function getKategoriDistribusi(): array
    {
        $cacheKey = 'dashboard.gudang_distribusi';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            $gudangs = Gudang::select('id', 'nama_gudang')
                ->withSum('stok', 'stok_akhir')
                ->get();
            
            $data = [
                'labels' => [],
                'data' => [],
                'colors' => [],
            ];

            $colorPalette = [
                '#534AB7', '#0F6E56', '#185FA5', '#854F0B', '#993C1D',
                '#993556', '#059669', '#6366f1', '#d97706', '#dc2626',
                '#2563eb', '#7c3aed', '#db2777', '#ea580c', '#0891b2'
            ];

            $i = 0;
            foreach ($gudangs as $gudang) {
                $stokTotal = (int) ($gudang->stok_sum_stok_akhir ?? 0);
                if ($stokTotal > 0) {
                    $data['labels'][] = $gudang->nama_gudang;
                    $data['data'][] = $stokTotal;
                    $data['colors'][] = $colorPalette[$i % count($colorPalette)];
                    $i++;
                }
            }

            return $data;
        });
    }

    /**
     * Ambil data perbandingan bulanan (pembelian, penerimaan, pemakaian)
     */
    private function getMonthlyComparison(): array
    {
        $cacheKey = 'dashboard.monthly_comparison';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            $data = [
                'labels' => [],
                'pembelian' => [],
                'penerimaan' => [],
                'pemakaian' => [],
            ];

            // Get data for last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $yearMonth = $month->format('Y-m');
                $label = $month->translatedFormat('M Y');
                
                $data['labels'][] = $label;

                // Count pembelian in that month
                $pembelianCount = Pembelian::whereYear('tanggal_po', $month->year)
                    ->whereMonth('tanggal_po', $month->month)
                    ->count();
                $data['pembelian'][] = $pembelianCount;

                // Count penerimaan in that month
                $penerimaanCount = Penerimaan::whereYear('tanggal_terima', $month->year)
                    ->whereMonth('tanggal_terima', $month->month)
                    ->count();
                $data['penerimaan'][] = $penerimaanCount;

                // Count pemakaian in that month
                $pemakaianCount = Pemakaian::whereYear('tanggal_pakai', $month->year)
                    ->whereMonth('tanggal_pakai', $month->month)
                    ->count();
                $data['pemakaian'][] = $pemakaianCount;
            }

            return $data;
        });
    }

    /**
     * Ambil data top 10 barang dengan stok terbanyak
     */
    private function getTopStokBarang(): array
    {
        $cacheKey = 'dashboard.top_stok_barang';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            $barang = Barang::select('id', 'kode_barang', 'nama_barang')
                ->selectSub(function ($query) {
                    $query->selectRaw('COALESCE(SUM(stok_akhir), 0)')
                        ->from('stok')
                        ->whereColumn('barang_id', 'barang.id');
                }, 'total_stok')
                ->orderByDesc('total_stok')
                ->take(10)
                ->get();
            
            $data = [
                'labels' => [],
                'data' => [],
            ];

            foreach ($barang as $item) {
                $label = strlen($item->nama_barang) > 20 
                    ? substr($item->nama_barang, 0, 20) . '...' 
                    : $item->nama_barang;
                $data['labels'][] = $label;
                $data['data'][] = (int) $item->total_stok;
            }

            return $data;
        });
    }

    /**
     * Ambil data aktivitas mingguan untuk chart
     */
    private function getWeeklyActivityData(): array
    {
        $cacheKey = 'dashboard.weekly_activity';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            $data = [
                'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                'penerimaan' => [],
                'pemakaian' => [],
                'penjualan' => [],
            ];

            // Get data for last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                
                $penerimaanCount = Penerimaan::whereDate('tanggal_terima', $date)->count();
                $pemakaianCount = Pemakaian::whereDate('tanggal_pakai', $date)->count();
                $penjualanCount = Penjualan::whereDate('tanggal', $date)->count();
                
                $data['penerimaan'][] = $penerimaanCount;
                $data['pemakaian'][] = $pemakaianCount;
                $data['penjualan'][] = $penjualanCount;
            }

            return $data;
        });
    }

    /**
     * Hitung total hutang dengan query teroptimasi (tanpa N+1, tanpa looping PHP)
     */
    private function getTotalHutangOptimized(): float
    {
        $cacheKey = 'dashboard.total_hutang';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
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