<?php

namespace App\Livewire;

use App\Models\Jurnal as ModelsJurnal;
use App\Models\Akun;
use App\Models\JurnalDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Jurnal extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $jurnal_id;
    public $no_jurnal, $tanggal, $keterangan;
    public $details = [];
    public $isOpen = false;
    public $search = '';

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->details = [
            ['akun_id' => '', 'debit' => 0, 'kredit' => 0]
        ];
    }

    public function render()
    {
        $jurnals = ModelsJurnal::with(['details.akun'])
            ->where('no_jurnal', 'like', '%' . $this->search . '%')
            ->orWhere('keterangan', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $akuns = Akun::orderBy('kode_akun')->get();

        return view('livewire.jurnal.index', [
            'jurnals' => $jurnals,
            'akuns' => $akuns
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
        // Generate Auto Number
        $lastJurnal = ModelsJurnal::latest()->first();
        $nextId = $lastJurnal ? $lastJurnal->id + 1 : 1;
        $this->no_jurnal = 'JU-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    public function addDetail()
    {
        $this->details[] = ['akun_id' => '', 'debit' => 0, 'kredit' => 0];
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);
        $this->details = array_values($this->details);
    }

    public function store()
    {
        $this->validate([
            'no_jurnal' => 'required|unique:jurnal,no_jurnal',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'details.*.akun_id' => 'required|exists:akun,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
        ]);

        // Validate Balance
        $totalDebit = collect($this->details)->sum('debit');
        $totalKredit = collect($this->details)->sum('kredit');

        if ($totalDebit != $totalKredit) {
            $this->addError('balance', 'Total Debit (' . number_format($totalDebit) . ') tidak sama dengan Total Kredit (' . number_format($totalKredit) . ')');
            return;
        }

        if ($totalDebit == 0) {
             $this->addError('balance', 'Total Jurnal tidak boleh 0');
             return;
        }

        DB::transaction(function () {
            $jurnal = ModelsJurnal::create([
                'no_jurnal' => $this->no_jurnal,
                'tanggal' => $this->tanggal,
                'keterangan' => $this->keterangan,
                'referensi_tipe' => 'Manual',
                'inserted_user' => Auth::id(),
            ]);

            foreach ($this->details as $detail) {
                $jurnal->details()->create([
                    'akun_id' => $detail['akun_id'],
                    'debit' => $detail['debit'],
                    'kredit' => $detail['kredit'],
                ]);
            }
        });

        session()->flash('message', 'Jurnal berhasil disimpan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $jurnal = ModelsJurnal::with('details')->findOrFail($id);
        $this->jurnal_id = $jurnal->id;
        $this->no_jurnal = $jurnal->no_jurnal;
        $this->tanggal = $jurnal->tanggal;
        $this->keterangan = $jurnal->keterangan;
        
        $this->details = [];
        foreach ($jurnal->details as $detail) {
            $this->details[] = [
                'akun_id' => $detail->akun_id,
                'debit' => $detail->debit,
                'kredit' => $detail->kredit,
            ];
        }

        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate([
            'no_jurnal' => 'required|unique:jurnal,no_jurnal,' . $this->jurnal_id,
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'details.*.akun_id' => 'required|exists:akun,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
        ]);

        $totalDebit = collect($this->details)->sum('debit');
        $totalKredit = collect($this->details)->sum('kredit');

        if ($totalDebit != $totalKredit) {
            $this->addError('balance', 'Total Debit (' . number_format($totalDebit) . ') tidak sama dengan Total Kredit (' . number_format($totalKredit) . ')');
            return;
        }

        DB::transaction(function () {
            $jurnal = ModelsJurnal::findOrFail($this->jurnal_id);
            $jurnal->update([
                'no_jurnal' => $this->no_jurnal,
                'tanggal' => $this->tanggal,
                'keterangan' => $this->keterangan,
                'updated_user' => Auth::id(),
            ]);

            // Replace details
            $jurnal->details()->delete();
            foreach ($this->details as $detail) {
                $jurnal->details()->create([
                    'akun_id' => $detail['akun_id'],
                    'debit' => $detail['debit'],
                    'kredit' => $detail['kredit'],
                ]);
            }
        });

        session()->flash('message', 'Jurnal berhasil diperbarui.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $jurnal = ModelsJurnal::findOrFail($id);
        $jurnal->details()->delete(); // Delete details first
        $jurnal->delete();

        session()->flash('message', 'Jurnal berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['jurnal_id', 'no_jurnal', 'tanggal', 'keterangan']);
        $this->tanggal = date('Y-m-d');
        $this->details = [
            ['akun_id' => '', 'debit' => 0, 'kredit' => 0]
        ];
        $this->resetErrorBag();
    }
}