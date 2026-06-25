# Role-Based Access Control (RBAC) Documentation

## Overview
Sistem ini menggunakan Spatie Permission package untuk mengontrol akses menu berdasarkan role pengguna.

## Struktur Role

### 1. Admin
- **Akses**: Semua menu dan fitur
- **Permissions**: Semua permissions yang tersedia
- **Keterangan**: Role dengan akses penuh ke seluruh sistem

### 2. Vendor
- **Akses**: Menu Transaksi dan Master Data (read-only untuk master data)
- **Permissions**:
  - Dashboard
  - Master Data (view only): Barang, Supplier
  - Transaksi: Pembelian, Penerimaan, Pembayaran, Pemakaian (CRUD)
  - Laporan: Stok, Jurnal (view only)

### 3. User
- **Akses**: Menu Transaksi dan Master Data (read-only)
- **Permissions**:
  - Dashboard
  - Master Data (view only): Barang, Supplier
  - Transaksi (view only): Pembelian, Penerimaan, Pembayaran, Pemakaian
  - Laporan (view only): Stok, Jurnal

## Cara Menjalankan Seeder

### 1. Fresh Install (Hapus data lama terlebih dahulu)
```bash
# Hapus semua permissions dan roles yang ada
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RoleSeeder
```

### 2. Atau melalui DatabaseSeeder
```bash
# Menjalankan semua seeders termasuk RBAC
php artisan db:seed
```

### 3. Assign Role ke User
```php
// Di Controller atau Tinker
$user = User::find(1);
$user->assignRole('admin');      // Assign admin role
$user->assignRole('vendor');     // Assign vendor role
$user->assignRole('user');       // Assign user role

// Multiple roles
$user->assignRole(['admin', 'vendor']);

// Remove role
$user->removeRole('vendor');

// Sync roles (replace all)
$user->syncRoles(['admin']);

// Check role
$user->hasRole('admin');         // true/false
$user->hasAnyRole(['admin', 'vendor']); // true/false
```

### 4. Assign Permission langsung ke User
```php
$user = User::find(1);
$user->givePermissionTo('pembelian-create');
$user->givePermissionTo(['pembelian-create', 'pembelian-edit']);
```

## Daftar Permissions

### Dashboard
- `dashboard` - Akses ke halaman dashboard

### Master Data
- `master-data` - Akses ke menu master data
- `barang` - Akses ke menu barang
- `barang-create` - Buat barang baru
- `barang-edit` - Edit barang
- `barang-delete` - Hapus barang
- `satuan` - Akses ke menu satuan barang
- `satuan-create` - Buat satuan baru
- `satuan-edit` - Edit satuan
- `satuan-delete` - Hapus satuan
- `supplier` - Akses ke menu supplier
- `supplier-create` - Buat supplier baru
- `supplier-edit` - Edit supplier
- `supplier-delete` - Hapus supplier
- `gudang` - Akses ke menu gudang
- `gudang-create` - Buat gudang baru
- `gudang-edit` - Edit gudang
- `gudang-delete` - Hapus gudang
- `departemen` - Akses ke menu departemen
- `departemen-create` - Buat departemen baru
- `departemen-edit` - Edit departemen
- `departemen-delete` - Hapus departemen
- `coa` - Akses ke menu COA/Chart of Account
- `coa-create` - Buat COA baru
- `coa-edit` - Edit COA
- `coa-delete` - Hapus COA
- `ppn` - Akses ke menu PPN
- `ppn-create` - Buat PPN baru
- `ppn-edit` - Edit PPN
- `ppn-delete` - Hapus PPN

### Transaksi
- `transaksi` - Akses ke menu transaksi
- `pembelian` - Akses ke menu pembelian
- `pembelian-create` - Buat pembelian baru
- `pembelian-edit` - Edit pembelian
- `pembelian-delete` - Hapus pembelian
- `pembelian-approve` - Approve pembelian
- `penerimaan` - Akses ke menu penerimaan
- `penerimaan-create` - Buat penerimaan baru
- `penerimaan-edit` - Edit penerimaan
- `penerimaan-delete` - Hapus penerimaan
- `pembayaran` - Akses ke menu pembayaran
- `pembayaran-create` - Buat pembayaran baru
- `pembayaran-edit` - Edit pembayaran
- `pembayaran-delete` - Hapus pembayaran
- `pemakaian` - Akses ke menu pemakaian
- `pemakaian-create` - Buat pemakaian baru
- `pemakaian-edit` - Edit pemakaian
- `pemakaian-delete` - Hapus pemakaian

