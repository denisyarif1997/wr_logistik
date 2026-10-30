<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Satuan as ModelsSatuan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;


class Satuan extends Component
{
    use WithPagination;
    use \App\Traits\Exportable;

    protected $paginationTheme = 'bootstrap';

    public $kode_satuan, $nama_satuan;
    public $satuan_id;
    public $isOpen = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'kode_satuan' => 'required',
    ];
    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        $satuans = ModelsSatuan::query()
            ->when($this->search, function ($query) use ($searchTerm) {
                $query->where('kode_satuan', 'like', $searchTerm);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.satuan.index', compact('satuans'));
    }

    public function export()
    {
        $searchTerm = '%' . $this->search . '%';
        $satuans = ModelsSatuan::query()
            ->when($this->search, function ($query) use ($searchTerm) {
                $query->where('kode_satuan', 'like', $searchTerm);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = ['Kode Satuan', 'Tanggal Dibuat', 'Terakhir Diperbarui'];
        $rows = $satuans->map(function ($s) {
            return [
                $s->kode_satuan,
                $s->created_at ? $s->created_at->format('Y-m-d H:i') : '',
                $s->updated_at ? $s->updated_at->format('Y-m-d H:i') : '',
            ];
        });

        return $this->downloadCsv('data_satuan', $headers, $rows);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
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
        $this->satuan_id = null;
        $this->kode_satuan = '';
        $this->nama_satuan = '';
    }
    public function edit($id)
    {
        $satuan = ModelsSatuan::findOrFail($id);

        $this->satuan_id = $satuan->id;
        $this->kode_satuan = $satuan->kode_satuan;
        $this->nama_satuan = $satuan->nama_satuan;

        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        if ($this->satuan_id) {
            // Update existing record
            $satuan = ModelsSatuan::findOrFail($this->satuan_id);
            $satuan->update([
                'kode_satuan' => $this->kode_satuan,
                // 'nama_satuan' => $this->nama_satuan,
            ]);
            session()->flash('message', 'Data satuan berhasil diperbarui.');
        } else {
            // Create new record
            ModelsSatuan::create([
                'kode_satuan' => $this->kode_satuan,
                // 'nama_satuan' => $this->nama_satuan,
            ]);
            session()->flash('message', 'Data satuan berhasil ditambahkan.');
        }
        // $message = $this->kode_satuan ? 'Akun updated successfully.' : 'Akun created successfully.';
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id) 
   {
    $satuan = ModelsSatuan::findOrFail($id);
    $satuan->delete();

    session()->flash('message', 'Data satuan berhasil dihapus.');

    // Optional: reset input dan tutup modal jika sedang dalam mode edit
    $this->resetInputFields();
    $this->closeModal();
}


}
