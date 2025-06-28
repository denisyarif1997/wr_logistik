<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'satuan',
        'stok_minimum',
        'harga_beli_terakhir',
        'inserted_user',
        'updated_user',
        'created_at',
        'updated_at',
        'deleted_by',
        'deleted_at'
    ];

    public function stok()
    {
        return $this->hasMany(Stok::class);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan', 'kode_satuan');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'inserted_user');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_user');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    

}
