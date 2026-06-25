# Cara Membuat Role Baru dan Setting Akses

## Langkah 1: Tambahkan Role di RoleSeeder

Edit file `database/seeders/RoleSeeder.php`, tambahkan role baru sebelum fungsi `run()`:

```php
public function run(): void
{
    // ... role yang sudah ada (admin, vendor, user)

    // Tambahkan role baru di sini
    $managerRole = Role::updateOrCreate(
        ['name' => 'manager'],
        ['guard_name' => 'web']
    );
    
    // Assign permissions ke role baru
    $managerPermissions = [
        'dashboard',
        'master-data',
        'barang',
        'barang-view',
        'barang-create',
        'barang-edit',
        'supplier',
        'supplier-view',
        'transaksi',
        'pembelian',
        'pembelian-view',
        'pembelian-create',
        'pembelian-edit',
        'penerimaan',
        'penerimaan-view',
        'laporan',
        'stok',
        'jurnal',
    ];
    
    $managerRole->syncPermissions($managerPermissions);
}
```

## Langkah 2: Jalankan RoleSeeder

```bash
php artisan db:seed --class=RoleSeeder
```

## Langkah 3: Assign Role ke User

### Opsi A: Via Artisan Command
```bash
php artisan user:assign-role
# Ikuti instruksi di layar
```

### Opsi B: Via Tinker
```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'user@example.com')->first();
$user->assignRole('manager');
```

### Opsi C: Via Controller/Code
```php
$user = User::find(1);
$user->assignRole('manager');
```

## Langkah 4: Verifikasi

```bash
# Cek role yang ada
php artisan tinker
>>> Spatie\Permission\Models\Role::all()

# Cek permissions dari role
>>> $role = Spatie\Permission\Models\Role::where('name', 'manager')->first();
>>> $role->permissions;

# Cek user dan role-nya
>>> $user = App\Models\User::where('email', 'user@example.com')->first();
>>> $user->getRoleNames();
>>> $user->getAllPermissions();
```

## Contoh: Membuat Role "Manager"

### 1. Edit RoleSeeder.php

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Admin - Full access
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );
        $adminRole->syncPermissions(Permission::all());

        // Vendor - Transaction access
        $vendorRole = Role::updateOrCreate(
            ['name' => 'vendor'],
            ['guard_name' => 'web']
        );
        $vendorRole->syncPermissions([
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
            'pembayaran',
            'pembayaran-view',
            'pemakaian',
            'pemakaian-view',
            'laporan',
            'stok',
            'jurnal',
        ]);

        // User - Read only
        $userRole = Role::updateOrCreate(
            ['name' => 'user'],
            ['guard_name' => 'web']
        );
        $userRole->syncPermissions([
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
            'jurnal',
        ]);

        // MANAGER - New Role
        // Manager bisa CRUD transaksi + lihat master data
        $managerRole = Role::updateOrCreate(
            ['name' => 'manager'],
            ['guard_name' => 'web']
        );
        $managerPermissions = [
            'dashboard',
            'master-data',
            'barang',
            'barang-view',
            'barang-create',
            'barang-edit',
            'supplier',
            'supplier-view',
            'supplier-create',
            'supplier-edit',
            'transaksi',
            'pembelian',
            'pembelian-view',
            'pembelian-create',
            'pembelian-edit',
            'pembelian-approve',
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
            'jurnal',
        ];
        $managerRole->syncPermissions($managerPermissions);

        // GUDANG - Another New Role
        // Gudang hanya bisa manage penerimaan dan lihat stok
        $gudangRole = Role::updateOrCreate(
            ['name' => 'gudang'],
            ['guard_name' => 'web']
        );
        $gudangPermissions = [
            'dashboard',
            'master-data',
            'barang',
            'barang-view',
            'supplier',
            'supplier-view',
            'transaksi',
            'penerimaan',
            'penerimaan-view',
            'penerimaan-create',
            'penerimaan-edit',
            'pemakaian',
            'pemakaian-view',
            'laporan',
            'stok',
        ];
        $gudangRole->syncPermissions($gudangPermissions);
    }
}
```

### 2. Jalankan Seeder

```bash
php artisan db:seed --class=RoleSeeder
```

### 3. Assign Role ke User

```bash
# Via command
php artisan user:assign-role

