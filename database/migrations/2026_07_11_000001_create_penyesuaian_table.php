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
        Schema::create('penyesuaian', function (Blueprint $table) {
            $table->id();
            $table->string('no_penyesuaian')->unique();
            $table->date('tanggal');
            $table->integer('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('restrict');
            $table->enum('jenis', ['hilang', 'rusak', 'penyesuaian'])->default('penyesuaian');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('draft'); // draft, approved
            $table->unsignedBigInteger('inserted_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('inserted_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyesuaian');
    }
};