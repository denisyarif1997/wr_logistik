<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Livewire\Kasir;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function print($id)
    {
        $penjualan = Penjualan::with(['details.barang', 'gudang', 'creator'])->findOrFail($id);
        return view('penjualan.print', compact('penjualan'));
    }
}
