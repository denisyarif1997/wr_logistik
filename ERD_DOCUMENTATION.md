# Entity Relationship Diagram (ERD) - LOGISTIK WAREHOUSE

## Overview
Aplikasi LOGISTIK WAREHOUSE adalah sistem manajemen gudang yang mencakup manajemen pembelian, penerimaan barang, pembayaran, dan akuntansi.

---

## Database Schema

### 1. USER MANAGEMENT & AUTHENTICATION

#### users
- id (PK)
- name
- email
- email_verified_at
- password
- number (custom field)
- theme (custom field)
- remember_token
- created_at, updated_at

**Relationships:**
- Has many: roles (via spatie permission)
- Has many: penerimaan (as creator/updater/deleter)
- Has many: pembelian (as creator/updater/deleter)
- Has many: pembayaran (as creator/updater/deleter)
- Has many: pemakaian (as creator/updater/deleter)
- Has many: jurnal (as creator/updater/deleter)

#### user_otps
- id (PK)
- user_id (FK -> users)
- otp_code
- expires_at
- created_at, updated_at

#### password_reset_tokens
- id (PK)
- email
- token
- created_at

#### personal_access_tokens
- id (PK)
- tokenable_type
- tokenable_id
- name
- token
- abilities
- last_used_at
- expires_at
- created_at, updated_at

#### failed_jobs
- id (PK)
- uuid
- connection
- queue
- payload
- exception
- failed_at

---

### 2. PRODUCT CATALOG

#### categories
- id (PK)
- name
- slug (unique)
- created_at, updated_at

**Relationships:**
- Has many: sub_categories
- Has many: collections
- Has many: products

#### sub_categories
- id (PK)
- name
- slug (unique)
- category_id (FK -> categories)
- created_at, updated_at

**Relationships:**
- Belongs to: category
- Has many: products

#### collections
- id (PK)
- name
- slug (unique)
- image
- pdf
- category_id (FK -> categories)
- created_at, updated_at

**Relationships:**
- Belongs to: category
- Has many: products

#### products
- id (PK)
- name
- slug (unique)
- image (nullable)
- collection_id (FK -> collections)
- category_id (FK -> categories)
- sub_category_id (FK -> sub_categories, nullable)
- created_at, updated_at

**Relationships:**
- Belongs to: collection
- Belongs to: category
- Belongs to: sub_category (optional)
- Has many: product_images

#### product_images
- id (PK)
- product_id (FK -> products)
- image
- created_at, updated_at

**Relationships:**
- Belongs to: product

---

### 3. LOCATION MANAGEMENT

#### countries
- id (PK)
- name
- code
- created_at, updated_at

#### states
- id (PK)
- name
- country_id (FK -> countries)
- created_at, updated_at

#### cities
- id (PK)
- name
- state_id (FK -> states)
- created_at, updated_at

#### country_state_city (lookup table)
- id (PK)
- country_id (FK -> countries)
- state_id (FK -> states)
- city_id (FK -> cities)
- created_at, updated_at

#### table_country_extra
- id (PK)
- country_id (FK -> countries)
- extra_field_1
- extra_field_2
- created_at, updated_at

---

### 4. WAREHOUSE & INVENTORY

#### gudang (Warehouse)
- id (PK)
- nama_gudang
- lokasi
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Has many: stok
- Has many: penerimaan
- Has many: kartu_stok

#### satuan (Unit of Measurement)
- id (PK)
- kode_satuan
- nama_satuan
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Has many: barang

#### barang (Items/Products)
- id (PK)
- kode_barang
- nama_barang
- satuan (FK -> satuan, via kode_satuan)
- stok_minimum
- harga_beli_terakhir
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: satuan
- Has many: stok
- Has many: kartu_stok
- Has many: pembelian_detail
- Has many: penerimaan_detail
- Has many: pemakaian_detail

#### stok (Stock)
- id (PK)
- barang_id (FK -> barang)
- gudang_id (FK -> gudang)
- qty
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: barang
- Belongs to: gudang

