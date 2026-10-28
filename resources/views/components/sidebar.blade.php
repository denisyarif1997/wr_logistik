<nav>
    <ul class="nav-menu">
        <!-- Dashboard -->
        @can('dashboard')
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="ri-dashboard-line"></i>
                <span>Dashboard</span>
            </a>
        </li>
        @endcan

        @canany(['user', 'role', 'permission'])
            <!-- MANAJEMEN PENGGUNA -->
            <div class="nav-header">Pengguna</div>
            <li class="nav-item">
                <a href="#" class="nav-link has-treeview {{ Route::is('admin.user.*') || Route::is('admin.role.*') || Route::is('admin.permission.*') ? 'active' : '' }}">
                    <i class="ri-group-line"></i>
                    <span>Manajemen</span>
                    <i class="ri-arrow-right-s-line chevron {{ Route::is('admin.user.*') || Route::is('admin.role.*') || Route::is('admin.permission.*') ? 'open' : '' }}"></i>
                </a>
                <ul class="nav-treeview {{ Route::is('admin.user.*') || Route::is('admin.role.*') || Route::is('admin.permission.*') ? 'open' : '' }}">
                    @can('user')
                    <li class="nav-item">
                        <a href="{{ route('admin.user.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.user.index') ? 'active' : '' }}">
                            <i class="ri-user-line"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    @endcan
                    @can('role')
                    <li class="nav-item">
                        <a href="{{ route('admin.role.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.role.index') ? 'active' : '' }}">
                            <i class="ri-user-star-line"></i>
                            <span>Roles</span>
                        </a>
                    </li>
                    @endcan
                    @can('permission')
                    <li class="nav-item">
                        <a href="{{ route('admin.permission.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.permission.index') ? 'active' : '' }}">
                            <i class="ri-lock-line"></i>
                            <span>Permissions</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['barang', 'supplier', 'gudang', 'satuan', 'departemen', 'coa', 'ppn', 'pengaturan'])
            <!-- MASTER DATA -->
            <div class="nav-header">Master Data</div>
            <li class="nav-item">
                <a href="#" class="nav-link has-treeview {{ Route::is('admin.barang.*') || Route::is('admin.suppliers.*') || Route::is('admin.gudang.*') ||Route::is('admin.satuan.*') || Route::is('admin.departemen.*') || Route::is('admin.akun.*') || Route::is('admin.ppn.*') || Route::is('admin.pengaturan.*') ? 'active' : '' }}">
                    <i class="ri-database-2-line"></i>
                    <span>Data Master</span>
                    <i class="ri-arrow-right-s-line chevron {{ Route::is('admin.barang.*') || Route::is('admin.suppliers.*') || Route::is('admin.gudang.*') ||Route::is('admin.satuan.*') || Route::is('admin.departemen.*') || Route::is('admin.akun.*') || Route::is('admin.ppn.*') || Route::is('admin.pengaturan.*') ? 'open' : '' }}"></i>
                </a>
                <ul class="nav-treeview {{ Route::is('admin.barang.*') || Route::is('admin.suppliers.*') || Route::is('admin.gudang.*') ||Route::is('admin.satuan.*') || Route::is('admin.departemen.*') || Route::is('admin.akun.*') || Route::is('admin.ppn.*') || Route::is('admin.pengaturan.*') ? 'open' : '' }}">
                    @can('barang')
                    <li class="nav-item">
                        <a href="{{ route('admin.barang.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.barang.index') ? 'active' : '' }}">
                            <i class="ri-box-3-line"></i>
                            <span>Barang</span>
                        </a>
                    </li>
                    @endcan
                    @can('satuan')
                    <li class="nav-item">
                        <a href="{{ route('admin.satuan.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.satuan.index') ? 'active' : '' }}">
                            <i class="ri-ruler-line"></i>
                            <span>Satuan Barang</span>
                        </a>
                    </li>
                    @endcan
                    @can('supplier')
                    <li class="nav-item">
                        <a href="{{ route('admin.suppliers.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.suppliers.index') ? 'active' : '' }}">
                            <i class="ri-truck-line"></i>
                            <span>Supplier</span>
                        </a>
                    </li>
                    @endcan
                    @can('gudang')
                    <li class="nav-item">
                        <a href="{{ route('admin.gudang.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.gudang.index') ? 'active' : '' }}">
                            <i class="ri-warehouse-line"></i>
                            <span>Gudang</span>
                        </a>
                    </li>
                    @endcan
                    @can('departemen')
                    <li class="nav-item">
                        <a href="{{ route('admin.departemen.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.departemen.index') ? 'active' : '' }}">
                            <i class="ri-organization-chart"></i>
                            <span>Departemen</span>
                        </a>
                    </li>
                    @endcan
                    @can('coa')
                    <li class="nav-item">
                        <a href="{{ route('admin.akun.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.akun.index') ? 'active' : '' }}">
                            <i class="ri-list-check-2"></i>
                            <span>COA</span>
                        </a>
                    </li>
                    @endcan
                    @can('ppn')
                    <li class="nav-item">
                        <a href="{{ route('admin.ppn.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.ppn.index') ? 'active' : '' }}">
                            <i class="ri-percent-line"></i>
                            <span>PPN</span>
                        </a>
                    </li>
                    @endcan
                    @can('pengaturan')
                    <li class="nav-item">
                        <a href="{{ route('admin.pengaturan.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.pengaturan.index') ? 'active' : '' }}">
                            <i class="ri-settings-3-line"></i>
                            <span>Pengaturan COA</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['pembelian', 'penerimaan', 'pembayaran', 'pemakaian', 'transfer', 'penyesuaian', 'kasir'])
            <!-- TRANSAKSI -->
            <div class="nav-header">Transaksi</div>
            <li class="nav-item">
                <a href="#" class="nav-link has-treeview {{ Route::is('admin.pembelian.*') || Route::is('admin.penerimaan.*') || Route::is('admin.pemakaian.*')|| Route::is('admin.pembayaran.*') || Route::is('admin.transfer.*') || Route::is('admin.penyesuaian.*') || Route::is('admin.kasir.*') || Route::is('admin.penjualan.*') ? 'active' : '' }}">
                    <i class="ri-swap-box-line"></i>
                    <span>Transaksi</span>
                    <i class="ri-arrow-right-s-line chevron {{ Route::is('admin.pembelian.*') || Route::is('admin.penerimaan.*') || Route::is('admin.pemakaian.*')|| Route::is('admin.pembayaran.*') || Route::is('admin.transfer.*') || Route::is('admin.penyesuaian.*') || Route::is('admin.kasir.*') || Route::is('admin.penjualan.*') ? 'open' : '' }}"></i>
                </a>
                <ul class="nav-treeview {{ Route::is('admin.pembelian.*') || Route::is('admin.penerimaan.*') || Route::is('admin.pemakaian.*')|| Route::is('admin.pembayaran.*') || Route::is('admin.transfer.*') || Route::is('admin.penyesuaian.*') || Route::is('admin.kasir.*') || Route::is('admin.penjualan.*') ? 'open' : '' }}">
                    @can('pembelian')
                    <li class="nav-item">
                        <a href="{{ route('admin.pembelian.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.pembelian.index') ? 'active' : '' }}">
                            <i class="ri-file-list-3-line"></i>
                            <span>Purchase Order</span>
                        </a>
                    </li>
                    @endcan
                    @can('penerimaan')
                    <li class="nav-item">
                        <a href="{{ route('admin.penerimaan.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.penerimaan.index') ? 'active' : '' }}">
                            <i class="ri-inbox-archive-line"></i>
                            <span>Penerimaan</span>
                        </a>
                    </li>
                    @endcan
                    @can('pembayaran')
                    <li class="nav-item">
                        <a href="{{ route('admin.pembayaran.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.pembayaran.index') ? 'active' : '' }}">
                            <i class="ri-bank-card-line"></i>
                            <span>Pembayaran</span>
                        </a>
                    </li>
                    @endcan
                    @can('pemakaian')
                    <li class="nav-item">
                        <a href="{{ route('admin.pemakaian.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.pemakaian.index') ? 'active' : '' }}">
                            <i class="ri-open-arm-line"></i>
                            <span>Pemakaian</span>
                        </a>
                    </li>
                    @endcan
                    @can('transfer')
                    <li class="nav-item">
                        <a href="{{ route('admin.transfer.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.transfer.*') ? 'active' : '' }}">
                            <i class="ri-truck-line"></i>
                            <span>Transfer Barang</span>
                        </a>
                    </li>
                    @endcan
                    @can('penyesuaian')
                    <li class="nav-item">
                        <a href="{{ route('admin.penyesuaian.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.penyesuaian.index') ? 'active' : '' }}">
                            <i class="ri-scales-3-line"></i>
                            <span>Penyesuaian Stok</span>
                        </a>
                    </li>
                    @endcan
                    @can('kasir')
                    <li class="nav-item">
                        <a href="{{ route('admin.kasir.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.kasir.index') ? 'active' : '' }}">
                            <i class="ri-cash-register-line"></i>
                            <span>Kasir</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['stok', 'jurnal', 'buku_besar', 'laba_rugi', 'neraca', 'hutang_supplier', 'stock_opname'])
            <!-- LAPORAN -->
            <div class="nav-header">Laporan</div>
            <li class="nav-item">
                <a href="#" class="nav-link has-treeview {{ Route::is('admin.stok.*') || Route::is('admin.jurnal.*') || Route::is('admin.buku-besar.*') || Route::is('admin.laba-rugi.*') || Route::is('admin.neraca.*') || Route::is('admin.hutang-supplier.*') || Route::is('admin.stock-opname.*') ? 'active' : '' }}">
                    <i class="ri-file-chart-line"></i>
                    <span>Laporan</span>
                    <i class="ri-arrow-right-s-line chevron {{ Route::is('admin.stok.*') || Route::is('admin.jurnal.*') || Route::is('admin.buku-besar.*') || Route::is('admin.laba-rugi.*') || Route::is('admin.neraca.*') || Route::is('admin.hutang-supplier.*') || Route::is('admin.stock-opname.*') ? 'open' : '' }}"></i>
                </a>
                <ul class="nav-treeview {{ Route::is('admin.stok.*') || Route::is('admin.jurnal.*') || Route::is('admin.buku-besar.*') || Route::is('admin.laba-rugi.*') || Route::is('admin.neraca.*') || Route::is('admin.hutang-supplier.*') || Route::is('admin.stock-opname.*') ? 'open' : '' }}">
                    @can('stok')
                    <li class="nav-item">
                        <a href="{{ route('admin.stok.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.stok.index') ? 'active' : '' }}">
                            <i class="ri-stack-line"></i>
                            <span>Stok</span>
                        </a>
                    </li>
                    @endcan
                    @can('jurnal')
                    <li class="nav-item">
                        <a href="{{ route('admin.jurnal.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.jurnal.index') ? 'active' : '' }}">
                            <i class="ri-book-2-line"></i>
                            <span>Jurnal</span>
                        </a>
                    </li>
                    @endcan
                    @can('buku_besar')
                    <li class="nav-item">
                        <a href="{{ route('admin.buku-besar.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.buku-besar.index') ? 'active' : '' }}">
                            <i class="ri-book-open-line"></i>
                            <span>Buku Besar</span>
                        </a>
                    </li>
                    @endcan
                    @can('laba_rugi')
                    <li class="nav-item">
                        <a href="{{ route('admin.laba-rugi.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.laba-rugi.index') ? 'active' : '' }}">
                            <i class="ri-line-chart-line"></i>
                            <span>Laba Rugi</span>
                        </a>
                    </li>
                    @endcan
                    @can('neraca')
                    <li class="nav-item">
                        <a href="{{ route('admin.neraca.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.neraca.index') ? 'active' : '' }}">
                            <i class="ri-scales-line"></i>
                            <span>Neraca</span>
                        </a>
                    </li>
                    @endcan
                    @can('hutang_supplier')
                    <li class="nav-item">
                        <a href="{{ route('admin.hutang-supplier.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.hutang-supplier.index') ? 'active' : '' }}">
                            <i class="ri-file-dollar-line"></i>
                            <span>Hutang Supplier</span>
                        </a>
                    </li>
                    @endcan
                    @can('stock_opname')
                    <li class="nav-item">
                        <a href="{{ route('admin.stock-opname.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.stock-opname.index') ? 'active' : '' }}">
                            <i class="ri-clipboard-line"></i>
                            <span>Stock Opname</span>
                        </a>
                    </li>
                    @endcan
                    @can('stock_opname')
                    <li class="nav-item">
                        <a href="{{ route('admin.laporan-stock-opname.index') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.laporan-stock-opname.index') ? 'active' : '' }}">
                            <i class="ri-file-text-line"></i>
                            <span>Lap. Stock Opname</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- PROFIL -->
        <div class="nav-header">Lainnya</div>
        <li class="nav-item">
            <a href="{{ route('admin.profile.edit') }}" wire:navigate.hover class="nav-link {{ Route::is('admin.profile.edit') ? 'active' : '' }}">
                <i class="ri-user-settings-line"></i>
                <span>Profil Saya</span>
            </a>
        </li>
    </ul>
</nav>