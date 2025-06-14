<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'jurnal_detail';

    protected $fillable = [
        'jurnal_id',
        'akun_id',
        'debit',
        'kredit',
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
