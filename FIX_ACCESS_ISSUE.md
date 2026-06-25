# Fix Access Issue - Role-Based Access Control

## Masalah
Setelah implementasi RBAC, user kehilangan akses ke menu karena belum memiliki role yang di-assign.

## Solusi Cepat

### Opsi 1: Jalankan Seeder (Recommended)
```bash
php artisan db:seed --class=AssignRolesToExistingUsers
```

Command ini akan:
- Menemukan semua user yang belum punya role
- Assign role 'vendor' secara default ke user tersebut
- Memberikan akses penuh ke menu Transaksi dan Master Data (view-only)

### Opsi 2: Gunakan Artisan Command
```bash
# Assign role ke user tertentu
php artisan user:assign-role email@example.com admin

# Atau pilih dari daftar user
php artisan user:assign-role
```

### Opsi 3: Via Tinker (Laravel REPL)
```bash
php artisan tinker
```

Kemudian jalankan:
```php
// Assign admin role
$user = App\Models\User::where('email', 'email@example.com')->first();
$user->assignRole('admin');

// Atau assign vendor role
$user->assignRole('vendor');

// Atau assign user role
$user->assignRole('user');
```

## Verifikasi
Setelah assign role, cek apakah user mendapatkan akses:
```php
// Di Tinker
$user = App\Models\User::where('email', 'email@example.com')->first();
$user->getRoleNames(); // Lihat role yang dimiliki
$user->getAllPermissions(); // Lihat semua permissions
```

## Role yang Tersedia

### 1. Admin
- Akses penuh ke semua menu
- Cocok untuk administrator sistem

### 2. Vendor
- Dashboard
- Master Data (view): Barang, Supplier
- Transaksi (CRUD): Pembelian, Penerimaan, Pembayaran, Pemakaian
- Laporan (view): Stok, Jurnal

### 3. User
- Dashboard
- Master Data (view): Barang, Supplier
- Transaksi (view only): Pembelian, Penerimaan, Pembayaran, Pemakaian
- Laporan (view): Stok, Jurnal

## Troubleshooting

### Permission tidak masih tidak bekerja
1. Clear cache:
```bash
php artisan cache:clear
php artisan permission:cache-clear
```

2. Cek apakah role dan permission sudah ter-create:
```bash
php artisan tinker
>>> Spatie\Permission\Models\Role::all()
>>> Spatie\Permission\Models\Permission::all()
```

3. Jika belum ada, jalankan seeder:
```bash
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder
```

### User masih tidak bisa akses setelah assign role
1. Logout dan login kembali
2. Clear browser cache
3. Cek di database apakah role sudah ter-assign:
```php
// Di Tinker
$user = App\Models\User::where('email', 'email@example.com')->first();
$user->roles; // Harus menampilkan role yang di-assign
```

## Catatan Penting
- Setelah assign role, user perlu logout dan login kembali
- Admin role mendapatkan semua permissions secara otomatis
- Jika ingin mengubah role user, gunakan command: `php artisan user:assign-role`