<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Pengaturan COA (Chart of Accounts)</h3>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="store">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coa_persediaan">COA Persediaan Barang</label>
                            <select wire:model="coa_persediaan" class="form-control" id="coa_persediaan">
                                <option value="">Pilih COA</option>
                                @foreach($akuns as $akun)
                                    @if($akun->tipe == 'aset')
                                        <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('coa_persediaan') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coa_hutang_supplier">COA Hutang Supplier</label>
                            <select wire:model="coa_hutang_supplier" class="form-control" id="coa_hutang_supplier">
                                <option value="">Pilih COA</option>
                                @foreach($akuns as $akun)
                                    @if($akun->tipe == 'liabilitas')
                                        <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('coa_hutang_supplier') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coa_piutang">COA Piutang</label>
                            <select wire:model="coa_piutang" class="form-control" id="coa_piutang">
                                <option value="">Pilih COA</option>
                                @foreach($akuns as $akun)
                                    @if($akun->tipe == 'aset')
                                        <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('coa_piutang') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coa_modal">COA Modal</label>
                            <select wire:model="coa_modal" class="form-control" id="coa_modal">
                                <option value="">Pilih COA</option>
                                @foreach($akuns as $akun)
                                    @if($akun->tipe == 'ekuitas')
                                        <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('coa_modal') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coa_pendapatan_penjualan">COA Pendapatan Penjualan</label>
                            <select wire:model="coa_pendapatan_penjualan" class="form-control" id="coa_pendapatan_penjualan">
                                <option value="">Pilih COA</option>
                                @foreach($akuns as $akun)
                                    @if($akun->tipe == 'pendapatan')
                                        <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('coa_pendapatan_penjualan') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coa_beban_operasional">COA Beban Operasional</label>
                            <select wire:model="coa_beban_operasional" class="form-control" id="coa_beban_operasional">
                                <option value="">Pilih COA</option>
                                @foreach($akuns as $akun)
                                    @if($akun->tipe == 'beban')
                                        <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('coa_beban_operasional') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coa_beban_pemakaian_barang">COA Biaya Pemakaian Barang</label>
                            <select wire:model="coa_beban_pemakaian_barang" class="form-control" id="coa_beban_pemakaian_barang">
                                <option value="">Pilih COA</option>
                                @foreach($akuns as $akun)
                                    @if($akun->tipe == 'beban')
                                        <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('coa_beban_operasional') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>
</div>