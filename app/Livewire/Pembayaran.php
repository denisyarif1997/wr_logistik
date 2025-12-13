<?php

namespace App\Livewire;

use App\Models\Penerimaan;
use App\Models\Pembelian;
use App\Models\Pembayaran as ModelsPembayaran;
use App\Models\Akun;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Pembayaran extends Component
{
    use WithPagination;

    public $pembayaran_id;
    public $penerimaan_id;
    public $tanggal_bayar;
    public $jumlah_bayar;
    public $metode_bayar; // Optional, can be derived from Akun name
    public $akun_id;
    public $keterangan;
    public $status = 'pending';

    public $isOpen = false;
    public $isShow = false;
    public $search = '';
    
    // Payment detail modal
    public $showPaymentDetailModal = false;
    public $selectedPenerimaanForDetail;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'penerimaan_id' => 'required|exists:penerimaan,id',
        'tanggal_bayar' => 'required|date',
        'jumlah_bayar' => 'required|numeric|min:0',
        'akun_id' => 'required|exists:akun,id',
        'keterangan' => 'nullable|string|max:500',
    ];

    public function updatedPenerimaanId($value)
    {
        if ($value) {
            $penerimaan = Penerimaan::with(['details'])->find($value);
            if ($penerimaan) {
                // Calculate Total Tagihan
                $subtotalDetails = $penerimaan->details->sum('subtotal');
                $totalTagihan = $subtotalDetails - $penerimaan->diskon + $penerimaan->ppn + $penerimaan->biaya_lain;

                // Calculate Already Paid
                $alreadyPaid = ModelsPembayaran::where('penerimaan_id', $value)
                    ->where('status', '!=', 'gagal')
                    ->when($this->pembayaran_id, function($q) {
                        $q->where('id', '!=', $this->pembayaran_id);
                    })
                    ->sum('jumlah_bayar');

                // Set Remaining
                $this->jumlah_bayar = max(0, $totalTagihan - $alreadyPaid);
            }
        }
    }

    public function render()
    {
        $pembayarans = ModelsPembayaran::with(['penerimaan.pembelian.supplier', 'akun', 'creator', 'updater'])
            ->whereHas('penerimaan', function ($query) {
                $query->where('no_penerimaan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all penerimaan (not just unpaid ones)
        $penerimaansBelumLunas = Penerimaan::with(['pembelian.supplier', 'details', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Filter accounts for Payment (Assets: Kas, Bank)
        $akuns = Akun::where('nama_akun', 'like', '%Kas%')
                     ->orWhere('nama_akun', 'like', '%Bank%')
                     ->orWhere ('nama_akun', 'like','%BANK%')
                     ->get();

        return view('livewire.pembayaran.index', [
            'pembayarans' => $pembayarans,
            'penerimaansBelumLunas' => $penerimaansBelumLunas,
            'akuns' => $akuns,
        ]);
    }

    public function createFromPenerimaan($penerimaanId)
    {
        $this->resetForm();
        $this->penerimaan_id = $penerimaanId;
        $this->tanggal_bayar = date('Y-m-d');
        
        // Trigger updatedPenerimaanId to calculate remaining amount
        $this->updatedPenerimaanId($penerimaanId);
        
        $this->isOpen = true;
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function showPenerimaanDetail($penerimaanId)
    {
        // Redirect to Penerimaan page and trigger show
        return redirect()->route('admin.penerimaan.index')->with('showPenerimaanId', $penerimaanId);
    }

    public function showPaymentDetail($penerimaanId)
    {
        $this->selectedPenerimaanForDetail = Penerimaan::with(['pembelian.supplier', 'pembayaran.akun', 'details'])
            ->findOrFail($penerimaanId);
        $this->showPaymentDetailModal = true;
    }

    public function closePaymentDetail()
    {
        $this->showPaymentDetailModal = false;
        $this->selectedPenerimaanForDetail = null;
    }

    public function store()
    {
        $this->validate();

        DB::transaction(function () {
            $userId = Auth::id();
            $akun = Akun::find($this->akun_id);
            $this->metode_bayar = $akun->nama_akun;

            // Get penerimaan data
            $penerimaan = Penerimaan::with('pembayaran')->find($this->penerimaan_id);
            $totalTagihan = $penerimaan->calculated_total ?? 0;
            $totalBayarSebelumnya = $penerimaan->pembayaran->where('status', '!=', 'gagal')->sum('jumlah_bayar');
            
            // Auto-calculate status based on total payment
            $totalBayarSetelah = $totalBayarSebelumnya + $this->jumlah_bayar;
            
            if ($totalBayarSetelah >= $totalTagihan) {
                $this->status = 'lunas';
            } else {
                $this->status = 'lunas'; // Set as lunas for journal entry
            }

            $data = [
                'penerimaan_id' => $this->penerimaan_id,
                'tanggal_bayar' => $this->tanggal_bayar,
                'jumlah_bayar' => $this->jumlah_bayar,
                'metode_bayar' => $this->metode_bayar,
                'akun_id' => $this->akun_id,
                'keterangan' => $this->keterangan,
                'status' => $this->status,
                'updated_user' => $userId,
            ];

            // Only set inserted_user on create
            if (!$this->pembayaran_id) {
                $data['inserted_user'] = $userId;
            }

            $pembayaran = ModelsPembayaran::updateOrCreate(
                ['id' => $this->pembayaran_id],
                $data
            );

            // Create Journal Entry if status is 'lunas' and it's a new payment
            if ($this->status === 'lunas' && !$this->pembayaran_id) {
                $no_ref = $penerimaan->no_penerimaan ?? '-';

                $jurnal = Jurnal::create([
                    'no_jurnal' => 'PAY-' . str_pad($pembayaran->id, 5, '0', STR_PAD_LEFT),
                    'tanggal' => $this->tanggal_bayar,
                    'keterangan' => 'Pembayaran Penerimaan ' . $no_ref . ' (' . $this->keterangan . ')',
                    'referensi_id' => $pembayaran->id,
                    'referensi_tipe' => 'Pembayaran',
                    'inserted_user' => $userId,
                ]);

                // Debit: Hutang Usaha (ID 5)
                $jurnal->details()->create([
                    'akun_id' => 5, // Hutang Usaha
                    'nama_akun' => 'Hutang Usaha',
                    'debit' => $this->jumlah_bayar,
                    'kredit' => 0,
                ]);

                // Credit: Selected Account (Kas/Bank)
                $jurnal->details()->create([
                    'akun_id' => $this->akun_id,
                    'nama_akun' => $akun->nama_akun,
                    'debit' => 0,
                    'kredit' => $this->jumlah_bayar,
                ]);
            }
        });

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
        $this->akun_id = $pembayaran->akun_id;
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

        DB::transaction(function () {
            $pembayaran = ModelsPembayaran::findOrFail($this->pembayaran_id);
            $akun = Akun::find($this->akun_id);
            $this->metode_bayar = $akun->nama_akun;

            $pembayaran->update([
                'penerimaan_id' => $this->penerimaan_id,
                'tanggal_bayar' => $this->tanggal_bayar,
                'jumlah_bayar' => $this->jumlah_bayar,
                'metode_bayar' => $this->metode_bayar,
                'akun_id' => $this->akun_id,
                'keterangan' => $this->keterangan,
                'status' => $this->status,
                'updated_user' => Auth::id(),
            ]);
            
            // Note: Updating journal entry on edit is complex (need to delete old, create new, or update).
            // For now, we assume edits don't regenerate journals automatically to avoid messing up accounting,
            // or we could implement it if requested.
        });

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
            'akun_id',
            'keterangan',
            'status',
        ]);
        $this->tanggal_bayar = date('Y-m-d');
    }
}
