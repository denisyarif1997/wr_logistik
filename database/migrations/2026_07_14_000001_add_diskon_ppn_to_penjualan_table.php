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
        Schema::table('penjualan', function (Blueprint $table) {
            $table->decimal('diskon', 5, 2)->default(0)->after('kembalian'); // diskon global %
            $table->decimal('ppn', 15, 2)->default(0)->after('diskon'); // nominal ppn
            $table->decimal('ppn_rate', 5, 2)->default(0)->after('ppn'); // rate ppn %
        });

        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->decimal('diskon', 5, 2)->default(0)->after('subtotal'); // diskon per item %
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->dropColumn('diskon');
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['diskon', 'ppn', 'ppn_rate']);
        });
    }
};