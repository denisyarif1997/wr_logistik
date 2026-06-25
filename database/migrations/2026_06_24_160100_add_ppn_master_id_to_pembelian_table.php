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
            $table->unsignedBigInteger('ppn_master_id')->nullable()->after('ppn');
            $table->foreign('ppn_master_id')->references('id')->on('ppn')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropForeign(['ppn_master_id']);
            $table->dropColumn('ppn_master_id');
        });
    }
};