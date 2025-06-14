<?php

namespace App\Livewire;

use App\Models\Gudang as ModelsGudang;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Gudang extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $nama_gudang, $lokasi;
    public $gudang_id;
    public $isOpen = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'nama_gudang' => 'required|unique:gudang,nama_gudang',
        'lokasi' => 'required',
    ];

    public function render()
    {
        $gudangs = ModelsGudang::where('nama_gudang', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.gudang.index', compact('gudangs'));
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
        $this->gudang_id = null;
        $this->nama_gudang = '';
        $this->lokasi = '';
    }

    public function edit($id)
    {
        $gudang = ModelsGudang::findOrFail($id);
        $this->gudang_id = $gudang->id;
        $this->nama_gudang = $gudang->nama_gudang;
        $this->lokasi = $gudang->lokasi;
        $this->openModal();
    }

    public function store()
    {
        $this->validate(
            $this->gudang_id
                ? array_merge($this->rules, ['nama_gudang' => 'required|unique:gudang,nama_gudang,' . $this->gudang_id])
                : array_merge($this->rules, ['nama_gudang' => 'required|unique:gudang,nama_gudang'])
        );

        $userId = auth()->id();

        $gudang = ModelsGudang::updateOrCreate(
            ['id' => $this->gudang_id],
            [
                'nama_gudang' => $this->nama_gudang,
                'lokasi' => $this->lokasi,
                'updated_user' => $userId
            ]
        );

        // Jika gudang baru dibuat (tidak ada id), tambahkan inserted_user
        if (!$this->gudang_id) {
            $gudang->inserted_user = $userId;
            $gudang->save();
        }

        $message = $this->gudang_id ? 'Gudang updated successfully.' : 'Gudang created successfully.';

        $this->dispatch('notify', $message);
        $this->closeModal();
        $this->resetInputFields();
    }


    public function delete($id)
    {
        $gudang = ModelsGudang::findOrFail($id);
        $gudang->update(['deleted_by' => Auth::id()]);
        $gudang->delete();

        $this->dispatch('notify', 'Gudang deleted successfully.');
    }
}
