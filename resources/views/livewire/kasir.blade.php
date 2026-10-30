<div class="pos-wrapper">
    {{-- CSS Styles: POS Multi-Card Industrial Standard --}}
    <style>
        .pos-wrapper {
            font-family: var(--font-sans, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif);
            color: var(--text-primary, #1A1D26);
        }

        /* Generic Card Styling */
        .pos-card {
            background: var(--bg-card, #FFFFFF);
            border: 1px solid var(--border-color, #E5E7EB);
            border-radius: var(--radius-lg, 10px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 16px;
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }

        .pos-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .pos-card-header {
            padding: 12px 18px;
            background: var(--bg-body, #F7F9FC);
            border-bottom: 1px solid var(--border-color, #E5E7EB);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pos-card-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-primary, #1A1D26);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pos-card-body {
            padding: 16px;
        }

        /* Status & Clock */
        .pos-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .pos-pulse-dot {
            width: 8px;
            height: 8px;
            background: #10B981;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: posPulse 2s infinite;
        }

        @keyframes posPulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 7px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .pos-clock {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
            background: var(--bg-body, #F7F9FC);
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px solid var(--border-color, #E5E7EB);
            color: var(--text-primary, #1A1D26);
        }

        .pos-tab-nav {
            display: inline-flex;
            background: var(--bg-body, #F7F9FC);
            padding: 3px;
            border-radius: 8px;
            border: 1px solid var(--border-color, #E5E7EB);
        }

        .pos-tab-btn {
            border: none;
            background: transparent;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            color: var(--text-secondary, #5A6070);
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .pos-tab-btn.active {
            background: var(--primary, #0F6CBD);
            color: #FFFFFF;
            box-shadow: 0 2px 4px rgba(15, 108, 189, 0.2);
        }

        /* Scanner Bar */
        .pos-scanner-input {
            height: 44px;
            font-size: 14px;
            font-weight: 500;
            padding-left: 40px;
            border: 2px solid var(--border-color, #E5E7EB);
            border-radius: 8px;
            background: var(--bg-input, #FFFFFF);
            color: var(--text-primary, #1A1D26);
            transition: all 0.2s ease;
        }

        .pos-scanner-input:focus {
            border-color: var(--primary, #0F6CBD);
            box-shadow: 0 0 0 3px rgba(15, 108, 189, 0.15);
            outline: none;
        }

        .pos-scanner-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--text-muted, #8B90A0);
            pointer-events: none;
        }

        .pos-kbd-badge {
            background: var(--bg-body, #F7F9FC);
            border: 1px solid var(--border-color, #E5E7EB);
            color: var(--text-secondary, #5A6070);
            font-size: 10px;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 4px;
        }

        /* Product Catalog Cards Grid */
        .pos-product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 12px;
            max-height: 520px;
            overflow-y: auto;
            padding: 4px 2px;
        }

        .pos-product-card {
            background: var(--bg-card, #FFFFFF);
            border: 1px solid var(--border-color, #E5E7EB);
            border-radius: 10px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
            position: relative;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
            user-select: none;
        }

        .pos-product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.08);
            border-color: var(--primary, #0F6CBD);
        }

        .pos-product-card:active {
            transform: scale(0.98);
        }

        .pos-product-card.in-cart {
            border-color: var(--primary, #0F6CBD);
            background: rgba(15, 108, 189, 0.02);
        }

        .pos-product-avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(15, 108, 189, 0.08);
            color: var(--primary, #0F6CBD);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .pos-product-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary, #1A1D26);
            line-height: 1.3;
            min-height: 34px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
        }

        .pos-product-meta {
            font-size: 11px;
            color: var(--text-muted, #8B90A0);
            margin-bottom: 8px;
        }

        .pos-product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            pt: 6px;
            border-top: 1px dashed var(--border-color, #E5E7EB);
        }

        .pos-product-price {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary, #0F6CBD);
        }

        /* Cart Items Table inside Card */
        .pos-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }

        .pos-table th {
            background: var(--bg-body, #F7F9FC);
            color: var(--text-secondary, #5A6070);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 8px 12px;
            border-bottom: 1px solid var(--border-color, #E5E7EB);
        }

        .pos-table td {
            padding: 10px 12px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color, #E5E7EB);
            color: var(--text-primary, #1A1D26);
        }

        .pos-table tbody tr:hover {
            background: var(--bg-hover, #F2F4F7);
        }

        /* Qty Stepper Controls */
        .pos-qty-stepper {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--border-color, #E5E7EB);
            border-radius: 6px;
            overflow: hidden;
            background: var(--bg-input, #FFFFFF);
        }

        .pos-stepper-btn {
            border: none;
            background: var(--bg-body, #F7F9FC);
            color: var(--text-primary, #1A1D26);
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .pos-stepper-btn:hover {
            background: var(--border-color, #E5E7EB);
        }

        .pos-stepper-input {
            width: 38px;
            height: 26px;
            border: none;
            text-align: center;
            font-weight: 600;
            font-size: 12px;
            background: transparent;
            color: var(--text-primary, #1A1D26);
        }

        .pos-stepper-input:focus {
            outline: none;
        }

        /* Big LED Total Display Card */
        .pos-total-display {
            background: linear-gradient(135deg, #0F6CBD 0%, #004B87 100%);
            color: #FFFFFF;
            padding: 16px 20px;
            text-align: right;
            border-radius: 8px;
        }

        .pos-total-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.85;
            margin-bottom: 2px;
        }

        .pos-total-amount {
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .pos-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
            color: var(--text-secondary, #5A6070);
        }

        /* Quick Cash Chips */
        .pos-quick-cash {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .pos-cash-chip {
            border: 1px solid var(--border-color, #E5E7EB);
            background: var(--bg-body, #F7F9FC);
            color: var(--text-primary, #1A1D26);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .pos-cash-chip:hover {
            border-color: var(--primary, #0F6CBD);
            background: rgba(15, 108, 189, 0.08);
            color: var(--primary, #0F6CBD);
        }

        /* Change Alert Box */
        .pos-change-box {
            padding: 10px 14px;
            border-radius: 8px;
            margin-top: 10px;
            margin-bottom: 12px;
        }

        .pos-change-box.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #065F46;
        }

        .pos-change-box.warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #92400E;
        }

        /* Giant Pay Button */
        .pos-btn-pay {
            width: 100%;
            padding: 12px 18px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .pos-btn-pay.lunas {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: #FFFFFF;
        }

        .pos-btn-pay.lunas:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        .pos-btn-pay.piutang {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: #FFFFFF;
        }

        .pos-btn-pay.piutang:hover {
            background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
        }

        .pos-btn-pay:disabled {
            background: #9CA3AF !important;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Floating Search Results Dropdown */
        .pos-search-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: var(--bg-card, #FFFFFF);
            border: 1px solid var(--border-color, #E5E7EB);
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            z-index: 1050;
            max-height: 340px;
            overflow-y: auto;
        }

        .pos-search-item {
            padding: 8px 14px;
            border-bottom: 1px solid var(--border-color, #E5E7EB);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.15s ease;
        }

        .pos-search-item:last-child {
            border-bottom: none;
        }

        .pos-search-item:hover {
            background: var(--bg-hover, #F2F4F7);
        }

        /* Stat Card in History */
        .pos-stat-card {
            background: var(--bg-card, #FFFFFF);
            border: 1px solid var(--border-color, #E5E7EB);
            border-radius: var(--radius-lg, 8px);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pos-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
    </style>

    {{-- =========================================================================
         CARD 1: SESI KASIR & INFORMASI TRANSAKSI (TOP HEADER CARD)
         ========================================================================= --}}
    <div class="pos-card">
        <div class="pos-card-header py-2">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="pos-status-badge">
                    <span class="pos-pulse-dot"></span>
                    <span>POS KASIR AKTIF</span>
                </div>
                <div class="d-flex align-items-center text-muted text-xs">
                    <i class="ri-user-smile-line mr-1 text-primary"></i>
                    <span>Kasir: <strong class="text-dark">{{ Auth::user()->name }}</strong></span>
                </div>
                <div class="pos-clock" id="posLiveClock">
                    {{ now()->format('H:i:s') }} WIB
                </div>
            </div>

            <div class="pos-tab-nav">
                <button type="button" class="pos-tab-btn {{ $activeTab === 'pos' ? 'active' : '' }}" wire:click="setTab('pos')">
                    <i class="ri-shopping-cart-2-line"></i>
                    <span>Form Kasir POS</span>
                    @if(count($cart) > 0)
                        <span class="badge badge-danger rounded-pill ml-1">{{ count($cart) }}</span>
                    @endif
                </button>
                <button type="button" class="pos-tab-btn {{ $activeTab === 'history' ? 'active' : '' }}" wire:click="setTab('history')">
                    <i class="ri-history-line"></i>
                    <span>Riwayat Penjualan</span>
                </button>
            </div>
        </div>

        <div class="pos-card-body py-3">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                    <label class="form-label text-xs mb-1 font-weight-bold text-muted">
                        <i class="ri-building-line text-primary mr-1"></i> GUDANG TRANSAKSI
                    </label>
                    <select class="form-control form-control-sm font-weight-bold" wire:model.live="gudang_id" {{ $isView ? 'disabled' : '' }}>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                    <label class="form-label text-xs mb-1 font-weight-bold text-muted">
                        <i class="ri-bank-card-line text-success mr-1"></i> AKUN KAS / BANK
                    </label>
                    <select class="form-control form-control-sm font-weight-bold" wire:model="kas_id" {{ $isView ? 'disabled' : '' }}>
                        @foreach($kasAccounts as $k)
                            <option value="{{ $k->id }}">{{ $k->kode_akun }} - {{ $k->nama_akun }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                    <label class="form-label text-xs mb-1 font-weight-bold text-muted">
                        <i class="ri-calendar-line mr-1"></i> TANGGAL TRANSAKSI
                    </label>
                    <input type="date" class="form-control form-control-sm" wire:model="tanggal" {{ $isView ? 'readonly disabled' : '' }}>
                </div>

                <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                    <label class="form-label text-xs mb-1 font-weight-bold text-muted">
                        <i class="ri-sticky-note-line mr-1"></i> CATATAN / KETERANGAN
                    </label>
                    <input type="text" class="form-control form-control-sm" wire:model="keterangan" placeholder="Keterangan opsional..." {{ $isView ? 'readonly disabled' : '' }}>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT BANNERS FOR VIEW / EDIT MODE --}}
    @if($isView && $viewPenjualan)
        <div class="alert alert-info d-flex align-items-center justify-content-between p-3 mb-3 border-0 shadow-sm" style="background:#EBF5FF; border-left: 4px solid #0F6CBD !important;">
            <div class="d-flex align-items-center">
                <i class="ri-information-fill text-primary mr-3" style="font-size: 24px;"></i>
                <div>
                    <h6 class="mb-0 font-weight-bold text-primary">Mode Lihat Penjualan: {{ $viewPenjualan->no_penjualan }}</h6>
                    <small class="text-secondary">
                        Waktu: {{ $viewPenjualan->created_at->format('d/m/Y H:i') }} | Kasir: {{ $viewPenjualan->creator->name ?? '-' }} |
                        Status: <strong class="badge badge-{{ $viewPenjualan->status == 'selesai' ? 'success' : ($viewPenjualan->status == 'draft' ? 'warning' : 'secondary') }}">{{ ucfirst($viewPenjualan->status) }}</strong>
                    </small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.kasir.print', $viewPenjualan->id) }}" target="_blank" class="btn btn-sm btn-primary">
                    <i class="ri-printer-line mr-1"></i> Cetak Struk
                </a>
                <button type="button" class="btn btn-sm btn-secondary" wire:click="resetKasir">
                    <i class="ri-arrow-go-back-line mr-1"></i> Kembali ke Kasir Baru
                </button>
            </div>
        </div>
    @elseif($isEdit)
        <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 mb-3 border-0 shadow-sm" style="background:#FEF3C7; border-left: 4px solid #F59E0B !important;">
            <div class="d-flex align-items-center">
                <i class="ri-edit-2-fill text-warning mr-3" style="font-size: 24px;"></i>
                <div>
                    <h6 class="mb-0 font-weight-bold text-dark">Mode Edit Penjualan (ID: {{ $editPenjualanId }})</h6>
                    <small class="text-muted">Anda sedang mengubah data transaksi lama. Simpan setelah selesai mengubah item.</small>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" wire:click="clearCart">
                <i class="ri-close-line mr-1"></i> Batal Edit
            </button>
        </div>
    @endif

    {{-- =========================================================================
         TAB 1: FORM KASIR (CARD WORKSTATION)
         ========================================================================= --}}
    @if($activeTab === 'pos')
    <div class="row">
        {{-- ==================== KOLOM KIRI (KATALOG & SCANNER) ==================== --}}
        <div class="col-xl-7 col-lg-6 mb-3">
            @if(!$isView)
            {{-- CARD 2: SCANNER BARCODE & PENCARIAN CEPAT --}}
            <div class="pos-card mb-3">
                <div class="pos-card-header py-2">
                    <h6 class="pos-card-title">
                        <i class="ri-barcode-box-line text-primary"></i> Scanner & Pencarian Cepat
                    </h6>
                    <span class="pos-kbd-badge">F2 Fokus</span>
                </div>
                <div class="pos-card-body p-3">
                    <div class="row">
                        {{-- Barcode Scanner Input --}}
                        <div class="col-md-7 mb-2 mb-md-0 position-relative">
                            <i class="ri-barcode-line pos-scanner-icon"></i>
                            <input type="text"
                                   id="posBarcodeInput"
                                   class="pos-scanner-input w-100"
                                   placeholder="Scan barcode / ketik kode (Enter)..."
                                   wire:model.defer="barcode"
                                   wire:keydown.enter.prevent="scanBarcode"
                                   autocomplete="off"
                                   autofocus>
                        </div>

                        {{-- Live Filter Name / Code --}}
                        <div class="col-md-5 position-relative">
                            <div class="input-group">
                                <input type="text"
                                       id="posSearchManualInput"
                                       class="form-control"
                                       style="height: 44px; border-radius: 8px;"
                                       placeholder="Filter katalog / nama..."
                                       wire:model.live.debounce.250ms="barcodeSearch"
                                       autocomplete="off">
                                @if(!empty($barcodeSearch))
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" wire:click="$set('barcodeSearch', '')">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Quick Shortcuts Bar --}}
                    <div class="d-flex align-items-center flex-wrap gap-2 mt-2 pt-2 border-top text-xs text-muted">
                        <span><span class="pos-kbd-badge mr-1">F2</span> Scan</span>
                        <span class="text-muted">•</span>
                        <span><span class="pos-kbd-badge mr-1">F8</span> Bayar</span>
                        <span class="text-muted">•</span>
                        <span><span class="pos-kbd-badge mr-1">F9</span> Selesai</span>
                        <span class="text-muted">•</span>
                        <span><span class="pos-kbd-badge mr-1">F4</span> Baru</span>
                    </div>
                </div>
            </div>

            {{-- CARD 3: KATALOG KARTU PRODUK (PRODUCT CARDS GRID) --}}
            <div class="pos-card mb-3">
                <div class="pos-card-header py-2">
                    <h6 class="pos-card-title">
                        <i class="ri-grid-fill text-primary"></i> Katalog Produk
                    </h6>
                    <span class="badge badge-light border text-muted">
                        {{ count($catalogProducts) }} Produk Siap Jual
                    </span>
                </div>
                <div class="pos-card-body p-3">
                    @if(count($catalogProducts) == 0)
                        <div class="text-center py-4 text-muted">
                            <i class="ri-inbox-line text-lg d-block mb-1"></i>
                            Tidak ada produk yang cocok dengan pencarian Anda.
                        </div>
                    @else
                        <div class="pos-product-grid">
                            @foreach($catalogProducts as $prod)
                                <div class="pos-product-card {{ ($prod['in_cart'] ?? 0) > 0 ? 'in-cart' : '' }}"
                                     wire:key="pos-product-card-{{ $prod['id'] }}"
                                     wire:click="addToCart({{ $prod['id'] }})"
                                     title="Klik untuk menambahkan ke keranjang">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="pos-product-avatar">
                                            <i class="ri-box-3-line"></i>
                                        </div>
                                        @if(($prod['in_cart'] ?? 0) > 0)
                                            <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                                <i class="ri-shopping-cart-line mr-1"></i>{{ $prod['in_cart'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="pos-product-name" title="{{ $prod['nama'] }}">
                                        {{ $prod['nama'] }}
                                    </div>

                                    <div class="pos-product-meta">
                                        <span>{{ $prod['kode'] }}</span>
                                        @if($prod['barcode'])
                                            <span>• {{ $prod['barcode'] }}</span>
                                        @endif
                                    </div>

                                    <div class="pos-product-footer pt-2">
                                        <div class="pos-product-price">
                                            Rp {{ number_format($prod['harga'], 0, ',', '.') }}
                                        </div>
                                        <span class="badge badge-{{ $prod['stok'] > 0 ? 'success' : 'danger' }}" style="font-size: 10px;">
                                            {{ $prod['stok'] > 0 ? 'Stok: ' . number_format($prod['stok'], 0, ',', '.') : 'Habis' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- ==================== KOLOM KANAN (KERANJANG & CHECKOUT) ==================== --}}
        <div class="col-xl-5 col-lg-6 mb-3">
            {{-- CARD 4: KERANJANG BELANJA (CART CARD) --}}
            <div class="pos-card mb-3">
                <div class="pos-card-header py-2">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="pos-card-title">
                            <i class="ri-shopping-basket-line text-primary"></i> Keranjang Belanja
                        </h6>
                        <span class="badge badge-info ml-1" style="font-size: 11px;">
                            {{ count($cart) }} Item ({{ collect($cart)->sum('qty') }} pcs)
                        </span>
                    </div>
                    @if(!$isView && count($cart) > 0)
                        <button type="button" class="btn btn-xs btn-outline-danger"
                                wire:click="clearCart"
                                onclick="return confirm('Kosongkan semua item dari keranjang?')">
                            <i class="ri-delete-bin-line mr-1"></i> Kosongkan
                        </button>
                    @endif
                </div>

                <div class="pos-card-body p-0">
                    @if(count($cart) == 0)
                        <div class="text-center py-5 px-3">
                            <div class="mb-2 text-muted" style="font-size: 40px; opacity: 0.35;">
                                <i class="ri-shopping-cart-2-line"></i>
                            </div>
                            <h6 class="font-weight-bold text-secondary mb-1" style="font-size: 13px;">Keranjang Masih Kosong</h6>
                            <p class="text-muted text-xs mb-3">Klik kartu produk di samping atau scan barcode untuk menambahkan item.</p>
                            @if(!$isView)
                                <button type="button" class="btn btn-xs btn-primary" onclick="document.getElementById('posBarcodeInput').focus()">
                                    <i class="ri-barcode-line mr-1"></i> Scan Barcode (F2)
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                            <table class="pos-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;" class="text-center">#</th>
                                        <th>Produk</th>
                                        <th class="text-right" style="width: 90px;">Harga</th>
                                        <th class="text-center" style="width: 110px;">Qty</th>
                                        <th class="text-right" style="width: 100px;">Subtotal</th>
                                        @if(!$isView)
                                            <th style="width: 35px;" class="text-center"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $key => $item)
                                        <tr wire:key="cart-row-{{ $item['barang_id'] }}-{{ $key }}">
                                            <td class="text-center text-muted font-weight-bold" style="font-size:11px;">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark text-xs mb-0">{{ $item['nama'] }}</div>
                                                <div class="text-muted" style="font-size: 10px;">
                                                    {{ $item['kode'] }}
                                                    @if($item['barcode']) • {{ $item['barcode'] }} @endif
                                                    <span class="text-info ml-1">(Stok: {{ $item['stok'] }})</span>
                                                </div>
                                            </td>
                                            <td class="text-right text-xs">
                                                Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                @if($isView)
                                                    <span class="font-weight-bold">{{ $item['qty'] }}</span>
                                                @else
                                                    <div class="pos-qty-stepper">
                                                        <button type="button" class="pos-stepper-btn" wire:click="decrementQty({{ $key }})" title="Kurang">
                                                            <i class="ri-subtract-line"></i>
                                                        </button>
                                                        <input type="number"
                                                               class="pos-stepper-input"
                                                               min="1"
                                                               max="{{ $item['stok'] }}"
                                                               value="{{ $item['qty'] }}"
                                                               wire:change="updateQty({{ $key }}, $event.target.value)">
                                                        <button type="button" class="pos-stepper-btn" wire:click="incrementQty({{ $key }})" title="Tambah">
                                                            <i class="ri-add-line"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-right font-weight-bold text-primary text-xs">
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            </td>
                                            @if(!$isView)
                                                <td class="text-center">
                                                    <button type="button"
                                                            class="btn btn-sm btn-icon btn-outline-danger"
                                                            style="width: 24px; height: 24px; padding: 0;"
                                                            wire:click="removeItem({{ $key }})"
                                                            title="Hapus">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CARD 5: RINCIAN TAGIHAN & TOTAL (BILLING SUMMARY CARD) --}}
            <div class="pos-card mb-3">
                <div class="pos-card-body p-3">
                    {{-- Big LED Total Display --}}
                    <div class="pos-total-display mb-3">
                        <div class="pos-total-label">TOTAL TAGIHAN</div>
                        <div class="pos-total-amount">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Subtotal & Discounts --}}
                    <div class="pos-summary-row">
                        <span>Subtotal Item</span>
                        <strong class="text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>

                    {{-- Diskon Global (%) --}}
                    <div class="pos-summary-row align-items-center">
                        <span>Diskon Transaksi (%)</span>
                        <div style="width: 90px;">
                            <input type="number"
                                   min="0"
                                   max="100"
                                   step="0.1"
                                   class="form-control form-control-sm text-right font-weight-bold"
                                   wire:model.lazy="diskon"
                                   placeholder="0"
                                   {{ $isView ? 'readonly disabled' : '' }}>
                        </div>
                    </div>
                    @if($diskon > 0)
                        <div class="pos-summary-row text-danger text-xs pt-0">
                            <span>Potongan Diskon ({{ $diskon }}%)</span>
                            <span>- Rp {{ number_format($subtotal * $diskon / 100, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    {{-- PPN Tax --}}
                    <div class="pos-summary-row align-items-center">
                        <span>Pajak PPN</span>
                        <div style="width: 130px;">
                            <select class="form-control form-control-sm" wire:model="ppn_id" {{ $isView ? 'disabled' : '' }}>
                                <option value="">Tanpa PPN</option>
                                @foreach($ppnMasters as $p)
                                    <option value="{{ $p->id }}">{{ $p->kode_ppn ?? 'PPN' }} ({{ $p->rate }}%)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if($ppn_rate > 0)
                        <div class="pos-summary-row text-primary text-xs pt-0">
                            <span>PPN Ditambahkan ({{ $ppn_rate }}%)</span>
                            <span>+ Rp {{ number_format($ppn_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CARD 6: PEMBAYARAN & SELESAIKAN (PAYMENT TENDER CARD) --}}
            <div class="pos-card mb-3">
                <div class="pos-card-header py-2">
                    <h6 class="pos-card-title">
                        <i class="ri-wallet-3-line text-success"></i> Pembayaran & Kasir
                    </h6>
                    <span class="pos-kbd-badge">F8</span>
                </div>
                <div class="pos-card-body p-3">
                    {{-- Uang Bayar / Cash Input --}}
                    <div class="form-group mb-2">
                        <label class="form-label text-xs font-weight-bold text-muted mb-1 d-flex justify-content-between">
                            <span>UANG BAYAR (CASH / BANK)</span>
                            <span class="pos-kbd-badge">F8</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold bg-light">Rp</span>
                            </div>
                            <input type="number"
                                   id="posBayarInput"
                                   min="0"
                                   step="500"
                                   class="form-control text-right font-weight-bold"
                                   style="font-size: 16px; height: 42px;"
                                   wire:model.lazy="bayar"
                                   placeholder="0"
                                   {{ $isView ? 'readonly disabled' : '' }}>
                        </div>

                        {{-- Quick Cash Chips --}}
                        @if(!$isView && count($quickCashOptions) > 0)
                            <div class="pos-quick-cash">
                                @foreach($quickCashOptions as $option)
                                    <button type="button"
                                            class="pos-cash-chip {{ $bayar == $option ? 'border-primary text-primary' : '' }}"
                                            wire:key="quick-cash-chip-{{ $option }}"
                                            wire:click="setBayar({{ $option }})">
                                        @if($option == (int) ceil($total))
                                            <i class="ri-check-line mr-1"></i> Uang Pas
                                        @else
                                            Rp {{ number_format($option, 0, ',', '.') }}
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Kembalian / Piutang Alert Box --}}
                    @if($total > 0 && $bayar >= $total)
                        <div class="pos-change-box success">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold text-xs"><i class="ri-check-line mr-1"></i> KEMBALIAN:</span>
                                <strong class="text-md">Rp {{ number_format($kembalian, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    @elseif($total > 0 && $bayar < $total)
                        <div class="pos-change-box warning">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold text-xs"><i class="ri-error-warning-line mr-1"></i> KURANG BAYAR:</span>
                                <strong>Rp {{ number_format($total - $bayar, 0, ',', '.') }}</strong>
                            </div>
                            <small class="d-block mt-1" style="font-size: 10px;">
                                Dicatat sebagai <strong>Piutang / Draft</strong> dan dapat dilunasi nanti.
                            </small>
                        </div>
                    @endif

                    {{-- Action Execution Buttons --}}
                    @if(!$isView)
                        @if($isEdit)
                            <button type="button"
                                    class="pos-btn-pay lunas mt-2"
                                    wire:click="updatePenjualan"
                                    wire:loading.attr="disabled"
                                    wire:target="updatePenjualan"
                                    {{ count($cart) == 0 ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="updatePenjualan">
                                    <i class="ri-save-line"></i> SIMPAN PERUBAHAN TRANSAKSI
                                </span>
                                <span wire:loading wire:target="updatePenjualan">
                                    <i class="ri-loader-4-line ri-spin"></i> Menyimpan Perubahan...
                                </span>
                            </button>
                            <button type="button" class="btn btn-secondary btn-block mt-2 btn-sm" wire:click="clearCart">
                                <i class="ri-close-line mr-1"></i> Batal Edit
                            </button>
                        @else
                            <button type="button"
                                    id="posBtnSubmit"
                                    class="pos-btn-pay {{ $bayar >= $total && $total > 0 ? 'lunas' : 'piutang' }} mt-2"
                                    wire:click="simpanPenjualan"
                                    wire:loading.attr="disabled"
                                    wire:target="simpanPenjualan"
                                    {{ count($cart) == 0 ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="simpanPenjualan">
                                    @if($bayar >= $total && $total > 0)
                                        <i class="ri-cash-line"></i> BAYAR & SELESAIKAN (F9)
                                    @elseif($total > 0 && $bayar < $total)
                                        <i class="ri-file-shield-line"></i> SIMPAN (BELUM LUNAS / PIUTANG)
                                    @else
                                        <i class="ri-shopping-cart-2-line"></i> PILIH PRODUK TERLEBIH DAHULU
                                    @endif
                                </span>
                                <span wire:loading wire:target="simpanPenjualan">
                                    <i class="ri-loader-4-line ri-spin"></i> Memproses Transaksi...
                                </span>
                            </button>

                            <button type="button" class="btn btn-secondary btn-block mt-2 btn-sm" wire:click="resetKasir">
                                <i class="ri-refresh-line mr-1"></i> Transaksi Baru / Reset (F4)
                            </button>
                        @endif
                    @else
                        {{-- Mode View Actions --}}
                        @if($viewPenjualan && $viewPenjualan->status != 'selesai' && $viewPenjualan->bayar < $viewPenjualan->total)
                            <button type="button" class="pos-btn-pay lunas mt-2" wire:click="openBayarModal({{ $viewPenjualan->id }})">
                                <i class="ri-hand-coin-line mr-1"></i> BAYAR PELUNASAN SEKARANG
                            </button>
                        @endif
                        <a href="{{ route('admin.kasir.print', $viewPenjualan->id) }}" target="_blank" class="btn btn-primary btn-block mt-2">
                            <i class="ri-printer-line mr-1"></i> Cetak Struk / Billing
                        </a>
                        <button type="button" class="btn btn-secondary btn-block mt-2 btn-sm" wire:click="resetKasir">
                            <i class="ri-arrow-go-back-line mr-1"></i> Keluar dari Mode Lihat
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- =========================================================================
         TAB 2: RIWAYAT TRANSAKSI & DAFTAR PENJUALAN
         ========================================================================= --}}
    @if($activeTab === 'history')
    <div>
        {{-- SUMMARY STATS CARDS --}}
        <div class="row mb-3">
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="pos-stat-card">
                    <div class="pos-stat-icon bg-primary text-white">
                        <i class="ri-shopping-bag-3-line"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-weight-bold">TRANSAKSI HARI INI</div>
                        <h5 class="mb-0 font-weight-bold">{{ number_format($todayStats['count'] ?? 0) }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-2">
                <div class="pos-stat-card">
                    <div class="pos-stat-icon bg-success text-white">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-weight-bold">OMSET HARI INI</div>
                        <h5 class="mb-0 font-weight-bold text-success">
                            Rp {{ number_format($todayStats['total_omzet'] ?? 0, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-2">
                <div class="pos-stat-card">
                    <div class="pos-stat-icon bg-info text-white">
                        <i class="ri-checkbox-circle-line"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-weight-bold">LUNAS / SELESAI</div>
                        <h5 class="mb-0 font-weight-bold text-info">{{ number_format($todayStats['lunas_count'] ?? 0) }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-2">
                <div class="pos-stat-card">
                    <div class="pos-stat-icon bg-warning text-dark">
                        <i class="ri-time-line"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-weight-bold">PENDING / PIUTANG</div>
                        <h5 class="mb-0 font-weight-bold text-warning">{{ number_format($todayStats['pending_count'] ?? 0) }}</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER CARD --}}
        <div class="pos-card mb-3">
            <div class="pos-card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <label class="form-label text-xs mb-1 font-weight-bold text-muted">Cari No. Penjualan</label>
                        <div class="input-group input-group-sm">
                            <input wire:model.defer="search" wire:keydown.enter="$refresh" type="text" class="form-control" placeholder="Cari nomor nota...">
                            <div class="input-group-append">
                                <button wire:click="$refresh" class="btn btn-primary" type="button">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 mb-2 mb-md-0">
                        <label class="form-label text-xs mb-1 font-weight-bold text-muted">Status</label>
                        <select class="form-control form-control-sm" wire:model.live="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="selesai">Selesai (Lunas)</option>
                            <option value="draft">Draft (Piutang)</option>
                            <option value="retur">Retur</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-2 mb-md-0">
                        <label class="form-label text-xs mb-1 font-weight-bold text-muted">Tanggal Mulai</label>
                        <input type="date" class="form-control form-control-sm" wire:model="startDate">
                    </div>

                    <div class="col-md-3 mb-2 mb-md-0">
                        <label class="form-label text-xs mb-1 font-weight-bold text-muted">Tanggal Sampai</label>
                        <input type="date" class="form-control form-control-sm" wire:model="endDate">
                    </div>

                    <div class="col-md-1 mb-2 mb-md-0 text-right">
                        <label class="form-label text-xs mb-1 font-weight-bold text-muted d-block">&nbsp;</label>
                        <button wire:click="$refresh" class="btn btn-sm btn-info btn-block" title="Terapkan Filter">
                            <i class="ri-filter-3-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE DAFTAR PENJUALAN CARD --}}
        <div class="pos-card">
            <div class="pos-card-header py-2 d-flex justify-content-between align-items-center">
                <h6 class="pos-card-title">
                    <i class="ri-file-list-3-line text-primary"></i> Data Riwayat Penjualan
                </h6>
            </div>
            <div class="pos-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No Penjualan</th>
                                <th>Waktu</th>
                                <th>Gudang</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Bayar</th>
                                <th class="text-right">Kembalian</th>
                                <th class="text-center">Status</th>
                                <th>Kasir</th>
                                <th class="text-center" style="width: 170px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penjualans as $p)
                                <tr wire:key="penjualan-row-{{ $p->id }}">
                                    <td>
                                        <strong class="text-primary">{{ $p->no_penjualan }}</strong>
                                        @if($p->details->count() > 0)
                                            <div class="text-xs text-muted">{{ $p->details->count() }} item barang</div>
                                        @endif
                                    </td>
                                    <td class="text-xs">
                                        {{ $p->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                    </td>
                                    <td>{{ $p->gudang->nama_gudang ?? '-' }}</td>
                                    <td class="text-right font-weight-bold">
                                        Rp {{ number_format($p->total, 0, ',', '.') }}
                                    </td>
                                    <td class="text-right">
                                        Rp {{ number_format($p->bayar, 0, ',', '.') }}
                                    </td>
                                    <td class="text-right text-success">
                                        Rp {{ number_format($p->kembalian, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($p->status == 'selesai')
                                            <span class="badge badge-success px-2 py-1"><i class="ri-check-line mr-1"></i> Lunas</span>
                                        @elseif($p->status == 'draft' || $p->status == 'kunci')
                                            <span class="badge badge-warning px-2 py-1"><i class="ri-time-line mr-1"></i> Piutang</span>
                                        @elseif($p->status == 'retur')
                                            <span class="badge badge-danger px-2 py-1"><i class="ri-arrow-go-back-line mr-1"></i> Retur</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">{{ ucfirst($p->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-xs">{{ $p->creator->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            {{-- View Details --}}
                                            <a href="{{ route('admin.kasir.index', ['view' => $p->id]) }}" class="btn btn-outline-secondary" title="Lihat Transaksi">
                                                <i class="ri-eye-line"></i>
                                            </a>

                                            {{-- Pelunasan Modal if Belum Lunas --}}
                                            @if($p->status == 'draft' && $p->bayar < $p->total)
                                                <button type="button" class="btn btn-outline-success" wire:click="openBayarModal({{ $p->id }})" title="Bayar Pelunasan">
                                                    <i class="ri-money-dollar-box-line"></i>
                                                </button>
                                            @endif

                                            {{-- Edit if draft --}}
                                            @if($p->status == 'draft' || $p->status == 'kunci')
                                                <a href="{{ route('admin.kasir.index', ['edit' => $p->id]) }}" class="btn btn-outline-warning" title="Edit Transaksi">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                            @endif

                                            {{-- Print Thermal Receipt --}}
                                            <a href="{{ route('admin.kasir.print', $p->id) }}" target="_blank" class="btn btn-outline-primary" title="Cetak Struk">
                                                <i class="ri-printer-line"></i>
                                            </a>

                                            {{-- Retur if not retur and not selesai --}}
                                            @if($p->status == 'draft' || $p->status == 'kunci')
                                                <button type="button" class="btn btn-outline-danger"
                                                        wire:click="returPenjualan({{ $p->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="returPenjualan({{ $p->id }})"
                                                        onclick="return confirm('Retur penjualan {{ $p->no_penjualan }}? Stok barang akan dikembalikan ke gudang.')"
                                                        title="Retur Penjualan">
                                                    <i class="ri-arrow-go-back-line"></i>
                                                </button>
                                            @endif

                                            {{-- History Retur --}}
                                            <button type="button" class="btn btn-outline-secondary" wire:click="viewReturnHistory({{ $p->id }})" title="Histori Retur">
                                                <i class="ri-history-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="ri-inbox-line d-block text-lg mb-1"></i>
                                        Tidak ada data transaksi penjualan pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-2">
                {{ $penjualans->links() }}
            </div>
        </div>
    </div>
    @endif

    {{-- =========================================================================
         MODAL 1: TRANSAKSI BERHASIL & STRUK KASIR (POST-CHECKOUT SUCCESS MODAL)
         ========================================================================= --}}
    @if($showSuccessModal && $lastSale)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.65); z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-body text-center p-4">
                    {{-- Animated Success Checkmark --}}
                    <div class="mb-3">
                        <div style="width: 68px; height: 68px; background: rgba(16, 185, 129, 0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="ri-check-line text-success" style="font-size: 38px;"></i>
                        </div>
                    </div>

                    <h5 class="font-weight-bold mb-1">Transaksi Berhasil Disimpan!</h5>
                    <p class="text-muted text-xs mb-3">No. Nota: <strong class="text-dark">{{ $lastSale->no_penjualan }}</strong></p>

                    {{-- Struk Brief Summary --}}
                    <div class="bg-light p-3 rounded text-left mb-3 border">
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span class="text-muted">Kasir:</span>
                            <span class="font-weight-bold">{{ $lastSale->creator->name ?? Auth::user()->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span class="text-muted">Gudang:</span>
                            <span>{{ $lastSale->gudang->nama_gudang ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span class="text-muted">Item Terjual:</span>
                            <span>{{ $lastSale->details->count() }} macam ({{ $lastSale->details->sum('qty') }} pcs)</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between font-weight-bold mb-1">
                            <span>TOTAL:</span>
                            <span class="text-primary">Rp {{ number_format($lastSale->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span>Bayar:</span>
                            <span>Rp {{ number_format($lastSale->bayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-xs font-weight-bold text-success">
                            <span>Kembalian:</span>
                            <span>Rp {{ number_format($lastSale->kembalian, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.kasir.print', $lastSale->id) }}"
                           target="_blank"
                           class="btn btn-primary btn-block btn-lg font-weight-bold mb-2">
                            <i class="ri-printer-line mr-1"></i> CETAK STRUK THERMAL (80mm)
                        </a>

                        <button type="button" class="btn btn-secondary btn-block" wire:click="closeSuccessModal">
                            <i class="ri-add-line mr-1"></i> Transaksi Baru (F4)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- =========================================================================
         MODAL 2: PELUNASAN PIUTANG / BAYAR PENJUALAN
         ========================================================================= --}}
    @if($showModalBayar && $selectedBayarSale)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.65); z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title font-weight-bold text-sm">
                        <i class="ri-money-dollar-box-line mr-1"></i> Pelunasan Piutang: {{ $selectedBayarSale->no_penjualan }}
                    </h5>
                    <button type="button" class="close text-white" wire:click="closeBayarModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-4">
                            <small class="text-muted d-block text-xs">TOTAL TAGIHAN</small>
                            <strong class="text-dark">Rp {{ number_format($selectedBayarSale->total, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block text-xs">SUDAH DIBAYAR</small>
                            <strong class="text-success">Rp {{ number_format($selectedBayarSale->bayar, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block text-xs">SISA PIUTANG</small>
                            <strong class="text-danger">Rp {{ number_format($selectedBayarSale->total - $selectedBayarSale->bayar, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label text-xs font-weight-bold">JUMLAH PEMBAYARAN SEKARANG</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold">Rp</span>
                            </div>
                            <input type="number"
                                   class="form-control form-control-lg font-weight-bold"
                                   wire:model="jumlahBayar"
                                   min="1"
                                   max="{{ $selectedBayarSale->total - $selectedBayarSale->bayar }}"
                                   placeholder="Nominal pembayaran...">
                        </div>
                        <small class="text-muted d-block mt-1">
                            Bila pembayaran melunasi sisa tagihan, status transaksi otomatis menjadi <strong>Lunas / Selesai</strong>.
                        </small>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeBayarModal">Batal</button>
                    <button type="button"
                            class="btn btn-success font-weight-bold"
                            wire:click="prosesBayarModal"
                            wire:loading.attr="disabled"
                            wire:target="prosesBayarModal">
                        <span wire:loading.remove wire:target="prosesBayarModal">
                            <i class="ri-check-line mr-1"></i> Proses Pembayaran
                        </span>
                        <span wire:loading wire:target="prosesBayarModal">
                            <i class="ri-loader-4-line ri-spin"></i> Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- =========================================================================
         MODAL 3: HISTORI RETUR PENJUALAN
         ========================================================================= --}}
    @if($showReturnHistoryModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.65); z-index: 1060;">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header py-3 bg-light">
                    <h5 class="modal-title font-weight-bold text-sm">
                        <i class="ri-history-line mr-1 text-primary"></i> Histori Retur: {{ $selectedReturnSale->no_penjualan ?? '-' }}
                    </h5>
                    <button type="button" class="close" wire:click="closeReturnHistory" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Barang</th>
                                    <th>Gudang</th>
                                    <th class="text-right">Qty Masuk (Retur)</th>
                                    <th class="text-right">Stok Akhir</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returnHistory as $history)
                                    <tr wire:key="return-history-{{ $history->id }}">
                                        <td>{{ $history->tanggal->format('d/m/Y') }}</td>
                                        <td>{{ $history->barang->nama_barang ?? '-' }}</td>
                                        <td>{{ $history->gudang->nama_gudang ?? '-' }}</td>
                                        <td class="text-right font-weight-bold text-success">{{ number_format($history->qty_masuk, 0, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($history->stok_akhir, 0, ',', '.') }}</td>
                                        <td>{{ $history->keterangan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat retur untuk transaksi ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" wire:click="closeReturnHistory">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- SCRIPT: KEYBOARD SHORTCUTS, WEB AUDIO POS FEEDBACK, & REALTIME CLOCK --}}
<script>
    (function () {
        // POS Audio Feedback Generator (Web Audio API - Zero External Assets)
        const audioCtx = (window.AudioContext || window.webkitAudioContext) ? new (window.AudioContext || window.webkitAudioContext)() : null;

        function playTone(freq, type, duration, delay = 0) {
            if (!audioCtx) return;
            try {
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = type;
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime + delay);
                gain.gain.setValueAtTime(0.12, audioCtx.currentTime + delay);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + delay + duration);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(audioCtx.currentTime + delay);
                osc.stop(audioCtx.currentTime + delay + duration);
            } catch (e) {
                // Audio context not allowed or failed
            }
        }

        // POS Sound Effects
        window.posBeepAdd = function () {
            playTone(880, 'sine', 0.08); // High crisp beep on scan
        };

        window.posBeepSuccess = function () {
            playTone(523.25, 'sine', 0.12, 0);     // C5
            playTone(659.25, 'sine', 0.12, 0.1);   // E5
            playTone(783.99, 'sine', 0.2, 0.2);    // G5
        };

        window.posBeepError = function () {
            playTone(220, 'square', 0.18); // Low alert buzz
        };

        // Realtime Clock
        function updatePosClock() {
            const clockEl = document.getElementById('posLiveClock');
            if (clockEl) {
                const now = new Date();
                const h = String(now.getHours()).padStart(2, '0');
                const m = String(now.getMinutes()).padStart(2, '0');
                const s = String(now.getSeconds()).padStart(2, '0');
                clockEl.textContent = `${h}:${m}:${s} WIB`;
            }
        }
        setInterval(updatePosClock, 1000);
        updatePosClock();

        // Global POS Keyboard Shortcuts
        document.addEventListener('keydown', function (e) {
            // F2: Focus scanner
            if (e.key === 'F2') {
                e.preventDefault();
                const scanner = document.getElementById('posBarcodeInput');
                if (scanner) {
                    scanner.focus();
                    scanner.select();
                }
            }
            // F8: Focus nominal bayar
            else if (e.key === 'F8') {
                e.preventDefault();
                const bayar = document.getElementById('posBayarInput');
                if (bayar) {
                    bayar.focus();
                    bayar.select();
                }
            }
            // F9: Trigger Bayar / Simpan Transaksi
            else if (e.key === 'F9') {
                e.preventDefault();
                const submitBtn = document.getElementById('posBtnSubmit');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.click();
                }
            }
            // F4: Transaksi Baru / Clear
            else if (e.key === 'F4') {
                e.preventDefault();
                if (window.Livewire) {
                    const comp = document.querySelector('.pos-wrapper');
                    if (comp) {
                        Livewire.find(comp.closest('[wire\\:id]').getAttribute('wire:id')).call('resetKasir');
                    }
                }
            }
        });
    })();

    document.addEventListener('livewire:init', () => {
        Livewire.on('focus-barcode', () => {
            setTimeout(() => {
                const el = document.getElementById('posBarcodeInput');
                if (el) {
                    el.focus();
                }
            }, 80);
        });

        Livewire.on('pos-beep-add', () => {
            if (window.posBeepAdd) window.posBeepAdd();
        });

        Livewire.on('pos-beep-success', () => {
            if (window.posBeepSuccess) window.posBeepSuccess();
        });

        Livewire.on('pos-beep-error', () => {
            if (window.posBeepError) window.posBeepError();
        });
    });
</script>
