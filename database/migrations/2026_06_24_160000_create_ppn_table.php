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
        Schema::create('ppn', function (Blueprint $table) {
            $table->id();
            $table->decimal('rate', 5, 2)->default(0); // PPN rate in percentage (e.g., 10.00, 11.00, 12.00)
            $table->string('kode_ppn', 50)->unique(); // Code for PPN (e.g., 'PPN_10', 'PPN_11', 'PPN_12')
            $table->text('keterangan')->nullable(); // Description
            $table->boolean('is_active')->default(true); // Whether this PPN rate is active
            $table->timestamp('tanggal_berlaku')->nullable(); // Effective date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppn');
    }
};