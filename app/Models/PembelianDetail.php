<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PembelianDetail extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'pembelian_detail';

    protected $fillable = [
        'pembelian_id',
        'barang_id',
        'qty',
        'harga_satuan',
        'subtotal',
        'inserted_user',
        'updated_user',
        'created_at',
        'updated_at',
        'deleted_by',
        'deleted_at' 
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