# Atau via tinker
php artisan tinker
>>> $user = App\Models\User::where('email', 'manager@example.com')->first();
>>> $user->assignRole('manager');
```

## Daftar Permissions yang Tersedia

### Dashboard
- `dashboard`

### Master Data
- `master-data`
- `barang`, `barang-view`, `barang-create`, `barang-edit`, `barang-delete`
- `satuan`, `satuan-view`, `satuan-create`, `satuan-edit`, `satuan-delete`
- `supplier`, `supplier-view`, `supplier-create`, `supplier-edit`, `supplier-delete`
- `gudang`, `gudang-view`, `gudang-create`, `gudang-edit`, `gudang-delete`
- `departemen`, `departemen-view`, `departemen-create`, `departemen-edit`, `departemen-delete`
- `coa`, `coa-view`, `coa-create`, `coa-edit`, `coa-delete`
- `ppn`, `ppn-view`, `ppn-create`, `ppn-edit`, `ppn-delete`

### Transaksi
- `transaksi`
- `pembelian`, `pembelian-view`, `pembelian-create`, `pembelian-edit`, `pembelian-delete`, `pembelian-approve`
- `penerimaan`, `penerimaan-view`, `penerimaan-create`, `penerimaan-edit`, `penerimaan-delete`
- `pembayaran`, `pembayaran-view`, `pembayaran-create`, `pembayaran-edit`, `pembayaran-delete`
- `pemakaian`, `pemakaian-view`, `pemakaian-create`, `pemakaian-edit`, `pemakaian-delete`

### Laporan
- `laporan`
- `stok`, `stok-view`
- `jurnal`, `jurnal-view`

### User Management
- `user`, `user-view`, `user-create`, `user-edit`, `user-delete`
- `role`, `role-view`, `role-create`, `role-edit`, `role-delete`
- `permission`, `permission-view`, `permission-create`, `permission-edit`, `permission-delete`

## Tips

1. **Permission dasar**: Setiap module punya permission dasar (misal: `pembelian`) untuk akses menu
2. **Permission view**: `*-view` untuk melihat data (read-only)
3. **Permission CRUD**: `*-create`, `*-edit`, `*-delete` untuk operasi CRUD
4. **Permission khusus**: Misal `pembelian-approve` untuk approve pembelian

## Contoh Penggunaan di Controller

```php
public function __construct()
{
    // Hanya user dengan permission 'pembelian-create' yang bisa akses
    $this->middleware(['can:pembelian-create'])->only('create', 'store');
    
    // Hanya user dengan permission 'pembelian-edit' yang bisa akses
    $this->middleware(['can:pembelian-edit'])->only('edit', 'update');
    
    // Hanya user dengan permission 'pembelian-delete' yang bisa akses
    $this->middleware(['can:pembelian-delete'])->only('destroy');
    
    // Hanya user dengan permission 'pembelian-approve' yang bisa akses
    $this->middleware(['can:pembelian-approve'])->only('approve');
}
```

## Contoh Penggunaan di View

```blade
{{-- Cek permission --}}
@can('pembelian-create')
    <button class="btn btn-primary">Create Pembelian</button>
@endcan

{{-- Cek multiple permissions --}}
@canany(['pembelian-create', 'pembelian-edit'])
    <button class="btn btn-primary">Create or Edit</button>
@endcanany

{{-- Cek role --}}
@role('manager')
    <p>Hanya Manager yang melihat ini</p>
@endrole
```

## Troubleshooting

### Permission tidak muncul di menu
1. Clear cache: `php artisan cache:clear`
2. Clear permission cache: `php artisan permission:cache-reset`
3. Logout dan login kembali

### Role tidak bisa di-assign
1. Pastikan role sudah dibuat: `php artisan db:seed --class=RoleSeeder`
2. Cek role ada di database: `php artisan tinker >>> Role::all()`

### Permission tidak bekerja
1. Pastikan permission sudah dibuat: `php artisan db:seed --class=PermissionSeeder`
2. Assign permission ke role di RoleSeeder
3. Clear cache: `php artisan permission:fix`