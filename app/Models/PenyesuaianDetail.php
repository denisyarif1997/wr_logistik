<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenyesuaianDetail extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'penyesuaian_detail';

    protected $fillable = [
        'penyesuaian_id',
        'barang_id',
        'qty',
        'keterangan',
        'inserted_user',
        'updated_user',
        'created_at',
        'updated_at',
        'deleted_by',
        'deleted_at'
    ];

    public function penyesuaian()
    {
        return $this->belongsTo(Penyesuaian::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}