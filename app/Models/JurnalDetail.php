<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JurnalDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jurnal_detail';

    protected $fillable = [
        'jurnal_id',
        'akun_id',
        'nama_akun',
        'debit',
        'kredit',
        'created_at',
        'updated_at',
    ];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function akun()
    {
        return $this->belongsTo(Akun::class);
    }
}
