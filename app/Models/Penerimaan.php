<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Penerimaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penerimaan';

    protected $fillable = [
        'no_penerimaan',
        'tanggal_terima',
        'pembelian_id',
        'gudang_id',
        'diterima_oleh',
        'inserted_user',
        'updated_user',
        'created_at',
        'updated_at',
        'deleted_by',
        'deleted_at'
    ];

    protected $casts = [
        'tanggal_terima' => 'datetime',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function details()
    {
        return $this->hasMany(PenerimaanDetail::class);
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
