<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ppn;

class PpnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PPN 10% - Tarif lama
        Ppn::create([
            'rate' => 10.00,
            'kode_ppn' => 'PPN_10',
            'keterangan' => 'Tarif PPN 10% (sebelum perubahan)',
            'is_active' => true,
            'tanggal_berlaku' => '2022-04-01',
        ]);

        // PPN 11% - Tarif baru
        Ppn::create([
            'rate' => 11.00,
            'kode_ppn' => 'PPN_11',
            'keterangan' => 'Tarif PPN 11% (per 1 April 2022)',
            'is_active' => true,
            'tanggal_berlaku' => '2022-04-01',
        ]);

        // PPN 12% - Tarif terkini
        Ppn::create([
            'rate' => 12.00,
            'kode_ppn' => 'PPN_12',
            'keterangan' => 'Tarif PPN 12% (per 1 Januari 2025)',
            'is_active' => true,
            'tanggal_berlaku' => '2025-01-01',
        ]);
    }
}