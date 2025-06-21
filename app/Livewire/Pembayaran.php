<?php

namespace App\Livewire;

use App\Models\Penerimaan;
use App\Models\Pembayaran as ModelsPembayaran;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Pembayaran extends Component
{
    use WithPagination;

    public $pembayaran_id;
    public $penerimaan_id;
    public $tanggal_bayar;
    public $jumlah_bayar;
    public $metode_bayar;
    public $keterangan;
    public $status = 'pending';

    public $isOpen = false;
    public $isShow = false;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'penerimaan_id' => 'required|exists:penerimaan,id',
        'tanggal_bayar' => 'required|date',
        'jumlah_bayar' => 'required|numeric|min:0',
        'metode_bayar' => 'nullable|string|max:255',
        'keterangan' => 'nullable|string|max:500',
        'status' => 'required|in:pending,lunas,gagal',
    ];

    public function render()
    {
        $pembayarans = ModelsPembayaran::with(['penerimaan', 'creator', 'updater'])
            ->whereHas('penerimaan', function ($query) {
                $query->where('no_penerimaan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $penerimaans = Penerimaan::latest()->get();

        return view('livewire.pembayaran.index', [
            'pembayarans' => $pembayarans,
            'penerimaans' => $penerimaans,
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        ModelsPembayaran::create([
            'penerimaan_id' => $this->penerimaan_id,
            'tanggal_bayar' => $this->tanggal_bayar,
            'jumlah_bayar' => $this->jumlah_bayar,
            'metode_bayar' => $this->metode_bayar,
            'keterangan' => $this->keterangan,
            'status' => $this->status,
            'inserted_user' => Auth::id(),
        ]);

        session()->flash('message', 'Pembayaran berhasil disimpan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $pembayaran = ModelsPembayaran::findOrFail($id);

        $this->pembayaran_id = $pembayaran->id;
        $this->penerimaan_id = $pembayaran->penerimaan_id;
        $this->tanggal_bayar = $pembayaran->tanggal_bayar;
        $this->jumlah_bayar = $pembayaran->jumlah_bayar;
        $this->metode_bayar = $pembayaran->metode_bayar;
        $this->keterangan = $pembayaran->keterangan;
        $this->status = $pembayaran->status;

        $this->isOpen = true;
        $this->isShow = false;
    }

    public function show($id)
    {
        $this->edit($id);
        $this->isShow = true;
    }

    public function update()
    {
        $this->validate();

        $pembayaran = ModelsPembayaran::findOrFail($this->pembayaran_id);

        $pembayaran->update([
            'penerimaan_id' => $this->penerimaan_id,
            'tanggal_bayar' => $this->tanggal_bayar,
            'jumlah_bayar' => $this->jumlah_bayar,
            'metode_bayar' => $this->metode_bayar,
            'keterangan' => $this->keterangan,
            'status' => $this->status,
            'updated_user' => Auth::id(),
        ]);

        session()->flash('message', 'Pembayaran berhasil diperbarui.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $pembayaran = ModelsPembayaran::findOrFail($id);
        $pembayaran->delete();

        session()->flash('message', 'Pembayaran berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->isShow = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'pembayaran_id',
            'penerimaan_id',
            'tanggal_bayar',
            'jumlah_bayar',
            'metode_bayar',
            'keterangan',
            'status',
        ]);
    }
}
