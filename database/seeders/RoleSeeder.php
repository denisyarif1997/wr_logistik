<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Role with all permissions
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );
        $adminRole->syncPermissions(Permission::all());

        // Create Vendor Role with limited permissions
        $vendorRole = Role::updateOrCreate(
            ['name' => 'vendor'],
            ['guard_name' => 'web']
        );
        $vendorPermissions = [
            'dashboard',
            'master-data',
            'barang',
            'barang-view',
            'supplier',
            'supplier-view',
            'transaksi',
            'pembelian',
            'pembelian-view',
            'pembelian-create',
            'pembelian-edit',
            'penerimaan',
            'penerimaan-view',
            'penerimaan-create',
            'penerimaan-edit',
            'pembayaran',
            'pembayaran-view',
            'pembayaran-create',
            'pembayaran-edit',
            'pemakaian',
            'pemakaian-view',
            'pemakaian-create',
            'pemakaian-edit',
            'laporan',
            'stok',
            'stok-view',
            'jurnal',
            'jurnal-view',
        ];
        $vendorRole->syncPermissions($vendorPermissions);

        // Create User Role with basic permissions
        $userRole = Role::updateOrCreate(
            ['name' => 'user'],
            ['guard_name' => 'web']
        );
        $userPermissions = [
            'dashboard',
            'master-data',
            'barang',
            'barang-view',
            'supplier',
            'supplier-view',
            'transaksi',
            'pembelian',
            'pembelian-view',
            'penerimaan',
            'penerimaan-view',
            'pembayaran',
            'pembayaran-view',
            'pemakaian',
            'pemakaian-view',
            'laporan',
            'stok',
            'stok-view',
            'jurnal',
            'jurnal-view',
        ];
        $userRole->syncPermissions($userPermissions);
    }
}