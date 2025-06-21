<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembayaran_penerimaan';

    protected $fillable = [
        'penerimaan_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_bayar',
        'keterangan',
        'status',
        'inserted_user',
        'updated_user',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];  

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
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
            'lunas' => 'Lunas',
            'gagal' => 'Gagal',
            default => 'Unknown',
        };
    }
    public function getFormattedTanggalBayarAttribute()
    {
        return $this->tanggal_bayar ? $this->tanggal_bayar->format('d-m-Y') : null;
    }
    public function getFormattedJumlahBayarAttribute()
    {
        return number_format($this->jumlah_bayar, 2, ',', '.');
    }
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d-m-Y H:i:s') : null;
    }
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d-m-Y H:i:s') : null;
    }
    public function getFormattedDeletedAtAttribute()
    {
        return $this->deleted_at ? $this->deleted_at->format('d-m-Y H:i:s') : null;
    }
    public function getCreatedByAttribute()
    {
        return $this->creator ? $this->creator->name : 'System';
    }
    public function getUpdatedByAttribute()
    {
        return $this->updater ? $this->updater->name : 'System';
    }
    public function getDeletedByAttribute()
    {
        return $this->deleter ? $this->deleter->name : 'System';
    }
    public function scopeFilter($query, $filters)
    {
        if (isset($filters['search'])) {
            $query->where('penerimaan_id', 'like', '%' . $filters['search'] . '%');
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['tanggal_bayar'])) {
            $query->whereDate('tanggal_bayar', $filters['tanggal_bayar']);
        }
    }
    public function scopeWithRelations($query)
    {
        return $query->with(['penerimaan', 'creator', 'updater', 'deleter']);
    }
    public function scopePaginatePembayaran($query, $perPage = 10)
    {
        return $query->paginate($perPage);
    }
    public function scopeGetPembayaranById($query, $id)
    {
        return $query->with(['penerimaan', 'creator', 'updater', 'deleter'])->findOrFail($id);
    }
}
