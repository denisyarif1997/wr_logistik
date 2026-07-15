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
        Schema::create('penyesuaian_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyesuaian_id');
            $table->foreign('penyesuaian_id')->references('id')->on('penyesuaian')->onDelete('cascade');
            $table->integer('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('restrict');
            $table->decimal('qty', 10, 2)->default(0);
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
        Schema::dropIfExists('penyesuaian_detail');
    }
};