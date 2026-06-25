<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            'dashboard',
            
            // Master Data
            'master-data',
            'barang',
            'barang-view',
            'barang-create',
            'barang-edit',
            'barang-delete',
            'satuan',
            'satuan-view',
            'satuan-create',
            'satuan-edit',
            'satuan-delete',
            'supplier',
            'supplier-view',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',
            'gudang',
            'gudang-view',
            'gudang-create',
            'gudang-edit',
            'gudang-delete',
            'departemen',
            'departemen-view',
            'departemen-create',
            'departemen-edit',
            'departemen-delete',
            'coa',
            'coa-view',
            'coa-create',
            'coa-edit',
            'coa-delete',
            'ppn',
            'ppn-view',
            'ppn-create',
            'ppn-edit',
            'ppn-delete',
            
            // Transaksi
            'transaksi',
            'pembelian',
            'pembelian-view',
            'pembelian-create',
            'pembelian-edit',
            'pembelian-delete',
            'pembelian-approve',
            'penerimaan',
            'penerimaan-view',
            'penerimaan-create',
            'penerimaan-edit',
            'penerimaan-delete',
            'pembayaran',
            'pembayaran-view',
            'pembayaran-create',
            'pembayaran-edit',
            'pembayaran-delete',
            'pemakaian',
            'pemakaian-view',
            'pemakaian-create',
            'pemakaian-edit',
            'pemakaian-delete',
            
            // Laporan
            'laporan',
            'stok',
            'stok-view',
            'jurnal',
            'jurnal-view',
            
            // User Management
            'user',
            'user-view',
            'user-create',
            'user-edit',
            'user-delete',
            'role',
            'role-view',
            'role-create',
            'role-edit',
            'role-delete',
            'permission',
            'permission-view',
            'permission-create',
            'permission-edit',
            'permission-delete',
        ];

        foreach ($permissions as $permissionName) {
            Permission::updateOrCreate(
                ['name' => $permissionName],
                ['guard_name' => 'web']
            );
        }
    }
}