<?php

namespace App\Livewire;

use App\Models\Akun as ModelsAkun;
use Livewire\Component;
use Livewire\WithPagination;

class Akun extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $kode_akun, $nama_akun, $tipe;
    public $akun_id;
    public $isOpen = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'kode_akun' => 'required|unique:akun,kode_akun',
        'nama_akun' => 'required',
        'tipe' => 'required|in:aset,liabilitas,ekuitas,pendapatan,beban',
    ];

    public function render()
    {
        $akuns = ModelsAkun::where('nama_akun', 'like', '%' . $this->search . '%')
            ->orWhere('kode_akun', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.akun.index', compact('akuns'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->akun_id = null;
        $this->kode_akun = '';
        $this->nama_akun = '';
        $this->tipe = 'aset';
    }

    public function edit($id)
    {
        $akun = ModelsAkun::findOrFail($id);
        $this->akun_id = $akun->id;
        $this->kode_akun = $akun->kode_akun;
        $this->nama_akun = $akun->nama_akun;
        $this->tipe = $akun->tipe;
        $this->openModal();
    }

    public function store()
    {
        $this->validate(
             $this->akun_id ? array_merge($this->rules, ['kode_akun' => 'required|unique:akun,kode_akun,'.$this->akun_id]) : $this->rules
        );

        ModelsAkun::updateOrCreate(
            ['id' => $this->akun_id],
            [
                'kode_akun' => $this->kode_akun,
                'nama_akun' => $this->nama_akun,
                'tipe' => $this->tipe,
            ]
        );

        $message = $this->akun_id ? 'Akun updated successfully.' : 'Akun created successfully.';
        $this->dispatch('notify', $message);
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        ModelsAkun::findOrFail($id)->delete();
        $this->dispatch('notify', 'Akun deleted successfully.');
    }
}
