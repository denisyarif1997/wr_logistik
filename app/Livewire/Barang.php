<?php

namespace App\Livewire;

use App\Models\Barang as ModelsBarang;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Barang extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $kode_barang, $nama_barang,$barcode, $satuan, $stok_minimum, $harga_beli_terakhir, $is_jual = false, $margin_jual;
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
        'is_jual' => 'boolean',
        'margin_jual' => 'nullable|numeric|min:0',
    ];

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
       // nggunakan query builder untuk mengambil data barang dengan stok aktual
        $barangs = ModelsBarang::query()
            ->select([
                'barang.*',
                'satuan.kode_satuan',
                DB::raw('COALESCE(SUM(stok.stok_akhir), 0) as stok_aktual')
            ])
            ->leftJoin('stok', 'barang.id', '=', 'stok.barang_id')
            ->leftJoin('satuan', 'barang.satuan', '=' , 'satuan.id' )
            ->when($this->search, callback: function ($query) use ($searchTerm) {
                $query->whereRaw("barang.nama_barang LIKE ?", [$searchTerm])
                    // Jika ingin kolom lain juga, bisa tambahkan or:
                    ->orWhereRaw("barang.kode_barang LIKE ?", [$searchTerm]);
            })
            ->groupBy([
                'barang.id',
                'barang.kode_barang',
                'barang.nama_barang',
                'satuan.kode_satuan',
                'barang.stok_minimum',
                'barang.harga_beli_terakhir',
                'barang.created_at'
            ])
            ->orderBy('barang.created_at', 'desc')
            ->paginate(10);
        $satuans = \App\Models\Satuan::all();
            // dd($satuans);
        return view('livewire.barang.index', compact('barangs','satuans'));
    }


    public function create()
    {
        Gate::authorize('barang-create');
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
        $this->is_jual = false;
        $this->margin_jual = '';
    }

    public function edit($id)
    {
        Gate::authorize('barang-edit');
        $barang = ModelsBarang::findOrFail($id);

        $this->barang_id = $barang->id;
        $this->kode_barang = $barang->kode_barang;
        $this->nama_barang = $barang->nama_barang;
        $this->satuan = $barang->satuan;
        $this->stok_minimum = $barang->stok_minimum;
        $this->harga_beli_terakhir = $barang->harga_beli_terakhir;
        $this->is_jual = $barang->is_jual;
        $this->margin_jual = $barang->margin_jual;

        $this->openModal();
    }

    public function store()
    {
        $permission = $this->barang_id ? 'barang-edit' : 'barang-create';
        Gate::authorize($permission);

        $this->validate();

        $userId = auth()->id();

        $isNew = !$this->barang_id;

        $barang = ModelsBarang::updateOrCreate(
            ['id' => $this->barang_id],
            [
                'kode_barang' => $this->kode_barang,
                'nama_barang' => $this->nama_barang,
                'barcode' => $this->barcode,
                'satuan' => $this->satuan,
                'stok_minimum' => $this->stok_minimum,
                'harga_beli_terakhir' => $this->harga_beli_terakhir,
                'is_jual' => $this->is_jual,
                'margin_jual' => $this->margin_jual,
                'updated_user' => $userId,
            ]
        );

        // Tambahkan inserted_user hanya jika baru dibuat
        if ($isNew) {
            $barang->inserted_user = $userId;
            $barang->save();
        }

        $message = $isNew ? 'Barang created successfully.' : 'Barang updated successfully.';

        $this->dispatch('notify', message: $message, type: 'success');

        $this->closeModal();
        $this->resetInputFields();
    }


    public function delete($id)
    {
        Gate::authorize('barang-delete');

        $barang = ModelsBarang::findOrFail($id);
        $barang->update(['deleted_by' => Auth::id()]);
        $barang->delete();

        $this->dispatch('notify', message: 'Barang deleted successfully.', type: 'success');
    }
}
