<div class="pos-wrapper">
    <style>
        :root {
            --primary: #0F6CBD;
            --primary-dark: #004B87;
            --success: #10B981;
            --success-dark: #059669;
            --danger: #F59E0B;
            --danger-dark: #D97706;
            --text-primary: #1A1D26;
            --text-secondary: #5A6070;
            --text-muted: #8B90A0;
            --bg-card: #FFFFFF;
            --bg-body: #F7F9FC;
            --bg-input: #FFFFFF;
            --bg-hover: #F2F4F7;
            --border-color: #E5E7EB;
            --radius-lg: 10px;
        }

        /* ===== Wrapper & Layout ===== */
        .pos-wrapper {
            font-family: var(--font-sans, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif);
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .pos-main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .pos-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 10px;
            overflow-y: auto;
            min-width: 0;
            background: var(--bg-body);
        }

        .pos-right {
            width: 320px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            background: var(--bg-card);
            border-left: 1px solid var(--border-color);
            overflow-y: auto;
            padding: 10px;
            gap: 8px;
        }

        /* ===== Brand ===== */
        .pos-brand {
            background: var(--primary);
            color: #FFFFFF;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pos-brand-icon {
            font-size: 20px;
            font-weight: 800;
            width: 28px;
            height: 28px;
            background: rgba(255,255,255,0.2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pos-brand-text { font-size: 14px; font-weight: 800; letter-spacing: 1px; }
        .pos-brand-sub { font-size: 9px; font-weight: 600; opacity: 0.8; letter-spacing: 2px; }

        /* ===== Card ===== */
        .pos-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 8px;
            overflow: hidden;
        }

        .pos-card-header {
            padding: 6px 10px;
            background: var(--bg-body);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pos-card-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pos-card-body { padding: 8px; }
        .pos-card-body.p-0 { padding: 0 !important; }

        /* ===== Buttons ===== */
        .pos-btn {
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            color: #FFFFFF;
        }
        .pos-btn:focus { outline: none; box-shadow: 0 0 0 3px rgba(15, 108, 189, 0.15); }

        .pos-btn-primary { background: var(--primary); }
        .pos-btn-primary:hover { background: var(--primary-dark); }
        .pos-btn-success { background: var(--success); }
        .pos-btn-success:hover { background: var(--success-dark); }
        .pos-btn-secondary { background: var(--bg-body); color: var(--text-primary); border: 1px solid var(--border-color); }
        .pos-btn-secondary:hover { background: var(--bg-hover); }
        .pos-btn-danger { background: var(--danger); color: #FFFFFF; }
        .pos-btn-danger:hover { background: var(--danger-dark); }

        .pos-btn-small {
            padding: 4px 8px;
            font-size: 10px;
            font-weight: 700;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 2px;
        }
        .pos-btn-small:disabled { background: #9CA3AF !important; cursor: not-allowed; }

        .pos-btn-clear { background: linear-gradient(135deg, #FBC4AB 0%, #F4A488 100%); color: #FFFFFF; }
        .pos-btn-clear:hover { background: linear-gradient(135deg, #F4A488 0%, #E88768 100%); }

        .pos-btn-print { background: linear-gradient(135deg, #A7D9F5 0%, #7FB3D9 100%); color: #FFFFFF; }
        .pos-btn-print:hover { background: linear-gradient(135deg, #7FB3D9 0%, #5A97B8 100%); }

        .pos-actions {
            display: flex;
            gap: 4px;
            padding-top: 6px;
            border-top: 1px solid var(--border-color);
        }

        .pos-btn-charge {
            flex: 1;
            padding: 6px 8px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #FFFFFF;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(15, 108, 189, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
        }
        .pos-btn-charge:hover:not(:disabled) {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #003a6b 100%);
            box-shadow: 0 6px 16px rgba(15, 108, 189, 0.4);
            transform: translateY(-1px);
        }
        .pos-btn-charge:disabled { background: #9CA3AF !important; cursor: not-allowed; box-shadow: none; transform: none; }

        /* ===== Input ===== */
        .pos-input {
            width: 100%;
            box-sizing: border-box;
            padding: 6px 8px;
            font-size: 13px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            background: var(--bg-input);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }
        .pos-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(15, 108, 189, 0.15); outline: none; }

        /* ===== Cashier / Clock ===== */
        .pos-cashier {
            display: flex;
            align-items: center;
            gap: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid var(--border-color);
        }
        .pos-cashier-name { font-size: 11px; font-weight: 700; color: var(--text-primary); }
        .pos-cashier-role { font-size: 9px; color: var(--text-muted); }
        .pos-clock { font-size: 10px; color: var(--text-muted); font-family: 'Courier New', monospace; }

        /* ===== Total & Summary ===== */
        .pos-total {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #FFFFFF;
            padding: 8px 10px;
            border-radius: 6px;
            text-align: center;
        }
        .pos-total-label { font-size: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; opacity: 0.85; margin-bottom: 1px; }
        .pos-total-amount { font-size: 18px; font-weight: 800; letter-spacing: -0.5px; }

        .pos-summary { display: flex; flex-direction: column; gap: 2px; }
        .pos-summary-row { display: flex; justify-content: space-between; font-size: 10px; }
        .pos-summary-row .label { color: var(--text-secondary); }
        .pos-summary-row .value { font-weight: 600; color: var(--text-primary); }
        .pos-summary-row .value.discount { color: var(--danger); }
        .pos-summary-row .value.ppn { color: var(--success); }

        /* ===== Payment Methods ===== */
        .pos-payment-methods { display: flex; flex-direction: column; gap: 3px; }
        .pos-payment-method {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 5px 7px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .pos-payment-method:hover { border-color: var(--primary); background: rgba(15, 108, 189, 0.08); color: var(--primary); }
        .pos-payment-method.active { border-color: var(--primary); background: var(--primary); color: #FFFFFF; }

        /* ===== Numpad ===== */
        .pos-numpad { display: grid; grid-template-columns: repeat(3, 1fr); gap: 3px; }
        .pos-numpad-btn {
            aspect-ratio: 1;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background: var(--bg-body);
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pos-numpad-btn:hover { background: var(--bg-hover); border-color: var(--primary); color: var(--primary); }
        .pos-numpad-btn.cancel { color: var(--danger); }
        .pos-numpad-btn.cancel:hover { background: rgba(245, 158, 11, 0.08); border-color: var(--danger); }
        .pos-numpad-btn.enter { background: var(--success); color: #FFFFFF; border-color: var(--success); }
        .pos-numpad-btn.enter:hover { background: var(--success-dark); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }

        .pos-kbd {
            background: var(--bg-body);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 9px;
            font-weight: 700;
            padding: 1px 4px;
            border-radius: 3px;
        }

        /* ===== Katalog ===== */
        .pos-catalog-item {
            flex: 0 0 auto;
            min-width: 90px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            text-align: center;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 6px;
            cursor: pointer;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .pos-catalog-item:hover { border-color: var(--primary); box-shadow: 0 2px 8px rgba(0,0,0,0.08); }

        /* ===== Cart ===== */
        .pos-cart-empty { text-align: center; padding: 12px; color: var(--text-muted); }
        .pos-cart-table { width: 100%; border-collapse: collapse; }
        .pos-cart-table th {
            background: var(--bg-body);
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 6px;
            border-bottom: 2px solid var(--border-color);
            text-align: left;
            color: var(--text-secondary);
            position: sticky;
            top: 0;
        }
        .pos-cart-table td { padding: 5px; border-bottom: 1px solid var(--border-color); font-size: 11px; vertical-align: middle; }
        .pos-cart-table tr:last-child td { border-bottom: none; }
        .pos-cart-table .name { font-weight: 600; color: var(--text-primary); }
        .pos-cart-table .num { text-align: right; font-weight: 600; color: var(--text-primary); }
        .pos-cart-table .num.discount { color: var(--danger); }

        .pos-qty-btn {
            background: var(--bg-body);
            border: 1px solid var(--border-color);
            border-radius: 3px;
            width: 18px;
            height: 18px;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-primary);
            cursor: pointer;
            padding: 0;
        }
        .pos-qty-btn:hover { background: var(--bg-hover); }
        .pos-qty-input { width: 24px; text-align: center; border: none; font-size: 11px; font-weight: 700; background: transparent; }

        /* ===== Select (Gudang & Kas) ===== */
        .pos-select {
            width: 100%;
            box-sizing: border-box;
            padding: 4px 6px;
            font-size: 11px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: var(--bg-input);
            color: var(--text-primary);
        }

        /* ===== Utilities ===== */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-primary { color: var(--primary); }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }
        .text-muted { color: var(--text-muted); }
        .text-secondary { color: var(--text-secondary); }
        .text-xs { font-size: 10px; }
        .text-sm { font-size: 10px; }
        .text-2xl { font-size: 24px; }
        .font-weight-bold { font-weight: 700; }
        .mt-1 { margin-top: 3px; }
        .mt-2 { margin-top: 6px; }
        .mb-1 { margin-bottom: 3px; }
        .mb-2 { margin-bottom: 6px; }
        .mb-3 { margin-bottom: 8px; }
        .mr-1 { margin-right: 3px; }
        .ml-2 { margin-left: 6px; }
        .w-100 { width: 100%; }
        .w-200 { width: 200px; }
        .py-2 { padding-top: 6px; padding-bottom: 6px; }
        .px-2 { padding-left: 6px; padding-right: 6px; }
        .px-3 { padding-left: 10px; padding-right: 10px; }
        .d-flex { display: flex; }
        .d-block { display: block; }
        .flex-column { flex-direction: column; }
        .gap-1 { gap: 4px; }
        .gap-2 { gap: 6px; }
        .align-items-center { align-items: center; }
        .justify-content-between { justify-content: space-between; }
        .position-relative { position: relative; }
        .flex-shrink-0 { flex-shrink: 0; }
        .overflow-auto { overflow: auto; }
        .border-bottom { border-bottom: 1px solid var(--border-color); }
        .table-responsive { overflow-x: auto; }

        .badge { display: inline-block; padding: 2px 6px; font-size: 10px; font-weight: 600; border-radius: 4px; color: #FFFFFF; }
        .badge-primary { background: var(--primary); }
        .badge-info { background: #0F6CBD; }

        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 4px 8px; font-size: 12px; border: 1px solid transparent; border-radius: 6px; cursor: pointer; }
        .btn-outline-secondary { background: transparent; color: var(--text-secondary); border-color: var(--border-color); }
        .btn-outline-secondary:hover { background: var(--bg-body); }

        .input-group { display: flex; align-items: stretch; width: 100%; }
        .input-group-prepend, .input-group-append { display: flex; align-items: center; }
        .input-group-text { display: flex; align-items: center; padding: 4px 8px; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-secondary); }
    </style>

    {{-- ===================== BRAND ===================== --}}
    <div class="pos-brand">
        <div class="pos-brand-icon">
            <i class="ri-store-2-line"></i>
        </div>
        <div class="pos-brand-text">SUPER CASHIER</div>
        <div class="pos-brand-sub">E-RESEP SAUNDERS</div>
    </div>

    <div class="pos-main">

        {{-- ===================== KIRI ===================== --}}
        <div class="pos-left">

            {{-- Gudang & Kas --}}
            <div class="d-flex gap-2 mb-2">
                <div style="flex: 1;">
                    <label class="text-xs font-weight-bold text-muted d-block mb-1">GUDANG TRANSAKSI</label>
                    <select class="pos-select" wire:model.live="gudang_id" {{ $isView ? 'disabled' : '' }}>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1;">
                    <label class="text-xs font-weight-bold text-muted d-block mb-1">AKUN KAS / BANK</label>
                    <select class="pos-select" wire:model="kas_id" {{ $isView ? 'disabled' : '' }}>
                        @foreach($kasAccounts as $k)
                            <option value="{{ $k->id }}">{{ $k->kode_akun }} - {{ $k->nama_akun }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Input Kode Barang --}}
            <div class="pos-card mb-2">
                <div class="pos-card-header">
                    <h6 class="pos-card-title">
                        <i class="ri-barcode-line text-primary"></i>
                        Masukkan Kode Barang
                    </h6>
                    <span class="pos-kbd">F2</span>
                </div>
                <div class="pos-card-body">
                    <div class="d-flex gap-2">
                        <input type="text" id="posBarcodeInput" class="pos-input w-100"
                               placeholder="Silakan masukkan kode barang/barcode..."
                               wire:model.live.debounce.250ms="barcode"
                               wire:keydown.enter="scanBarcode"
                               autocomplete="off"
                               {{ $isView ? 'readonly disabled' : '' }}>
                        <button type="button" class="pos-btn pos-btn-success w-200" wire:click="scanBarcode">
                            <i class="ri-check-line mr-1"></i> Confirm
                        </button>
                    </div>
                    <p class="text-xs text-muted mt-2">
                        <i class="ri-keyboard-line mr-1"></i> F2: Scan | F8: Bayar | F9: Selesai | F4: New
                    </p>

                    <div class="mt-2 position-relative">
                        <div class="input-group">
                            <input type="text" class="pos-input w-100"
                                   placeholder="Filter katalog / nama produk..."
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
            </div>

            {{-- Katalog Produk --}}
            @if(count($catalogProducts) > 0)
                <div class="pos-card mb-2">
                    <div class="pos-card-header">
                        <h6 class="pos-card-title">
                            <i class="ri-grid-fill text-primary"></i>
                            Katalog Produk ({{ count($catalogProducts) }})
                        </h6>
                    </div>
                    <div class="pos-card-body p-0">
                        <div class="d-flex gap-2 overflow-auto px-2 py-2" style="max-height: 200px;">
                            @foreach($catalogProducts as $prod)
                                <div class="pos-catalog-item"
                                     wire:key="catalog-{{ $prod['id'] }}"
                                     wire:click="addToCart({{ $prod['id'] }})">
                                    <i class="ri-box-3-line text-secondary" style="font-size: 16px;"></i>
                                    <div class="font-weight-bold text-xs mt-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80px;">
                                        {{ $prod['nama'] }}
                                    </div>
                                    <div class="text-xs text-muted" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80px;">
                                        {{ $prod['kode'] }}
                                    </div>
                                    <div class="font-weight-bold text-xs" style="color: var(--primary);">
                                        Rp {{ number_format($prod['harga'], 0, ',', '.') }}
                                    </div>
                                    @if(($prod['in_cart'] ?? 0) > 0)
                                        <span class="badge badge-primary">
                                            <i class="ri-shopping-cart-line mr-1"></i>{{ $prod['in_cart'] }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Keranjang --}}
            <div class="pos-card">
                <div class="pos-card-header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h6 class="pos-card-title">
                            <i class="ri-shopping-basket-line text-primary"></i>
                            Keranjang
                            <span class="badge badge-info ml-2">
                                {{ count($cart) }} item ({{ collect($cart)->sum('qty') }} pcs)
                            </span>
                        </h6>
                        @if(!$isView && count($cart) > 0)
                            <button type="button" class="pos-btn-danger pos-btn-small"
                                    wire:click="clearCart"
                                    wire:confirm="Kosongkan semua item dari keranjang?">
                                <i class="ri-delete-bin-line mr-1"></i> Kosongkan
                            </button>
                        @endif
                    </div>
                </div>
                <div class="pos-card-body p-0">
                    @if(count($cart) == 0)
                        <div class="pos-cart-empty">
                            <i class="ri-shopping-cart-2-line text-2xl d-block mb-1" style="opacity: 0.3;"></i>
                            <p class="text-xs font-weight-bold">Keranjang kosong</p>
                            <p class="text-xs">Scan barcode untuk menambahkan produk</p>
                        </div>
                    @else
                        <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                            <table class="pos-cart-table">
                                <thead>
                                    <tr>
                                        <th style="width: 38%;">Nama</th>
                                        <th style="width: 16%;" class="text-right">Harga</th>
                                        <th style="width: 12%;" class="text-right">Diskon</th>
                                        <th style="width: 18%;" class="text-center">Qty</th>
                                        <th style="width: 16%;" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $key => $item)
                                        <tr wire:key="cart-row-{{ $key }}">
                                            <td class="name">
                                                {{ $item['nama'] }}
                                                @if(($item['diskon_persen'] ?? 0) > 0)
                                                    <div class="text-xs text-danger">-{{ $item['diskon_persen'] }}%</div>
                                                @endif
                                            </td>
                                            <td class="num">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                            <td class="num discount">
                                                @if(($item['diskon_persen'] ?? 0) > 0)
                                                    -{{ $item['diskon_persen'] }}%
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-1" style="justify-content: center;">
                                                    <button type="button" class="pos-qty-btn" wire:click="decrementQty({{ $key }})">-</button>
                                                    <input type="text" class="pos-qty-input" value="{{ $item['qty'] }}" readonly>
                                                    <button type="button" class="pos-qty-btn" wire:click="incrementQty({{ $key }})">+</button>
                                                </div>
                                            </td>
                                            <td class="num">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===================== KANAN ===================== --}}
        <div class="pos-right">

            {{-- Kasir & Jam --}}
            <div class="pos-card mb-2">
                <div class="pos-card-header py-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-user-smile-line text-primary" style="font-size: 16px;"></i>
                        <span class="pos-cashier-name">{{ Auth::user()->name }}</span>
                        <span class="pos-cashier-role text-xs">KASIR</span>
                        <span class="pos-clock" id="posLiveClock">{{ now()->format('H:i:s') }} WIB</span>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Tagihan --}}
            <div class="pos-card">
                <div class="pos-card-header">
                    <h6 class="pos-card-title">
                        <i class="ri-receipt-line text-primary"></i>
                        RINGKASAN TAGIHAN
                    </h6>
                </div>
                <div class="pos-card-body py-2">
                    <div class="pos-total">
                        <div class="pos-total-label">TOTAL TAGIHAN</div>
                        <div class="pos-total-amount">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="pos-summary mt-2">
                        <div class="pos-summary-row">
                            <span class="label">Subtotal</span>
                            <span class="value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($diskon > 0)
                            <div class="pos-summary-row">
                                <span class="label">Diskon ({{ $diskon }}%)</span>
                                <span class="value discount">-Rp {{ number_format($subtotal * $diskon / 100, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($ppn_rate > 0)
                            <div class="pos-summary-row">
                                <span class="label">PPN ({{ $ppn_rate }}%)</span>
                                <span class="value ppn">+Rp {{ number_format($ppn_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Input Bayar --}}
                    <div class="mt-2">
                        <label class="text-xs font-weight-bold text-muted d-block mb-1">BAYAR</label>
                        <input type="number" id="posBayarInput" class="pos-input"
                               wire:model.live.debounce.250ms="bayar" min="0" autocomplete="off"
                               {{ $isView ? 'readonly disabled' : '' }}>
                        <div class="d-flex gap-1 mt-1" style="flex-wrap: wrap;">
                            @foreach($quickCashOptions as $opt)
                                <button type="button" class="pos-btn pos-btn-secondary"
                                        wire:click="setBayar({{ $opt }})">
                                    {{ number_format($opt, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                        <div class="pos-summary-row mt-2">
                            <span class="label">Kembalian</span>
                            <span class="value">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="pos-card">
                <div class="pos-card-header">
                    <h6 class="pos-card-title">
                        <i class="ri-bank-card-line text-success"></i>
                        Metode Pembayaran
                    </h6>
                </div>
                <div class="pos-card-body py-2">
                    <div class="pos-payment-methods">
                        <label class="pos-payment-method active">
                            <input type="radio" name="payment-method" checked style="display:none;">
                            <i class="ri-bank-card-line" style="font-size: 14px;"></i>
                            Debit Card
                        </label>
                        <label class="pos-payment-method">
                            <input type="radio" name="payment-method" style="display:none;">
                            <i class="ri-money-dollar-circle-line" style="font-size: 14px;"></i>
                            Cash
                        </label>
                        <label class="pos-payment-method">
                            <input type="radio" name="payment-method" style="display:none;">
                            <i class="ri-wallet-line" style="font-size: 14px;"></i>
                            Lainnya
                        </label>
                    </div>
                </div>
            </div>

            {{-- Numpad & Aksi --}}
            <div class="pos-card">
                <div class="pos-card-body py-2 d-flex flex-column gap-2">
                    <div class="pos-numpad">
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(7)">7</button>
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(8)">8</button>
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(9)">9</button>
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(4)">4</button>
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(5)">5</button>
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(6)">6</button>
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(1)">1</button>
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(2)">2</button>
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(3)">3</button>
                        <button type="button" class="pos-numpad-btn cancel" wire:click="clearNumpad">C</button>
                        <button type="button" class="pos-numpad-btn" wire:click="appendNumpad(0)">0</button>
                        <button type="button" class="pos-numpad-btn enter" wire:click="focusBayar"><i class="ri-corner-down-left-line"></i></button>
                    </div>

                    <div class="pos-actions">
                        @if($total > 0)
                            <button type="button" class="pos-btn-charge" id="posBtnSubmit" wire:click="simpanPenjualan">
                                <i class="ri-money-dollar-box-line"></i>
                                Charge Rp {{ number_format($total, 0, ',', '.') }}
                            </button>
                        @else
                            <button type="button" class="pos-btn-charge" disabled>
                                <i class="ri-shopping-cart-2-line"></i>
                                Pilih Produk
                            </button>
                        @endif

                        <button type="button" class="pos-btn-small pos-btn-clear"
                                wire:click="resetKasir"
                                wire:confirm="Transaksi baru akan mengosongkan keranjang. Lanjutkan?">
                            <i class="ri-refresh-line"></i>
                            Clear
                        </button>

                        @if(!$isView)
                            <button type="button" class="pos-btn-small pos-btn-print">
                                <i class="ri-printer-line"></i>
                                Print
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== SCRIPT ===================== --}}
    <script>
        (function () {
            const AC = window.AudioContext || window.webkitAudioContext;
            const audioCtx = AC ? new AC() : null;

            function playTone(freq, type, duration, delay = 0) {
                if (!audioCtx) return;
                try {
                    if (audioCtx.state === 'suspended') audioCtx.resume();
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
                } catch (e) {}
            }

            window.posBeepAdd = function () { playTone(880, 'sine', 0.08); };
            window.posBeepSuccess = function () {
                playTone(523.25, 'sine', 0.12, 0);
                playTone(659.25, 'sine', 0.12, 0.1);
                playTone(783.99, 'sine', 0.2, 0.2);
            };
            window.posBeepError = function () { playTone(220, 'square', 0.18); };

            function updatePosClock() {
                const el = document.getElementById('posLiveClock');
                if (!el) return;
                const now = new Date();
                const h = String(now.getHours()).padStart(2, '0');
                const m = String(now.getMinutes()).padStart(2, '0');
                const s = String(now.getSeconds()).padStart(2, '0');
                el.textContent = `${h}:${m}:${s} WIB`;
            }
            if (window.__posClockTimer) clearInterval(window.__posClockTimer);
            window.__posClockTimer = setInterval(updatePosClock, 1000);
            updatePosClock();

            if (window.__posKeyHandler) document.removeEventListener('keydown', window.__posKeyHandler);
            window.__posKeyHandler = function (e) {
                if (e.key === 'F2') {
                    e.preventDefault();
                    const scanner = document.getElementById('posBarcodeInput');
                    if (scanner) { scanner.focus(); scanner.select(); }
                } else if (e.key === 'F8') {
                    e.preventDefault();
                    const bayar = document.getElementById('posBayarInput');
                    if (bayar) { bayar.focus(); bayar.select(); }
                } else if (e.key === 'F9') {
                    e.preventDefault();
                    const btn = document.getElementById('posBtnSubmit');
                    if (btn && !btn.disabled) btn.click();
                } else if (e.key === 'F4') {
                    e.preventDefault();
                    const root = document.querySelector('.pos-wrapper');
                    if (window.Livewire && root) {
                        const holder = root.closest('[wire\\:id]');
                        if (holder) Livewire.find(holder.getAttribute('wire:id')).call('resetKasir');
                    }
                }
            };
            document.addEventListener('keydown', window.__posKeyHandler);

            const registerPosEvents = () => {
                if (window.__posEventsBound) return;
                window.__posEventsBound = true;
                Livewire.on('focus-barcode', () => setTimeout(() => document.getElementById('posBarcodeInput')?.focus(), 80));
                Livewire.on('focus-bayar', () => setTimeout(() => {
                    const el = document.getElementById('posBayarInput');
                    if (el) { el.focus(); el.select(); }
                }, 80));
                Livewire.on('pos-beep-add', () => window.posBeepAdd?.());
                Livewire.on('pos-beep-success', () => window.posBeepSuccess?.());
                Livewire.on('pos-beep-error', () => window.posBeepError?.());
            };
            if (window.Livewire) registerPosEvents();
            else document.addEventListener('livewire:init', registerPosEvents);
        })();
    </script>
</div>