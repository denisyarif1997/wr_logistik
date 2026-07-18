<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpnameDetail extends Model
{
    protected $table = 'stock_opname_detail';
    
    protected $fillable = [
        'stock_opname_id',
        'barang_id',
        'qty_sistem',
        'qty_fisik',
        'selisih',
        'harga_satuan',
        'total_selisih',
        'keterangan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_selisih' => 'decimal:2',
    ];

    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class, 'stock_opname_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}