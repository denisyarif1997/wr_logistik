<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Penyesuaian as ModelsPenyesuaian;
use App\Models\PenyesuaianDetail;
use App\Models\Stok;
use App\Models\KartuStok;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Penyesuaian extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $penyesuaian_id, $no_penyesuaian, $tanggal, $gudang_id, $jenis, $keterangan, $status;
    public $isOpen = false;
    public $isShow = false;
    public $search = '';
    public $startDate, $endDate;
    public $details = [];
    public $barangSearch = [];

    protected $rules = [
        'no_penyesuaian' => 'required|unique:penyesuaian,no_penyesuaian',
        'tanggal' => 'required|date',
        'gudang_id' => 'required|exists:gudang,id',
        'jenis' => 'required|in:hilang,rusak,penyesuaian,retur',
        'keterangan' => 'nullable|string',
        'status' => 'required|in:draft,approved',
        'details.*.barang_id' => 'required|exists:barang,id',
        'details.*.qty' => 'required|numeric|min:0.01',
        'details.*.keterangan' => 'nullable|string',
    ];

    public function mount()
    {
        $this->addDetail();
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->tanggal = date('Y-m-d');
    }

    public function addDetail()
    {
        $this->details[] = ['barang_id' => '', 'qty' => 1, 'keterangan' => ''];
        $this->barangSearch[count($this->details) - 1] = '';
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);
        unset($this->barangSearch[$index]);
        $this->details = array_values($this->details);
        $this->barangSearch = array_values($this->barangSearch);
    }

    public function updatedGudangId()
    {
        $this->reset(['details', 'barangSearch']);
        $this->addDetail();
    }

    public function selectBarang($index, $id, $name)
    {
        $this->details[$index]['barang_id'] = $id;
        $this->barangSearch[$index] = $name;
    }

    public function render()
    {
        $penyesuaian = ModelsPenyesuaian::with(['gudang', 'creator', 'updater', 'deleter'])
            ->where(function ($query) {
                $query->whereRaw('LOWER(no_penyesuaian) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereRaw('LOWER(jenis) LIKE ?', ['%' . strtolower($this->search) . '%'])
                    ->orWhereHas('gudang', function ($q) {
                        $q->whereRaw('LOWER(nama_gudang) LIKE ?', ['%' . strtolower($this->search) . '%']);
                    });
            })
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $barangResults = [];
        if ($this->gudang_id) {
            foreach ($this->barangSearch as $index => $term) {
                if (strlen($term) >= 2) {
                    $results = Barang::whereHas('stok', function ($q) {
                        $q->where('gudang_id', $this->gudang_id)->where('stok_akhir', '>', 0);
                    })
                        ->with(['stok' => fn($q) => $q->where('gudang_id', $this->gudang_id)])
                        ->whereRaw('LOWER(nama_barang) LIKE ?', ['%' . strtolower($term) . '%'])
                        ->limit(10)->get();

                    if ($results->count() == 1 && isset($this->details[$index]['barang_id']) && $results->first()->id == $this->details[$index]['barang_id'] && $results->first()->nama_barang == $term) {
                        $barangResults[$index] = [];
                    } else {
                        $barangResults[$index] = $results;
                    }
                } else {
                    $barangResults[$index] = [];
                }
            }
        }

        $gudangs = $this->isOpen ? Gudang::all() : collect();

        return view('livewire.penyesuaian.index', compact('penyesuaian', 'barangResults', 'gudangs'));
    }

    public function generateNoPenyesuaian()
    {
        $today = now()->format('Ymd');
        $prefix = 'ADJ-' . $today . '-';

        $last = ModelsPenyesuaian::whereRaw('LOWER(no_penyesuaian) LIKE ?', [strtolower($prefix) . '%'])
            ->orderBy('no_penyesuaian', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->no_penyesuaian, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->no_penyesuaian = $this->generateNoPenyesuaian();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->reset([
            'penyesuaian_id',
            'no_penyesuaian',
            'tanggal',
            'gudang_id',
            'jenis',
            'keterangan',
            'status',
            'details',
            'barangSearch',
            'isShow',
            'isOpen',
        ]);
    }

    private function resetInputFields()
    {
        $this->penyesuaian_id = null;
        $this->no_penyesuaian = '';
        $this->tanggal = date('Y-m-d');
        $this->gudang_id = '';
        $this->jenis = 'penyesuaian';
        $this->keterangan = '';
        $this->status = 'draft';
        $this->details = [];
        $this->barangSearch = [];
        $this->addDetail();
    }

    public function edit($id)
    {
        $penyesuaian = ModelsPenyesuaian::with('details.barang', 'gudang')->findOrFail($id);

        $this->penyesuaian_id = $penyesuaian->id;
        $this->no_penyesuaian = $penyesuaian->no_penyesuaian;
        $this->tanggal = $penyesuaian->tanggal;
        $this->gudang_id = $penyesuaian->gudang_id;
        $this->jenis = $penyesuaian->jenis;
        $this->keterangan = $penyesuaian->keterangan;
        $this->status = $penyesuaian->status;

        $this->details = [];
        $this->barangSearch = [];
        foreach ($penyesuaian->details as $index => $detail) {
            $this->details[$index] = [
                'id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'qty' => $detail->qty,
                'keterangan' => $detail->keterangan,
            ];
            $this->barangSearch[$index] = $detail->barang->nama_barang ?? '';
        }

        $this->openModal();
    }

    public function show($id)
    {
        $penyesuaian = ModelsPenyesuaian::with('details.barang', 'gudang')->findOrFail($id);

        $this->penyesuaian_id = $penyesuaian->id;
        $this->no_penyesuaian = $penyesuaian->no_penyesuaian;
        $this->tanggal = $penyesuaian->tanggal ? $penyesuaian->tanggal->format('Y-m-d') : null;
        $this->gudang_id = $penyesuaian->gudang_id;
        $this->jenis = $penyesuaian->jenis;
        $this->keterangan = $penyesuaian->keterangan;
        $this->status = $penyesuaian->status;

        $this->details = [];
        $this->barangSearch = [];
        foreach ($penyesuaian->details as $index => $detail) {
            $this->details[$index] = [
                'id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'qty' => $detail->qty,
                'keterangan' => $detail->keterangan,
            ];
            $this->barangSearch[$index] = $detail->barang->nama_barang ?? '';
        }

        $this->isShow = true;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate(
            $this->penyesuaian_id
                ? array_merge($this->rules, ['no_penyesuaian' => 'required|unique:penyesuaian,no_penyesuaian,' . $this->penyesuaian_id])
                : $this->rules
        );

        DB::transaction(function () {
            $userId = Auth::id();

            $data = [
                'no_penyesuaian' => $this->no_penyesuaian,
                'tanggal' => $this->tanggal,
                'gudang_id' => $this->gudang_id,
                'jenis' => $this->jenis,
                'keterangan' => $this->keterangan,
                'status' => $this->status,
                'updated_user' => $userId,
            ];

            if (!$this->penyesuaian_id) {
                $data['inserted_user'] = $userId;
            }

            $penyesuaian = ModelsPenyesuaian::updateOrCreate(
                ['id' => $this->penyesuaian_id],
                $data
            );

            $penyesuaian->details()->delete();

            foreach ($this->details as $detail) {
                $penyesuaian->details()->create([
                    'barang_id' => $detail['barang_id'],
                    'qty' => $detail['qty'],
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }
        });

        $message = $this->penyesuaian_id
            ? 'Penyesuaian stok berhasil diperbarui.'
            : 'Penyesuaian stok berhasil disimpan.';

        $this->dispatch('notify', message: $message, type: 'success');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $penyesuaian = ModelsPenyesuaian::findOrFail($id);
            $penyesuaian->details()->delete();
            $penyesuaian->delete();
        });

        $this->dispatch('notify', message: 'Penyesuaian stok berhasil dihapus.', type: 'success');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Approve penyesuaian -> kurangi stok & buat kartu stok.
     */
    public function validatePenyesuaian($id)
    {
        $penyesuaian = ModelsPenyesuaian::with('details')->find($id);

        if ($penyesuaian && strtolower($penyesuaian->status) === 'draft') {
            DB::transaction(function () use ($penyesuaian) {
                foreach ($penyesuaian->details as $detail) {
                    $stok = Stok::where('barang_id', $detail->barang_id)
                        ->where('gudang_id', $penyesuaian->gudang_id)
                        ->first();

                    if (!$stok) {
                        $stok = Stok::create([
                            'barang_id' => $detail->barang_id,
                            'gudang_id' => $penyesuaian->gudang_id,
                            'stok_akhir' => 0,
                        ]);
                    }

                    $stok->stok_akhir -= $detail->qty;
                    if ($stok->stok_akhir < 0) {
                        $stok->stok_akhir = 0;
                    }
                    $stok->save();

                    $jenisLabel = match ($penyesuaian->jenis) {
                        'hilang' => 'Barang Hilang',
                        'rusak' => 'Barang Rusak',
                        'retur' => 'Retur Barang',
                        default => 'Penyesuaian Stok',
                    };

                    KartuStok::create([
                        'tanggal' => $penyesuaian->tanggal,
                        'barang_id' => $detail->barang_id,
                        'gudang_id' => $penyesuaian->gudang_id,
                        'jenis_transaksi' => 'keluar',
                        'qty_masuk' => 0,
                        'qty_keluar' => $detail->qty,
                        'stok_akhir' => $stok->stok_akhir,
                        'referensi_id' => $penyesuaian->id,
                        'referensi_tipe' => 'Penyesuaian',
                        'keterangan' => $jenisLabel . ' - ' . $penyesuaian->no_penyesuaian,
                        'inserted_user' => auth()->id(),
                    ]);
                }

                $penyesuaian->status = 'approved';
                $penyesuaian->updated_user = auth()->id();
                $penyesuaian->save();

                $this->createJurnalPenyesuaian($penyesuaian, $jenisLabel);
            });

            $this->dispatch('notify', message: 'Penyesuaian stok berhasil di-approve.', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Approval gagal. Status penyesuaian tidak sesuai.', type: 'error');
        }
    }

    /**
     * Batalkan approval -> kembalikan stok & buat kartu stok masuk.
     */
    public function unvalidasi($id)
    {
        $penyesuaian = ModelsPenyesuaian::with('details')->find($id);

        if ($penyesuaian && strtolower($penyesuaian->status) === 'approved') {
            DB::transaction(function () use ($penyesuaian) {
                foreach ($penyesuaian->details as $detail) {
                    $stok = Stok::where('barang_id', $detail->barang_id)
                        ->where('gudang_id', $penyesuaian->gudang_id)
                        ->first();

                    if ($stok) {
                        $stok->stok_akhir += $detail->qty;
                        $stok->save();

                        KartuStok::create([
                            'tanggal' => date('Y-m-d'),
                            'barang_id' => $detail->barang_id,
                            'gudang_id' => $penyesuaian->gudang_id,
                            'jenis_transaksi' => 'masuk',
                            'qty_masuk' => $detail->qty,
                            'qty_keluar' => 0,
                            'stok_akhir' => $stok->stok_akhir,
                            'referensi_id' => $penyesuaian->id,
                            'referensi_tipe' => 'Penyesuaian',
                            'keterangan' => 'Batal Approve Penyesuaian ' . $penyesuaian->no_penyesuaian,
                            'inserted_user' => auth()->id(),
                        ]);
                    }
                }

                $penyesuaian->status = 'draft';
                $penyesuaian->updated_user = auth()->id();
                $penyesuaian->save();

                // Hapus jurnal yang dibuat saat approve agar tidak dobel
                $jurnalLama = Jurnal::where('referensi_tipe', 'Penyesuaian')
                    ->where('referensi_id', $penyesuaian->id)
                    ->first();
                if ($jurnalLama) {
                    $jurnalLama->details()->delete();
                    $jurnalLama->delete();
                }
            });

            $this->dispatch('notify', message: 'Approval penyesuaian berhasil dibatalkan.', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Unapprove gagal. Status penyesuaian tidak sesuai.', type: 'error');
        }
    }

    /**
     * Buat jurnal saat penyesuaian di-approve.
     * Barang keluar dari persediaan -> Debit Beban (4003), Kredit Persediaan Barang (1201).
     */
    private function createJurnalPenyesuaian($penyesuaian, $jenisLabel)
    {
        $totalNilai = 0;
        foreach ($penyesuaian->details as $detail) {
            $barang = Barang::find($detail->barang_id);
            if ($barang && $barang->harga_beli_terakhir) {
                $totalNilai += $barang->harga_beli_terakhir * $detail->qty;
            }
        }

        if ($totalNilai > 0) {
            $jurnal = Jurnal::create([
                'no_jurnal' => 'ADJ-' . str_pad($penyesuaian->id, 5, '0', STR_PAD_LEFT),
                'tanggal' => $penyesuaian->tanggal,
                'keterangan' => $jenisLabel . ' - ' . $penyesuaian->no_penyesuaian,
                'referensi_id' => $penyesuaian->id,
                'referensi_tipe' => 'Penyesuaian',
                'inserted_user' => auth()->id(),
            ]);

            // Debit: Beban Pemakaian Barang (akun id 9)
            $jurnal->details()->create([
                'akun_id' => 9,
                'debit' => $totalNilai,
                'kredit' => 0,
            ]);

            // Kredit: Persediaan Barang (akun id 4)
            $jurnal->details()->create([
                'akun_id' => 4,
                'debit' => 0,
                'kredit' => $totalNilai,
            ]);
        }
    }
}