#### kartu_stok (Stock Card/Journal)
- id (PK)
- tanggal
- barang_id (FK -> barang)
- gudang_id (FK -> gudang)
- jenis_transaksi (enum: 'masuk', 'keluar')
- qty_masuk (decimal, default: 0)
- qty_keluar (decimal, default: 0)
- stok_akhir (decimal, default: 0)
- referensi_id (nullable)
- referensi_tipe (nullable)
- keterangan (text, nullable)
- inserted_user (FK -> users)
- created_at, updated_at

**Relationships:**
- Belongs to: barang
- Belongs to: gudang
- Polymorphic: referensi (can be Pembelian, Penerimaan, Pemakaian, etc.)

---

### 5. PROCUREMENT (PEMBELIAN)

#### suppliers
- id (PK)
- nama_supplier
- alamat
- telepon
- email
- npwp
- inserted_user (FK -> users)
- updated_user (FK -> users)
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Has many: pembelian

#### pembelian (Purchase Order)
- id (PK)
- no_po
- tanggal_po
- supplier_id (FK -> suppliers)
- status
- ppn (decimal, default: 0)
- ppn_master_id (FK -> ppn, nullable)
- diskon (decimal, default: 0)
- biaya_lain (decimal, default: 0)
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: supplier
- Belongs to: ppn (optional)
- Has many: pembelian_detail
- Has one: penerimaan
- Belongs to: creator (user)
- Belongs to: updater (user)
- Belongs to: deleter (user)

#### pembelian_detail (Purchase Order Detail)
- id (PK)
- pembelian_id (FK -> pembelian)
- barang_id (FK -> barang)
- qty
- harga_satuan
- diskon (decimal, default: 0)
- ppn
- subtotal
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: pembelian
- Belongs to: barang

---

### 6. GOODS RECEIPT (PENERIMAAN)

#### penerimaan (Goods Receipt)
- id (PK)
- no_penerimaan
- tanggal_terima
- pembelian_id (FK -> pembelian)
- gudang_id (FK -> gudang)
- diterima_oleh
- ppn (decimal, default: 0)
- diskon (decimal, default: 0)
- biaya_lain (decimal, default: 0)
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: pembelian
- Belongs to: gudang
- Has many: penerimaan_detail
- Has many: pembayaran
- Belongs to: creator (user)
- Belongs to: updater (user)
- Belongs to: deleter (user)

**Computed Attributes:**
- calculated_total: sum of details subtotal - diskon + ppn + biaya_lain
- sisa_hutang: calculated_total - sum of successful payments

#### penerimaan_detail (Goods Receipt Detail)
- id (PK)
- penerimaan_id (FK -> penerimaan)
- barang_id (FK -> barang)
- qty
- harga_satuan
- diskon
- ppn
- subtotal
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: penerimaan
- Belongs to: barang

---

### 7. PAYMENT (PEMBAYARAN)

#### pembayaran (Payment)
- id (PK)
- penerimaan_id (FK -> penerimaan)
- tanggal_bayar
- jumlah_bayar (decimal)
- metode_bayar (nullable: 'tunai', 'transfer', etc.)
- akun_id (FK -> akun, nullable)
- keterangan (text, nullable)
- status (default: 'pending', options: 'pending', 'lunas', 'gagal')
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_at (soft delete)

**Relationships:**
- Belongs to: penerimaan
- Belongs to: akun (optional)
- Belongs to: creator (user)
- Belongs to: updater (user)
- Belongs to: deleter (user)

---

### 8. ACCOUNTING (AKUNTANSI)

#### akun (Chart of Accounts)
- id (PK)
- kode_akun
- nama_akun
- jenis_akun
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Has many: pembayaran
- Has many: jurnal_detail

#### ppn (Tax/PPN Master)
- id (PK)
- nama_ppn
- persentase
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Has many: pembelian

#### jurnal (Journal Entry)
- id (PK)
- no_jurnal
- tanggal_jurnal
- keterangan
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Has many: jurnal_detail
- Belongs to: creator (user)
- Belongs to: updater (user)
- Belongs to: deleter (user)

