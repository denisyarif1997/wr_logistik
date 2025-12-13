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
        if (!Schema::hasTable('pembayaran')) {
            Schema::create('pembayaran', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('penerimaan_id');
                $table->foreign('penerimaan_id')->references('id')->on('penerimaan')->onDelete('cascade');
                
                $table->date('tanggal_bayar');
                $table->decimal('jumlah_bayar', 15, 2);
                $table->string('metode_bayar')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('status')->default('pending');
                
                $table->unsignedInteger('akun_id')->nullable();
                $table->foreign('akun_id')->references('id')->on('akun')->onDelete('set null');
                
                $table->unsignedBigInteger('inserted_user')->nullable();
                $table->unsignedBigInteger('updated_user')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
