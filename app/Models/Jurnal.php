<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jurnal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jurnal';

    protected $fillable = [
        'no_jurnal',
        'tanggal',
        'keterangan',
        'referensi_id',
        'referensi_tipe',
        'inserted_user',
        'updated_user',
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