#### jurnal_detail (Journal Entry Detail)
- id (PK)
- jurnal_id (FK -> jurnal)
- akun_id (FK -> akun)
- debit
- kredit
- keterangan
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: jurnal
- Belongs to: akun

---

### 9. ASSET USAGE/CONSUMPTION (PEMAKAIAN)

#### pemakaian (Usage/Consumption)
- id (PK)
- no_pemakaian
- tanggal_pemakaian
- gudang_id (FK -> gudang)
- keperluan
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: gudang
- Has many: pemakaian_detail
- Belongs to: creator (user)
- Belongs to: updater (user)
- Belongs to: deleter (user)

#### pemakaian_detail (Usage/Consumption Detail)
- id (PK)
- pemakaian_id (FK -> pemakaian)
- barang_id (FK -> barang)
- qty
- keterangan
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: pemakaian
- Belongs to: barang

---

### 10. DEPARTMENTS

#### departemen
- id (PK)
- nama_departemen
- kode_departemen
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: creator (user)
- Belongs to: updater (user)
- Belongs to: deleter (user)

---

### 11. POSTINGAN (Announcements/Posts)

#### postingan
- id (PK)
- judul
- isi
- gambar
- inserted_user (FK -> users)
- updated_user (FK -> users)
- created_at, updated_at
- deleted_by (FK -> users)
- deleted_at (soft delete)

**Relationships:**
- Belongs to: creator (user)
- Belongs to: updater (user)
- Belongs to: deleter (user)

---

### 12. PERMISSIONS (Spatie Laravel Permission)

#### roles
- id (PK)
- name
- guard_name
- created_at, updated_at

#### permissions
- id (PK)
- name
- guard_name
- created_at, updated_at

#### model_has_roles
- role_id (FK -> roles)
- model_type
- model_id
- created_at, updated_at

#### model_has_permissions
- permission_id (FK -> permissions)
- model_type
- model_id
- created_at, updated_at

#### role_has_permissions
- permission_id (FK -> permissions)
- role_id (FK -> roles)
- created_at, updated_at

---

## Entity Relationship Summary

### Core Business Flow:

```
Suppliers → Pembelian (PO) → Penerimaan (Goods Receipt) → Pembayaran (Payment)
                ↓                    ↓
         PembelianDetail      PenerimaanDetail
                ↓                    ↓
              Barang              Barang
                ↓                    ↓
              Stok              KartuStok
                                (Stock Movement)
```

### Key Relationships:

1. **Purchase Flow:**
   - Suppliers create Pembelian (Purchase Order)
   - Pembelian has many PembelianDetail (items ordered)
   - Pembelian creates Penerimaan (Goods Receipt)
   - Penerimaan has many PenerimaanDetail (items received)
   - Penerimaan has many Pembayaran (payments)

2. **Inventory Management:**
   - Barang (Items) are linked to Satuan (Units)
   - Stok tracks current stock per Barang per Gudang
   - KartuStok records all stock movements (masuk/keluar)
   - KartuStok is polymorphic (can reference Pembelian, Penerimaan, Pemakaian)

3. **Accounting Integration:**
   - Pembayaran linked to Akun (Chart of Accounts)
   - Jurnal and JurnalDetail for double-entry bookkeeping
   - PPN (Tax) master linked to Pembelian

4. **User Tracking:**
   - Most tables track inserted_user, updated_user, deleted_by
   - Soft deletes implemented on major entities
   - Creator/Updater/Deleter relationships to Users

---

## Notes

- All main entities use SoftDeletes
- User tracking (inserted_user, updated_user, deleted_by) is implemented across most tables
- The application uses Spatie Laravel Permission for role-based access control
- Stock management uses polymorphic relationships for flexibility
- Financial calculations include PPN, diskon, and biaya_lain
- Payment status tracking: pending, lunas, gagal

---

Generated from analysis of migrations and Eloquent Models