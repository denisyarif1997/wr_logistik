<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function print($id)
    {
        $pembelian = Pembelian::with(['supplier', 'details.barang', 'creator'])->findOrFail($id);
        return view('pembelian.print', compact('pembelian'));
    }
}
