<?php

namespace App\Livewire;

use App\Models\Akun;
use App\Models\Setting;
use Livewire\Component;

class Pengaturan extends Component
{
    public $settings = [];
    public $isOpen = false;

    // COA Settings
    public $coa_persediaan;
    public $coa_hutang_supplier;
    public $coa_piutang;
    public $coa_modal;
    public $coa_pendapatan_penjualan;
    public $coa_beban_operasional;
    public $coa_beban_pemakaian_barang;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->coa_persediaan = Setting::getValue('coa_persediaan', 1001);
        $this->coa_hutang_supplier = Setting::getValue('coa_hutang_supplier', 2000);
        $this->coa_piutang = Setting::getValue('coa_piutang', 1002);
        $this->coa_modal = Setting::getValue('coa_modal', 3000);
        $this->coa_pendapatan_penjualan = Setting::getValue('coa_pendapatan_penjualan', 4000);
        $this->coa_beban_operasional = Setting::getValue('coa_beban_operasional', 5000);
        $this->coa_beban_pemakaian_barang = Setting::getValue('coa_beban_pemakaian_barang', 4003);

    }

    public function render()
    {
        $akuns = Akun::orderBy('kode_akun')->get();
        
        return view('livewire.pengaturan.index', [
            'akuns' => $akuns,
        ]);
    }

    public function store()
    {
        $this->validate([
            'coa_persediaan' => 'required|exists:akun,id',
            'coa_hutang_supplier' => 'required|exists:akun,id',
            'coa_piutang' => 'required|exists:akun,id',
            'coa_modal' => 'required|exists:akun,id',
            'coa_pendapatan_penjualan' => 'required|exists:akun,id',
            'coa_beban_operasional' => 'required|exists:akun,id',
            'coa_beban_pemakaian_barang' => 'required|exists:akun,id',
        ]);

        Setting::setValue('coa_persediaan', $this->coa_persediaan);
        Setting::setValue('coa_hutang_supplier', $this->coa_hutang_supplier);
        Setting::setValue('coa_piutang', $this->coa_piutang);
        Setting::setValue('coa_modal', $this->coa_modal);
        Setting::setValue('coa_pendapatan_penjualan', $this->coa_pendapatan_penjualan);
        Setting::setValue('coa_beban_operasional', $this->coa_beban_operasional);
        Setting::setValue('coa_beban_pemakaian_barang', $this->coa_beban_pemakaian_barang);


        $this->dispatch('notify', message: 'Pengaturan COA berhasil disimpan.', type: 'success');
    }
}