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
        Schema::create('transfer_barang_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_barang_id');
            $table->foreign('transfer_barang_id')->references('id')->on('transfer_barang')->onDelete('cascade');
            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('restrict');
            $table->decimal('qty', 10, 2);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('inserted_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('inserted_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_barang_detail');
    }
};