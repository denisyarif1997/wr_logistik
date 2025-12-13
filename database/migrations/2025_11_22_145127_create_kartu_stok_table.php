<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kartu_stok', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang');
            $table->integer('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->decimal('qty_masuk', 10, 2)->default(0);
            $table->decimal('qty_keluar', 10, 2)->default(0);
            $table->decimal('stok_akhir', 10, 2)->default(0);
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->string('referensi_tipe')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('inserted_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_stok');
    }
};
