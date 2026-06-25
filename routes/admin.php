<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCateoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard',[ProfileController::class,'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User Management - requires user permission
    Route::middleware(['can:user'])->group(function(){
        Route::resource('user',UserController::class);
    });
    
    // Role Management - requires role permission
    Route::middleware(['can:role'])->group(function(){
        Route::resource('role',RoleController::class);
    });
    
    // Permission Management - requires permission permission
    Route::middleware(['can:permission'])->group(function(){
        Route::resource('permission',PermissionController::class);
    });
    
    // Category Management - requires category permission
    Route::middleware(['can:category'])->group(function(){
        Route::resource('category',CategoryController::class);
        Route::resource('subcategory',SubCateoryController::class);
        Route::resource('collection',CollectionController::class);
        Route::get('/get/subcategory',[ProductController::class,'getsubcategory'])->name('getsubcategory');
        Route::get('/remove-external-img/{id}',[ProductController::class,'removeImage'])->name('remove.image');
    });
    
    // Product Management - requires product permission
    Route::middleware(['can:product'])->group(function(){
        Route::resource('product',ProductController::class);
    });
    
    // Master Data - requires respective permissions
    Route::middleware(['can:barang'])->group(function(){
        Route::get('/barang', function () {
            return view('admin.barang.index');
        })->name('barang.index');
    });
    
    Route::middleware(['can:supplier'])->group(function(){
        Route::get('/suppliers', function () {
            return view('admin.suppliers.index');
        })->name('suppliers.index');
    });
    
    Route::middleware(['can:gudang'])->group(function(){
        Route::get('/gudang', function () {
            return view('admin.gudang.index');
        })->name('gudang.index');
    });
    
    Route::middleware(['can:departemen'])->group(function(){
        Route::get('/departemen', function () {
            return view('admin.departemen.index');
        })->name('departemen.index');
    });
    
    Route::middleware(['can:coa'])->group(function(){
        Route::get('/akun', function () {
            return view('admin.akun.index');
        })->name('akun.index');
    });
    
    Route::middleware(['can:ppn'])->group(function(){
        Route::get('/ppn', function () {
            return view('admin.ppn.index');
        })->name('ppn.index');
    });
    
    Route::middleware(['can:satuan'])->group(function(){
        Route::get('/satuan', function () {
            return view('admin.satuan.index');
        })->name('satuan.index');
    });
    
    // Transaksi - requires respective permissions
    Route::middleware(['can:pembelian'])->group(function(){
        Route::get('/pembelian', function () {
            return view('admin.pembelian.index');
        })->name('pembelian.index');
        Route::get('/pembelian/{id}/print', [App\Http\Controllers\PembelianController::class, 'print'])->name('pembelian.print');
    });
    
    Route::middleware(['can:penerimaan'])->group(function(){
        Route::get('/penerimaan', function () {
            return view('admin.penerimaan.index');
        })->name('penerimaan.index');
    });
    
    Route::middleware(['can:pembayaran'])->group(function(){
        Route::get('/pembayaran', function () {
            return view('admin.pembayaran.index');
        })->name('pembayaran.index');
    });
    
    Route::middleware(['can:pemakaian'])->group(function(){
        Route::get('/pemakaian', function () {
            return view('admin.pemakaian.index');
        })->name('pemakaian.index');
    });
    
    Route::middleware(['can:transfer'])->group(function(){
        Route::get('/transfer-barang', \App\Livewire\TransferBarangManagement::class)->name('transfer.index');
    });
    
    // Laporan - requires respective permissions
    Route::middleware(['can:stok'])->group(function(){
        Route::get('/stok', function () {
            return view('admin.stok.index');
        })->name('stok.index');
    });
    
    Route::middleware(['can:jurnal'])->group(function(){
        Route::get('/jurnal', function () {
            return view('admin.jurnal.index');
        })->name('jurnal.index');
    });
});
