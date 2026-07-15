<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Penjualan extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'penjualan';

    protected $fillable = [
        'no_penjualan',
        'tanggal',
        'gudang_id',
        'kas_id',
        'diskon',
        'ppn',
        'ppn_rate',
        'total',
        'bayar',
        'kembalian',
        'status',
        'keterangan',
        'inserted_user',
        'updated_user',
        'created_at',
        'updated_at',
        'deleted_by',
        'deleted_at'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
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