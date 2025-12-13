<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStok extends Model
{
    use HasFactory;

    protected $table = 'kartu_stok';

    protected $fillable = [
        'tanggal',
        'barang_id',
        'gudang_id',
        'jenis_transaksi',
        'qty_masuk',
        'qty_keluar',
        'stok_akhir',
        'referensi_id',
        'referensi_tipe',
        'keterangan',
        'inserted_user',
    ];

    protected $casts = [
        'tanggal' => 'date',
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
