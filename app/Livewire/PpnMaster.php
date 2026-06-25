<?php

namespace App\Livewire;

use App\Models\Ppn;
use Livewire\Component;
use Livewire\WithPagination;

class PpnMaster extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $isOpen = false;
    public $ppn_master_id;
    public $rate;
    public $kode_ppn;
    public $keterangan;
    public $is_active = true;
    public $tanggal_berlaku;

    protected $rules = [
        'rate' => 'required|numeric|min:0|max:100',
        'kode_ppn' => 'required|string|max:50',
        'keterangan' => 'nullable|string|max:255',
        'is_active' => 'required|boolean',
        'tanggal_berlaku' => 'nullable|date',
    ];

    public function mount()
    {
        $this->tanggal_berlaku = now()->format('Y-m-d');
    }

    public function render()
    {
        $ppnMasters = Ppn::orderBy('rate', 'asc')->paginate(10);

        return view('livewire.ppn-master.index', compact('ppnMasters'));
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
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->ppn_master_id = null;
        $this->rate = '';
        $this->kode_ppn = '';
        $this->keterangan = '';
        $this->is_active = true;
        $this->tanggal_berlaku = now()->format('Y-m-d');
    }

    public function edit($id)
    {
        $ppn = Ppn::findOrFail($id);

        $this->ppn_master_id = $ppn->id;
        $this->rate = $ppn->rate;
        $this->kode_ppn = $ppn->kode_ppn;
        $this->keterangan = $ppn->keterangan;
        $this->is_active = $ppn->is_active;
        $this->tanggal_berlaku = $ppn->tanggal_berlaku ? $ppn->tanggal_berlaku->format('Y-m-d') : null;

        $this->openModal();
    }

    public function rules()
    {
        return [
            'rate' => 'required|numeric|min:0|max:100',
            'kode_ppn' => 'required|string|max:50|unique:ppn,kode_ppn,' . ($this->ppn_master_id ?? '') . ',id',
            'keterangan' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'tanggal_berlaku' => 'nullable|date',
        ];
    }

    public function store()
    {
        $this->validate();

        Ppn::updateOrCreate(
            ['id' => $this->ppn_master_id],
            [
                'rate' => $this->rate,
                'kode_ppn' => $this->kode_ppn,
                'keterangan' => $this->keterangan,
                'is_active' => $this->is_active,
                'tanggal_berlaku' => $this->tanggal_berlaku,
            ]
        );

        $message = $this->ppn_master_id
            ? 'PPN Master updated successfully.'
            : 'PPN Master created successfully.';

        $this->dispatch('notify', message: $message, type: 'success');

        $this->closeModal();
    }

    public function delete($id)
    {
        Ppn::findOrFail($id)->delete();

        $this->dispatch('notify', message: 'PPN Master deleted successfully.', type: 'success');
    }

    public function toggleActive($id)
    {
        $ppn = Ppn::findOrFail($id);
        $ppn->is_active = !$ppn->is_active;
        $ppn->save();

        $status = $ppn->is_active ? 'activated' : 'deactivated';
        $this->dispatch('notify', message: "PPN Master {$status} successfully.", type: 'success');
    }
}