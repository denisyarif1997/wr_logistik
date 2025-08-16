<?php

namespace App\Livewire;

use App\Models\Suppliers as ModelsSuppliers;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Suppliers extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $nama_supplier, $alamat, $telepon, $email, $npwp;
    public $supplier_id;
    public $isOpen = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'nama_supplier' => 'required',
        'alamat'        => 'required',
        'telepon'       => 'required',
        'email'         => 'required|email',
        'npwp'          => 'required',
    ];

    public function render()
    {
        $suppliers = ModelsSuppliers::whereNull('deleted_at')
            ->where('nama_supplier', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.suppliers', compact('suppliers'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $supplier = ModelsSuppliers::findOrFail($id);

        $this->supplier_id    = $supplier->id;
        $this->nama_supplier  = $supplier->nama_supplier;
        $this->alamat         = $supplier->alamat;
        $this->telepon        = $supplier->telepon;
        $this->email          = $supplier->email;
        $this->npwp           = $supplier->npwp;
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        ModelsSuppliers::updateOrCreate(
            ['id' => $this->supplier_id],
            [
                'nama_supplier' => $this->nama_supplier,
                'alamat'        => $this->alamat,
                'telepon'       => $this->telepon,
                'email'         => $this->email,
                'npwp'          => $this->npwp,
                'inserted_user' => $this->supplier_id ? null : Auth::id(),
                'updated_user'  => $this->supplier_id ? Auth::id() : null,
                
            ]
        );

        $message = $this->supplier_id
            ? 'Supplier berhasil diperbarui.'
            : 'Supplier berhasil ditambahkan.';

        $this->dispatch('notify', $message);

        $this->closeModal();
        $this->resetInputFields();       
         
    }

    public function delete($id)
    {
        $supplier = ModelsSuppliers::findOrFail($id);
        $supplier->update(['deleted_by' => Auth::id()]);
        $supplier->delete();

        $this->dispatch('notify', 'Supplier berhasil dihapus.');
    }

    private function resetInputFields()
    {
        $this->supplier_id = null;
        $this->nama_supplier = '';
        $this->alamat = '';
        $this->telepon = '';
        $this->email = '';
        $this->npwp = '';
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }
}
