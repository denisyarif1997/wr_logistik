<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenjualanDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'penjualan_detail';

    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'qty',
        'harga_satuan',
        'subtotal',
        'inserted_user',
        'updated_user',
        'created_at',
        'updated_at',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}