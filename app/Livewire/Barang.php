<?php

namespace App\Livewire;

use App\Models\Barang as ModelsBarang;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Barang extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $kode_barang, $nama_barang, $satuan, $stok_minimum, $harga_beli_terakhir;
    public $barang_id;
    public $isOpen = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'kode_barang' => 'required',
        'nama_barang' => 'required',
        'satuan' => 'required',
        'stok_minimum' => 'required|numeric',
        'harga_beli_terakhir' => 'required|numeric',
    ];

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        $barangs = ModelsBarang::query()
            ->select([
                'barang.*',
                DB::raw('COALESCE(SUM(stok.stok_akhir), 0) as stok_aktual')
            ])
            ->leftJoin('stok', 'barang.id', '=', 'stok.barang_id')
            ->when($this->search, callback: function ($query) use ($searchTerm) {
                $query->whereRaw("barang.nama_barang LIKE ?", [$searchTerm])
                    // Jika ingin kolom lain juga, bisa tambahkan or:
                    ->orWhereRaw("barang.kode_barang LIKE ?", [$searchTerm]);
            })
            ->groupBy([
                'barang.id',
                'barang.kode_barang',
                'barang.nama_barang',
                'barang.satuan',
                'barang.stok_minimum',
                'barang.harga_beli_terakhir',
                'barang.created_at'
            ])
            ->orderBy('barang.created_at', 'desc')
            ->paginate(10);

        return view('livewire.barang.index', compact('barangs'));
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
        $this->barang_id = null;
        $this->kode_barang = '';
        $this->nama_barang = '';
        $this->satuan = '';
        $this->stok_minimum = '';
        $this->harga_beli_terakhir = '';
    }

    public function edit($id)
    {
        $barang = ModelsBarang::findOrFail($id);

        $this->barang_id = $barang->id;
        $this->kode_barang = $barang->kode_barang;
        $this->nama_barang = $barang->nama_barang;
        $this->satuan = $barang->satuan;
        $this->stok_minimum = $barang->stok_minimum;
        $this->harga_beli_terakhir = $barang->harga_beli_terakhir;

        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        $userId = auth()->id();

        $isNew = !$this->barang_id;

        $barang = ModelsBarang::updateOrCreate(
            ['id' => $this->barang_id],
            [
                'kode_barang' => $this->kode_barang,
                'nama_barang' => $this->nama_barang,
                'satuan' => $this->satuan,
                'stok_minimum' => $this->stok_minimum,
                'harga_beli_terakhir' => $this->harga_beli_terakhir,
                'updated_user' => $userId,
            ]
        );

        // Tambahkan inserted_user hanya jika baru dibuat
        if ($isNew) {
            $barang->inserted_user = $userId;
            $barang->save();
        }

        $message = $isNew ? 'Barang created successfully.' : 'Barang updated successfully.';

        $this->dispatch('notify', $message);

        $this->closeModal();
        $this->resetInputFields();
    }


    public function delete($id)
    {
        $barang = ModelsBarang::findOrFail($id);
        $barang->update(['deleted_by' => Auth::id()]);
        $barang->delete();

        $this->dispatch('notify', 'Barang deleted successfully.');
    }
}
