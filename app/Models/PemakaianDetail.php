<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemakaianDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'pemakaian_detail';

    protected $fillable = [
        'pemakaian_id',
        'barang_id',
        'qty',
    ];

    public function pemakaian()
    {
        return $this->belongsTo(Pemakaian::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
