<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'penerimaan_detail';

    protected $fillable = [
        'penerimaan_id',
        'barang_id',
        'qty_diterima',
    ];

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
