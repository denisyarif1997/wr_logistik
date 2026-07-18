<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Akun;

class NeracaDefaultAkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Aset
        $akunAset = [
            ['kode_akun' => '1000', 'nama_akun' => 'Kas', 'tipe' => 'aset'],
            ['kode_akun' => '1001', 'nama_akun' => 'Persediaan', 'tipe' => 'aset'],
            ['kode_akun' => '1002', 'nama_akun' => 'Piutang', 'tipe' => 'aset'],
            ['kode_akun' => '1003', 'nama_akun' => 'Peralatan', 'tipe' => 'aset'],
        ];

        // Akun Liabilitas
        $akunLiabilitas = [
            ['kode_akun' => '2000', 'nama_akun' => 'Hutang Supplier', 'tipe' => 'liabilitas'],
            ['kode_akun' => '2001', 'nama_akun' => 'Hutang Lainnya', 'tipe' => 'liabilitas'],
        ];

        // Akun Ekuitas
        $akunEkuitas = [
            ['kode_akun' => '3000', 'nama_akun' => 'Modal', 'tipe' => 'ekuitas'],
            ['kode_akun' => '3001', 'nama_akun' => 'Laba Ditahan', 'tipe' => 'ekuitas'],
        ];

        // Akun Pendapatan
        $akunPendapatan = [
            ['kode_akun' => '4000', 'nama_akun' => 'Pendapatan Penjualan', 'tipe' => 'pendapatan'],
        ];

        // Akun Beban
        $akunBeban = [
            ['kode_akun' => '5000', 'nama_akun' => 'Beban Operasional', 'tipe' => 'beban'],
            ['kode_akun' => '5001', 'nama_akun' => 'Beban Gaji', 'tipe' => 'beban'],
        ];

        // Insert semua akun
        foreach (array_merge($akunAset, $akunLiabilitas, $akunEkuitas, $akunPendapatan, $akunBeban) as $akun) {
            Akun::updateOrCreate(
                ['kode_akun' => $akun['kode_akun']],
                $akun
            );
        }
    }
}