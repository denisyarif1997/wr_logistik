<?php

namespace App\Livewire;

use App\Models\{Barang, Departemen, Gudang, Pemakaian as ModelsPemakaian, PemakaianDetail, Stok};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class Pemakaian extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $pemakaian_id, $no_pemakaian, $tanggal_pakai, $departemen_id, $gudang_id, $diajukan_oleh;
    public $search = '', $isOpen = false, $viewing = null;
    public $details = [], $allBarang = [];

    protected $rules = [
        'no_pemakaian' => 'required|unique:pemakaian,no_pemakaian',
        'tanggal_pakai' => 'required|date',
        'departemen_id' => 'required|exists:departemen,id',
        'gudang_id' => 'required|exists:gudang,id',
        'diajukan_oleh' => 'required',
        'details.*.barang_id' => 'required|exists:barang,id',
        'details.*.qty' => 'required|numeric|min:1',
    ];

    public function mount()
    {
        $this->tanggal_pakai = date('Y-m-d');
        $this->resetForm();
    }

    public function render()
    {
        $pemakaians = ModelsPemakaian::with(['departemen', 'gudang', 'details.barang'])
            ->where('no_pemakaian', 'like', "%{$this->search}%")
            ->latest()->paginate(10);

        return view('livewire.pemakaian.index', [
            'pemakaians' => $pemakaians,
            'departemens' => Departemen::all(),
            'gudangs' => Gudang::all(),
        ]);
    }

    public function updatingSearch() { $this->resetPage(); }

    public function updatedGudangId()
    {
        $this->loadBarangWithStock();
        $this->details = [['barang_id' => '', 'qty' => 1]];
    }

    public function loadBarangWithStock()
    {
        if (!$this->gudang_id) {
            $this->allBarang = collect();
            return;
        }

        $this->allBarang = Barang::whereHas('stok', function ($q) {
            $q->where('gudang_id', $this->gudang_id)->where('stok_akhir', '>', 0);
        })->with(['stok' => fn($q) => $q->where('gudang_id', $this->gudang_id)])
          ->get();
    }

    public function addDetail() { $this->details[] = ['barang_id' => '', 'qty' => 1]; }
    public function removeDetail($index) { unset($this->details[$index]); $this->details = array_values($this->details); }

    public function create() { $this->resetForm(); $this->openModal(); }
    public function edit($id)
    {
        $p = ModelsPemakaian::with('details')->findOrFail($id);
        $this->fill($p->only('id', 'no_pemakaian', 'tanggal_pakai', 'departemen_id', 'gudang_id', 'diajukan_oleh'));
        $this->pemakaian_id = $p->id;
        $this->details = $p->details->map(fn($d) => ['barang_id' => $d->barang_id, 'qty' => $d->qty])->toArray();
        $this->loadBarangWithStock();
        $this->openModal();
    }

    public function view($id) { $this->viewing = ModelsPemakaian::with('details.barang')->findOrFail($id); }
    public function closeViewModal() { $this->viewing = null; }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; }

    private function resetForm()
    {
        $this->reset(['pemakaian_id', 'no_pemakaian', 'departemen_id', 'gudang_id', 'diajukan_oleh', 'details']);
        $this->tanggal_pakai = date('Y-m-d');
        $this->addDetail();
    }

    private function validateStok($detail)
    {
        $stok = Stok::where('barang_id', $detail['barang_id'])->where('gudang_id', $this->gudang_id)->first();
        if (!$stok || $stok->stok_akhir < $detail['qty']) {
            $barang = Barang::find($detail['barang_id']);
            throw ValidationException::withMessages([
                'details.*.qty' => 'Stok barang ' . ($barang->nama_barang ?? 'tidak diketahui') . ' tidak mencukupi. Sisa: ' . ($stok->stok_akhir ?? 0),
            ]);
        }
    }

    public function store()
    {
        $rules = $this->rules;
        if ($this->pemakaian_id) {
            $rules['no_pemakaian'] = 'required|unique:pemakaian,no_pemakaian,' . $this->pemakaian_id;
        }
        $this->validate($rules);

        DB::transaction(function () {
            $details = collect($this->details)->groupBy('barang_id')->map(function ($items) {
                return ['barang_id' => $items[0]['barang_id'], 'qty' => $items->sum('qty')];
            })->values()->toArray();

            if ($this->pemakaian_id) {
                $old = ModelsPemakaian::with('details')->find($this->pemakaian_id);
                foreach ($old->details as $d) {
                    $stok = Stok::where('barang_id', $d->barang_id)->where('gudang_id', $old->gudang_id)->first();
                    if ($stok) { $stok->stok_akhir += $d->qty; $stok->save(); }
                }
            }

            foreach ($details as $d) { $this->validateStok($d); }

            $pemakaian = ModelsPemakaian::updateOrCreate(
                ['id' => $this->pemakaian_id],
                [
                    'no_pemakaian' => $this->no_pemakaian,
                    'tanggal_pakai' => $this->tanggal_pakai,
                    'departemen_id' => $this->departemen_id,
                    'gudang_id' => $this->gudang_id,
                    'diajukan_oleh' => $this->diajukan_oleh,
                ]
            );

            $pemakaian->details()->delete();

            foreach ($details as $d) {
                $pemakaian->details()->create($d);
                $stok = Stok::where('barang_id', $d['barang_id'])->where('gudang_id', $this->gudang_id)->first();
                if ($stok) { $stok->stok_akhir -= $d['qty']; $stok->save(); }
            }
        });

        $this->dispatch('notify', $this->pemakaian_id ? 'Pemakaian diperbarui.' : 'Pemakaian berhasil disimpan.');
        $this->closeModal();
        $this->resetForm();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $pemakaian = ModelsPemakaian::with('details')->findOrFail($id);
            foreach ($pemakaian->details as $d) {
                $stok = Stok::where('barang_id', $d->barang_id)->where('gudang_id', $pemakaian->gudang_id)->first();
                if ($stok) { $stok->stok_akhir += $d->qty; $stok->save(); }
            }
            $pemakaian->details()->delete();
            $pemakaian->delete();
        });

        $this->dispatch('notify', 'Pemakaian berhasil dihapus.');
    }
}