### Laporan
- `laporan` - Akses ke menu laporan
- `stok` - Akses ke laporan stok
- `jurnal` - Akses ke laporan jurnal

### User Management
- `user` - Akses ke menu users
- `user-create` - Buat user baru
- `user-edit` - Edit user
- `user-delete` - Hapus user
- `role` - Akses ke menu roles
- `role-create` - Buat role baru
- `role-edit` - Edit role
- `role-delete` - Hapus role
- `permission` - Akses ke menu permissions
- `permission-create` - Buat permission baru
- `permission-edit` - Edit permission
- `permission-delete` - Hapus permission

## Penggunaan di Blade Template

### Cek Permission
```blade
@can('pembelian-create')
    <button>Create Pembelian</button>
@endcan

@cannot('pembelian-delete')
    <p>Anda tidak memiliki akses untuk menghapus</p>
@endcannot
```

### Cek Multiple Permissions
```blade
@canany(['pembelian-create', 'pembelian-edit'])
    <button>Create or Edit Pembelian</button>
@endcanany

@cannotany(['pembelian-create', 'pembelian-edit'])
    <p>Anda tidak memiliki akses</p>
@endcannotany
```

### Cek Role
```blade
@role('admin')
    <p>Hanya admin yang melihat ini</p>
@endrole

@hasrole('admin')
    <p>Admin content</p>
@endhasrole

@hasanyrole('admin|vendor')
    <p>Admin atau Vendor</p>
@endhasanyrole
```

## Penggunaan di Controller

```php
public function __construct()
{
    $this->middleware(['can:pembelian-create'])->only('create', 'store');
    $this->middleware(['can:pembelian-edit'])->only('edit', 'update');
    $this->middleware(['can:pembelian-delete'])->only('destroy');
}

// Atau di method tertentu
public function store(Request $request)
{
    $this->authorize('pembelian-create');
    // ...
}
```

## Menambahkan Permission Baru

### 1. Tambahkan di PermissionSeeder
```php
$permissions = [
    // ... permissions yang sudah ada
    'new-permission',
    'new-permission-create',
    'new-permission-edit',
    'new-permission-delete',
];
```

### 2. Assign ke Role di RoleSeeder
```php
$adminRole->syncPermissions(Permission::all());

$vendorRole->syncPermissions([
    // ... permissions yang sudah ada
    'new-permission',
    'new-permission-view',
]);
```

### 3. Gunakan di Route
```php
Route::middleware(['can:new-permission'])->group(function(){
    Route::get('/new-route', [NewController::class, 'index'])->name('new.index');
});
```

### 4. Gunakan di View
```blade
@can('new-permission')
    <a href="{{ route('new.index') }}">New Menu</a>
@endcan
```

## Troubleshooting

### Permission tidak bekerja
1. Pastikan user sudah login
2. Pastikan role sudah di-assign ke user
3. Pastikan permission sudah di-assign ke role
4. Clear cache: `php artisan cache:clear`
5. Clear permission cache: `php artisan permission:cache-clear`

### Menambah Role Baru
Edit `database/seeders/RoleSeeder.php`:
```php
$newRole = Role::create(['name' => 'new-role']);
$newRole->syncPermissions([
    'permission-1',
    'permission-2',
]);
```

## Catatan Penting
- Admin role otomatis mendapatkan semua permissions
- Vendor dan User role hanya mendapatkan permissions yang didefinisikan di RoleSeeder
- Jika ingin menambahkan permission baru, tambahkan di PermissionSeeder terlebih dahulu
- Selalu assign permissions ke role, jangan langsung ke user (kecuali kasus khusus)