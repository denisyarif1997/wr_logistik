<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranPenerimaanTable extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_penerimaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penerimaan_id');
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('metode_bayar')->nullable(); // tunai, transfer, dll
            $table->text('keterangan')->nullable();
            $table->string('status')->default('pending'); // pending, lunas, gagal, dll
            $table->unsignedBigInteger('inserted_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at

            // Foreign key constraint
            $table->foreign('penerimaan_id')->references('id')->on('penerimaan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_penerimaan');
    }
}

