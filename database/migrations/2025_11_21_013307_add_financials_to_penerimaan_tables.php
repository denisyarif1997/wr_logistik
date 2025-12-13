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
        Schema::table('penerimaan', function (Blueprint $table) {
            $table->decimal('ppn', 15, 2)->default(0)->after('diterima_oleh');
            $table->decimal('diskon', 15, 2)->default(0)->after('ppn');
            $table->decimal('biaya_lain', 15, 2)->default(0)->after('diskon');
        });

        Schema::table('penerimaan_detail', function (Blueprint $table) {
            $table->decimal('harga_satuan', 15, 2)->default(0)->after('qty_diterima');
            $table->decimal('diskon', 15, 2)->default(0)->after('harga_satuan');
            $table->decimal('ppn', 15, 2)->default(0)->after('diskon');
            $table->decimal('subtotal', 15, 2)->default(0)->after('ppn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerimaan', function (Blueprint $table) {
            $table->dropColumn(['ppn', 'diskon', 'biaya_lain']);
        });

        Schema::table('penerimaan_detail', function (Blueprint $table) {
            $table->dropColumn(['harga_satuan', 'diskon', 'ppn', 'subtotal']);
        });
    }
};
