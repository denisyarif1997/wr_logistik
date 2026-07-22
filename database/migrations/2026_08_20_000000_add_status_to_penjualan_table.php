<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update default status to 'draft' for new records
        // Status: draft, kunci, selesai, batal
        // - draft: bisa edit & hapus
        // - kunci: bisa edit & hapus (status intermediate)
        // - selesai: tidak bisa edit & hapus (sudah diproses)
        // - batal: tidak bisa edit & hapus (sudah dibatalkan)
        DB::statement("ALTER TABLE penjualan MODIFY status VARCHAR(255) DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE penjualan MODIFY status VARCHAR(255) DEFAULT 'selesai'");
    }
};
