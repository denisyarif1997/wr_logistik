<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transfer_barang';

    protected $fillable = [
        'no_transfer',
        'tanggal_transfer',
        'gudang_asal_id',
        'gudang_tujuan_id',
        'keterangan',
        'status',
        'inserted_user',
        'updated_user',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_transfer' => 'date',
    ];

    public function gudangAsal()
    {
        return $this->belongsTo(Gudang::class, 'gudang_asal_id');
    }

    public function gudangTujuan()
    {
        return $this->belongsTo(Gudang::class, 'gudang_tujuan_id');
    }

    public function details()
    {
        return $this->hasMany(TransferBarangDetail::class);
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

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    public function getFormattedTanggalTransferAttribute()
    {
        return $this->tanggal_transfer ? $this->tanggal_transfer->format('d-m-Y') : null;
    }

    public function getCreatedByAttribute()
    {
        return $this->creator ? $this->creator->name : 'System';
    }

    public function getUpdatedByAttribute()
    {
        return $this->updater ? $this->updater->name : 'System';
    }
}