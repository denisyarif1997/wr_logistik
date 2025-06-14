<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pembelian extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'pembelian';

    protected $fillable = [
        'no_po',
        'tanggal_po',
        'supplier_id',
        'status',
        'inserted_user',
        'updated_user',
        'created_at',
        'updated_at',
        'deleted_by',
        'deleted_at'    ];

    protected $casts = [
        'tanggal_po' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function details()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function penerimaan()
    {
        return $this->hasOne(Penerimaan::class);
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
