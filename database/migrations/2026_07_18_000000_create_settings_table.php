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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default COA settings
        DB::table('settings')->insert([
            ['key' => 'coa_persediaan', 'value' => '1001', 'type' => 'integer', 'description' => 'COA untuk Persediaan Barang'],
            ['key' => 'coa_hutang_supplier', 'value' => '2000', 'type' => 'integer', 'description' => 'COA untuk Hutang Supplier'],
            ['key' => 'coa_piutang', 'value' => '1002', 'type' => 'integer', 'description' => 'COA untuk Piutang'],
            ['key' => 'coa_modal', 'value' => '3000', 'type' => 'integer', 'description' => 'COA untuk Modal'],
            ['key' => 'coa_pendapatan_penjualan', 'value' => '4000', 'type' => 'integer', 'description' => 'COA untuk Pendapatan Penjualan'],
            ['key' => 'coa_beban_operasional', 'value' => '5000', 'type' => 'integer', 'description' => 'COA untuk Beban Operasional'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
