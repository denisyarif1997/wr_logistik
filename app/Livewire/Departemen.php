<?php

namespace App\Livewire;

use App\Models\Departemen as ModelsDepartemen;
use Livewire\Component;
use Livewire\WithPagination;

class Departemen extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $nama_departemen;
    public $departemen_id;
    public $isOpen = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'nama_departemen' => 'required|unique:departemen,nama_departemen',
    ];

    public function render()
    {
        $departemens = ModelsDepartemen::where('nama_departemen', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.departemen.index', compact('departemens'));
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
        $this->departemen_id = null;
        $this->nama_departemen = '';
    }

    public function edit($id)
    {
        $departemen = ModelsDepartemen::findOrFail($id);
        $this->departemen_id = $departemen->id;
        $this->nama_departemen = $departemen->nama_departemen;
        $this->openModal();
    }

    public function store()
    {
        $this->validate(
             $this->departemen_id ? array_merge($this->rules, ['nama_departemen' => 'required|unique:departemen,nama_departemen,'.$this->departemen_id]) : $this->rules
        );

        ModelsDepartemen::updateOrCreate(
            ['id' => $this->departemen_id],
            ['nama_departemen' => $this->nama_departemen]
        );

        $message = $this->departemen_id ? 'Departemen updated successfully.' : 'Departemen created successfully.';
        $this->dispatch('notify', $message);
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        ModelsDepartemen::findOrFail($id)->delete();
        $this->dispatch('notify', 'Departemen deleted successfully.');
    }
}
