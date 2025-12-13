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
        Schema::table('pembelian', function (Blueprint $table) {
            $table->decimal('ppn', 15, 2)->default(0)->after('status');
            $table->decimal('diskon', 15, 2)->default(0)->after('ppn');
            $table->decimal('biaya_lain', 15, 2)->default(0)->after('diskon');
        });

        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->decimal('diskon', 15, 2)->default(0)->after('harga_satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn(['ppn', 'diskon', 'biaya_lain']);
        });

        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->dropColumn('diskon');
        });
    }
};
