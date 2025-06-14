<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'stok';

    protected $fillable = [
        'barang_id',
        'gudang_id',
        // 'stok_awal',
        'stok_akhir',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }
}
