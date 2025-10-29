<?php

namespace App\Livewire;

use App\Models\PostinganModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Postingan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $judul, $isi;
    public $postingan_id;
    public $isOpen = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'judul' => 'required',
        'isi'   => 'required',
    ];


    public function render()
    {
        $postingan = PostinganModel::where('judul', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.postingan', compact('postingan'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $postingan = PostinganModel::findOrFail($id);

        $this->postingan_id = $postingan->id;
        $this->judul = $postingan->judul;
        $this->isi = $postingan->isi;
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        PostinganModel::updateOrCreate(
            ['id' => $this->postingan_id],
            [
                'judul' => $this->judul,
                'isi'   => $this->isi,
              
            ]
        );

        $message = $this->postingan_id
            ? 'Postingan berhasil diperbarui.'
            : 'Supplier berhasil ditambahkan.';

        $this->dispatch('notify', $message);

        $this->closeModal();
        $this->resetInputFields();       
         
    }

    public function delete($id)
    {
        $postingan = PostinganModel::findOrFail($id);
        $postingan->update(['deleted_by' => Auth::id()]);
        $postingan->delete();

        $this->dispatch('notify', 'Postingan berhasil dihapus.');
    }

    private function resetInputFields()
    {
        $this->postingan_id = null;
        $this->judul = '';
        $this->isi = '';
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
