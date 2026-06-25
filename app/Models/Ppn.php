<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppn extends Model
{
    use HasFactory;

    protected $table = 'ppn';

    protected $fillable = [
        'rate',
        'kode_ppn',
        'keterangan',
        'is_active',
        'tanggal_berlaku',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
        'tanggal_berlaku' => 'datetime',
    ];

    /**
     * Scope to get only active PPN rates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get PPN rates by effective date
     */
    public function scopeBerlaku($query, $date = null)
    {
        $date = $date ?? now();
        return $query->where(function ($q) use ($date) {
            $q->whereNull('tanggal_berlaku')
              ->orWhere('tanggal_berlaku', '<=', $date);
        });
    }

    /**
     * Get the default/current PPN rate
     */
    public static function getCurrentRate()
    {
        return self::active()
            ->berlaku()
            ->orderBy('tanggal_berlaku', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }
}