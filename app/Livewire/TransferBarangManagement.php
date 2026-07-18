<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TransferBarang;
use App\Models\TransferBarangDetail;
use App\Models\Gudang;
use App\Models\Barang;
use App\Models\Stok;
use App\Models\KartuStok;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class TransferBarangManagement extends Component
{
    use WithPagination;

    public $no_transfer;
    public $tanggal_transfer;
    public $gudang_asal_id;
    public $gudang_tujuan_id;
    public $keterangan;
    public $status = 'pending';
    public $transfer_id;
    public $details = [];
    public $search = '';
    public $showForm = false;
    public $isEdit = false;

    protected $rules = [
        // 'no_transfer' => 'required|string|max:255|unique:transfer_barang,no_transfer',
        'tanggal_transfer' => 'required|date',
        'gudang_asal_id' => 'required|exists:gudang,id',
        'gudang_tujuan_id' => 'required|exists:gudang,id|different:gudang_asal_id',
        'keterangan' => 'nullable|string',
        'details' => 'required|array|min:1',
        'details.*.barang_id' => 'required|exists:barang,id',
        'details.*.qty' => 'required|numeric|min:0.01',
        'details.*.keterangan' => 'nullable|string',
    ];

    public function mount()
    {
        $this->tanggal_transfer = date('Y-m-d');
        $this->details = [
            ['barang_id' => '', 'qty' => '', 'keterangan' => '']
        ];
    }

    public function render()
    {
        $gudangList = Gudang::all();
        $barangList = Barang::all();
        
        // Get stock information for each barang in selected warehouses
        $stokInfo = [];
        if (!empty($this->details)) {
            foreach ($this->details as $detail) {
                if (!empty($detail['barang_id'])) {
                    $stokAsal = 0;
                    if ($this->gudang_asal_id) {
                        $stokAsal = Stok::where('barang_id', $detail['barang_id'])
                            ->where('gudang_id', $this->gudang_asal_id)
                            ->value('stok_akhir') ?? 0;
                    }
                    
                    $stokTujuan = 0;
                    if ($this->gudang_tujuan_id) {
                        $stokTujuan = Stok::where('barang_id', $detail['barang_id'])
                            ->where('gudang_id', $this->gudang_tujuan_id)
                            ->value('stok_akhir') ?? 0;
                    }
                    
                    $stokInfo[$detail['barang_id']] = [
                        'asal' => $stokAsal,
                        'tujuan' => $stokTujuan
                    ];
                }
            }
        }
        
        $transfers = TransferBarang::with(['gudangAsal', 'gudangTujuan', 'creator'])
            ->when($this->search, function ($query) {
                $query->where('no_transfer', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
            // dd($transfers);

        return view('livewire.transfer-barang.index', compact('transfers', 'gudangList', 'barangList', 'stokInfo'))
            ->with('title', 'Transfer Barang');
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEdit = false;
    }

    public function edit($id)
    {
        $transfer = TransferBarang::findOrFail($id);
        
        $this->transfer_id = $transfer->id;
        $this->no_transfer = $transfer->no_transfer;
        $this->tanggal_transfer = $transfer->tanggal_transfer->format('Y-m-d');
        $this->gudang_asal_id = $transfer->gudang_asal_id;
        $this->gudang_tujuan_id = $transfer->gudang_tujuan_id;
        $this->keterangan = $transfer->keterangan;
        $this->status = $transfer->status;
        
        $this->details = $transfer->details->map(function ($detail) {
            return [
                'id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'qty' => $detail->qty,
                'keterangan' => $detail->keterangan,
            ];
        })->toArray();

        if (empty($this->details)) {
            $this->details = [['barang_id' => '', 'qty' => '', 'keterangan' => '']];
        }

        $this->showForm = true;
        $this->isEdit = true;
    }

    public function addDetail()
    {
        $this->details[] = ['barang_id' => '', 'qty' => '', 'keterangan' => ''];
    }

    public function removeDetail($index)
    {
        if (count($this->details) > 1) {
            unset($this->details[$index]);
            $this->details = array_values($this->details);
        }
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            if ($this->isEdit) {
                $transfer = TransferBarang::findOrFail($this->transfer_id);
                $transfer->update([
                    'no_transfer' => $this->no_transfer,
                    'tanggal_transfer' => $this->tanggal_transfer,
                    'gudang_asal_id' => $this->gudang_asal_id,
                    'gudang_tujuan_id' => $this->gudang_tujuan_id,
                    'keterangan' => $this->keterangan,
                    'updated_user' => auth()->id(),
                ]);

                // Delete old details
                $transfer->details()->delete();
            } else {
    $transfer = TransferBarang::create([
        'no_transfer' => '',
        'tanggal_transfer' => $this->tanggal_transfer,
        'gudang_asal_id' => $this->gudang_asal_id,
        'gudang_tujuan_id' => $this->gudang_tujuan_id,
        'keterangan' => $this->keterangan,
        'inserted_user' => auth()->id(),
    ]);

    $transfer->update([
        'no_transfer' => 'TRF-' . str_pad($transfer->id, 5, '0', STR_PAD_LEFT)
    ]);
}

            // Save details
            foreach ($this->details as $detail) {
                TransferBarangDetail::create([
                    'transfer_barang_id' => $transfer->id,
                    'barang_id' => $detail['barang_id'],
                    'qty' => $detail['qty'],
                    'keterangan' => $detail['keterangan'],
                    'inserted_user' => auth()->id(),
                ]);
            }

            DB::commit();
            $msg = $this->isEdit ? 'Transfer berhasil diupdate' : 'Transfer berhasil disimpan';
            session()->flash('message', $msg);
            $this->dispatch('notify', message: $msg, type: 'success');
            $this->resetForm();
            $this->showForm = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = 'Gagal menyimpan transfer: ' . $e->getMessage();
            session()->flash('error', $msg);
            $this->dispatch('notify', message: $msg, type: 'error');
        }
    }

    public function proses($id)
    {
        if (Gate::denies('transfer-proses')) {
         $this->dispatch('notify',
         message: 'Anda tidak mempunyai hak akses!',
            type: 'error'
        );
        return;
        }
        
        $transfer = TransferBarang::with('details')->findOrFail($id);
        
        if ($transfer->status !== 'pending') {
            $msg = 'Transfer sudah diproses atau dibatalkan';
            session()->flash('error', $msg);
            $this->dispatch('notify', message: $msg, type: 'error');
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($transfer->details as $detail) {
                $barang = $detail->barang;
                
                // Check stock in source warehouse
                $stokAsal = Stok::where('barang_id', $detail->barang_id)
                    ->where('gudang_id', $transfer->gudang_asal_id)
                    ->first();

                if (!$stokAsal || $stokAsal->stok_akhir <= 0) {
                    throw new \Exception("Stok barang {$barang->nama_barang} di gudang sumber 0");
                }

                if ($stokAsal->stok_akhir < $detail->qty) {
                    throw new \Exception("Stok tidak mencukupi untuk barang: {$barang->nama_barang}");
                }

                // Decrease stock from source warehouse
                $stokAsal->decrement('stok_akhir', $detail->qty);

                // Increase stock in destination warehouse
                $stokTujuan = Stok::where('barang_id', $detail->barang_id)
                    ->where('gudang_id', $transfer->gudang_tujuan_id)
                    ->first();

                if (!$stokTujuan) {
                    $stokTujuan = Stok::create([
                        'barang_id' => $detail->barang_id,
                        'gudang_id' => $transfer->gudang_tujuan_id,
                        'stok_akhir' => $detail->qty,
                    ]);
                } else {
                    $stokTujuan->increment('stok_akhir', $detail->qty);
                }

                // Create stock card for source warehouse (keluar)
                KartuStok::create([
                    'tanggal' => $transfer->tanggal_transfer,
                    'barang_id' => $detail->barang_id,
                    'gudang_id' => $transfer->gudang_asal_id,
                    'jenis_transaksi' => 'keluar',
                    'qty_keluar' => $detail->qty,
                    'stok_akhir' => $stokAsal->stok_akhir - $detail->qty,
                    'referensi_id' => $transfer->id,
                    'referensi_tipe' => TransferBarang::class,
                    'keterangan' => "Transfer ke {$transfer->gudangTujuan->nama_gudang} - {$detail->keterangan}",
                    'inserted_user' => auth()->id(),
                ]);

                // Create stock card for destination warehouse (masuk)
                $stokAkhirTujuan = $stokTujuan->stok_akhir;
                KartuStok::create([
                    'tanggal' => $transfer->tanggal_transfer,
                    'barang_id' => $detail->barang_id,
                    'gudang_id' => $transfer->gudang_tujuan_id,
                    'jenis_transaksi' => 'masuk',
                    'qty_masuk' => $detail->qty,
                    'stok_akhir' => $stokAkhirTujuan,
                    'referensi_id' => $transfer->id,
                    'referensi_tipe' => TransferBarang::class,
                    'keterangan' => "Transfer dari {$transfer->gudangAsal->nama_gudang} - {$detail->keterangan}",
                    'inserted_user' => auth()->id(),
                ]);
            }

            // Update transfer status
            $transfer->update([
                'status' => 'selesai',
                'updated_user' => auth()->id(),
            ]);

            // Update HPP for all barang after stock changes
            $this->updateHppForAllBarang();

            DB::commit();
            $msg = 'Transfer berhasil diproses';
            session()->flash('message', $msg);
            $this->dispatch('notify', message: $msg, type: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = 'Gagal memproses transfer: ' . $e->getMessage();
            session()->flash('error', $msg);
            $this->dispatch('notify', message: $msg, type: 'error');
        }
    }

    public function batalkan($id)
    {
        $transfer = TransferBarang::findOrFail($id);
        
        if ($transfer->status !== 'pending') {
            $msg = 'Transfer sudah diproses atau dibatalkan';
            session()->flash('error', $msg);
            $this->dispatch('notify', message: $msg, type: 'error');
            return;
        }

        $transfer->update([
            'status' => 'dibatalkan',
            'updated_user' => auth()->id(),
        ]);

        $msg = 'Transfer berhasil dibatalkan';
        session()->flash('message', $msg);
        $this->dispatch('notify', message: $msg, type: 'success');
    }

    public function resetForm()
    {
        $this->no_transfer = '';
        $this->tanggal_transfer = date('Y-m-d');
        $this->gudang_asal_id = '';
        $this->gudang_tujuan_id = '';
        $this->keterangan = '';
        $this->status = 'pending';
        $this->transfer_id = null;
        $this->details = [['barang_id' => '', 'qty' => '', 'keterangan' => '']];
        $this->isEdit = false;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Update HPP (Harga Pokok Penjualan) for all barang based on current stock
     * HPP is calculated as weighted average price from all stock movements
     */
    private function updateHppForAllBarang()
    {
        // Get all barang
        $barangs = Barang::all();
        
        foreach ($barangs as $barang) {
            // Get all stock entries for this barang to calculate total quantity
            $totalQty = Stok::where('barang_id', $barang->id)->sum('stok_akhir');
            
            if ($totalQty <= 0) {
                continue;
            }
            
            // Calculate weighted average price from all incoming stock movements
            $kartuStokMasuk = KartuStok::where('barang_id', $barang->id)
                ->where('jenis_transaksi', 'masuk')
                ->where('referensi_tipe', 'Penerimaan')
                ->get();
            
            $totalQtyForAvg = 0;
            $totalValueForAvg = 0;
            
            foreach ($kartuStokMasuk as $kartu) {
                // Get the penerimaan detail to find the purchase price
                $penerimaanDetail = \App\Models\PenerimaanDetail::where('penerimaan_id', $kartu->referensi_id)
                    ->where('barang_id', $barang->id)
                    ->first();
                
                if ($penerimaanDetail) {
                    $harga = $penerimaanDetail->harga_satuan;
                    $qty = $kartu->qty_masuk;
                    $totalQtyForAvg += $qty;
                    $totalValueForAvg += $harga * $qty;
                }
            }
            
            if ($totalQtyForAvg > 0) {
                $hpp = $totalValueForAvg / $totalQtyForAvg;
                $barang->hpp = round($hpp, 2);
                $barang->save();
            }
        }
    }
}
