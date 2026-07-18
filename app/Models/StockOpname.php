<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockOpname extends Model
{
    protected $table = 'stock_opname';
    
    protected $fillable = [
        'no_opname',
        'tanggal',
        'gudang_id',
        'jenis',
        'keterangan',
        'status',
        'inserted_user',
        'updated_user',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockOpnameDetail::class, 'stock_opname_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inserted_user');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_user');
    }
}