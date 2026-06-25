<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBarangDetail extends Model
{
    use HasFactory;

    protected $table = 'transfer_barang_detail';

    protected $fillable = [
        'transfer_barang_id',
        'barang_id',
        'qty',
        'keterangan',
        'inserted_user',
        'updated_user',
    ];

    public function transferBarang()
    {
        return $this->belongsTo(TransferBarang::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'inserted_user');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_user');
    }
}