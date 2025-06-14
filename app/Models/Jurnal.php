<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'jurnal';

    protected $fillable = [
        'no_jurnal',
        'tanggal',
        'keterangan',
        'referensi_id',
        'referensi_tipe',
    ];

    public function details()
    {
        return $this->hasMany(JurnalDetail::class);
    }

    public function referensi()
    {
        return $this->morphTo(__FUNCTION__, 'referensi_tipe', 'referensi_id');
    }
}
