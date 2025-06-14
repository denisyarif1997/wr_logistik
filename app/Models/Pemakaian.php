<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'pemakaian';

    protected $fillable = [
        'no_pemakaian',
        'tanggal_pakai',
        'departemen_id',
        'gudang_id',
        'diajukan_oleh',
    ];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function details()
    {
        return $this->hasMany(PemakaianDetail::class);
    }
}
