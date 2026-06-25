<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Suppliers;
use Illuminate\Support\Facades\DB;
use App\Models\User;


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
        'ppn',
        'ppn_master_id',
        'diskon',
        'biaya_lain',
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

    public function ppnMaster()
    {
        return $this->belongsTo(Ppn::class, 'ppn_master_id');
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

    // Scope filter tanggal & search (supplier/no_po)
    public function scopeFilter($query, $startDate, $endDate, $search)
    {
        return $query
            ->when($startDate, fn($q) => $q->whereDate('tanggal_po', '>=', $startDate))
            ->when($endDate,   fn($q) => $q->whereDate('tanggal_po', '<=', $endDate))
            ->when($search, function($q) use ($search) {
                $q->whereRaw('LOWER(no_po) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereHas('supplier', fn($s) =>
                      $s->whereRaw('LOWER(nama_supplier) LIKE ?', ['%' . strtolower($search) . '%'])
                  );
            });
    }

    // Search supplier untuk form PO (autocomplete/dropdown)
    public static function searchSupplier(string $keyword)
    {
        return Suppliers::query()
            ->whereRaw('LOWER(nama_supplier) LIKE ?', ['%' . strtolower($keyword) . '%'])
            ->orWhereRaw('LOWER(kode_supplier) LIKE ?', ['%' . strtolower($keyword) . '%'])
            ->orderBy('nama_supplier')
            ->limit(20)
            ->get(['id', 'nama_supplier', 'kode_supplier']);
    }

  
    

}
