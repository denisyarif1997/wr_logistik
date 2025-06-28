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
    Route::middleware(['role:admin'])->group(function(){
        Route::resource('user',UserController::class);
        Route::resource('role',RoleController::class);
        Route::resource('permission',PermissionController::class);
        Route::resource('category',CategoryController::class);
        Route::resource('subcategory',SubCateoryController::class);
        Route::resource('collection',CollectionController::class);
        Route::resource('product',ProductController::class);
        Route::get('/get/subcategory',[ProductController::class,'getsubcategory'])->name('getsubcategory');
        Route::get('/remove-external-img/{id}',[ProductController::class,'removeImage'])->name('remove.image');
        Route::get('/suppliers', function () {
            return view('admin.suppliers.index');
        })->name('suppliers.index');
        Route::get('/barang', function () {
            return view('admin.barang.index');
        })->name('barang.index');
        Route::get('/penerimaan', function () {
            return view('admin.penerimaan.index');
        })->name('penerimaan.index');
        Route::get('/pembelian', function () {
            return view('admin.pembelian.index');
        })->name('pembelian.index');
        Route::get('/gudang', function () {
            return view('admin.gudang.index');
        })->name('gudang.index');
        Route::get('/departemen', function () {
            return view('admin.departemen.index');
        })->name('departemen.index');
        Route::get('/akun', function () {
            return view('admin.akun.index');
        })->name('akun.index');
        Route::get('/pemakaian', function () {
            return view('admin.pemakaian.index');
        })->name('pemakaian.index');
        Route::get('/stok', function () {
            return view('admin.stok.index');
        })->name('stok.index');
        Route::get('/jurnal', function () {
            return view('admin.jurnal.index');
        })->name('jurnal.index');
         Route::get('/pembayaran', function () {
            return view('admin.pembayaran.index');
        })->name('pembayaran.index');
        Route::get('/satuan', function () {
            return view('admin.satuan.index');
        })->name('satuan.index');
    });
});
