<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="true">
        <!-- Dashboard -->
        @can('dashboard')
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>
        @endcan

        @canany(['user', 'role', 'permission'])
            <!-- MANAJEMEN PENGGUNA -->
            <li class="nav-item has-treeview {{ Route::is('admin.user.*') || Route::is('admin.role.*') || Route::is('admin.permission.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.user.*') || Route::is('admin.role.*') || Route::is('admin.permission.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p>
                        Pengguna
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('user')
                    <li class="nav-item">
                        <a href="{{ route('admin.user.index') }}" class="nav-link {{ Route::is('admin.user.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    @endcan
                    @can('role')
                    <li class="nav-item">
                        <a href="{{ route('admin.role.index') }}" class="nav-link {{ Route::is('admin.role.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tag"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                    @endcan
                    @can('permission')
                    <li class="nav-item">
                        <a href="{{ route('admin.permission.index') }}" class="nav-link {{ Route::is('admin.permission.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-key"></i>
                            <p>Permissions</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['barang', 'supplier', 'gudang', 'satuan', 'departemen', 'coa', 'ppn'])
            <!-- MASTER DATA -->
            <li class="nav-item has-treeview {{ Route::is('admin.barang.*') || Route::is('admin.suppliers.*') || Route::is('admin.gudang.*') ||Route::is('admin.satuan.*') || Route::is('admin.departemen.*') || Route::is('admin.akun.*') || Route::is('admin.ppn.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.barang.*') || Route::is('admin.suppliers.*') || Route::is('admin.gudang.*') || Route::is('admin.satuan.*') || Route::is('admin.departemen.*') || Route::is('admin.akun.*') || Route::is('admin.ppn.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-database"></i>
                    <p>
                        Master Data
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('barang')
                    <li class="nav-item">
                        <a href="{{ route('admin.barang.index') }}" class="nav-link {{ Route::is('admin.barang.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Barang</p>
                        </a>
                    </li>
                    @endcan
                    @can('satuan')
                      <li class="nav-item">
                        <a href="{{ route('admin.satuan.index') }}" class="nav-link {{ Route::is('admin.satuan.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ruler-combined"></i>
                            <p>Satuan Barang</p>
                        </a>
                    </li>
                    @endcan
                    @can('supplier')
                    <li class="nav-item">
                        <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ Route::is('admin.suppliers.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck"></i>
                            <p>Supplier</p>
                        </a>
                    </li>
                    @endcan
                    @can('gudang')
                    <li class="nav-item">
                        <a href="{{ route('admin.gudang.index') }}" class="nav-link {{ Route::is('admin.gudang.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>Gudang</p>
                        </a>
                    </li>
                    @endcan
                    @can('departemen')
                    <li class="nav-item">
                        <a href="{{ route('admin.departemen.index') }}" class="nav-link {{ Route::is('admin.departemen.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-sitemap"></i>
                            <p>Departemen</p>
                        </a>
                    </li>
                    @endcan
                    @can('coa')
                    <li class="nav-item">
                        <a href="{{ route('admin.akun.index') }}" class="nav-link {{ Route::is('admin.akun.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Coa</p>
                        </a>
                    </li>
                    @endcan
                    @can('ppn')
                    <li class="nav-item">
                        <a href="{{ route('admin.ppn.index') }}" class="nav-link {{ Route::is('admin.ppn.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-percent"></i>
                            <p>PPN</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['pembelian', 'penerimaan', 'pembayaran', 'pemakaian', 'transfer', 'penyesuaian', 'kasir'])
            <!-- TRANSAKSI -->
            <li class="nav-item has-treeview {{ Route::is('admin.pembelian.*') || Route::is('admin.penerimaan.*') || Route::is('admin.pemakaian.*')|| Route::is('admin.pembayaran.*') || Route::is('admin.transfer.*') || Route::is('admin.penyesuaian.*') || Route::is('admin.kasir.*') || Route::is('admin.penjualan.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.pembelian.*') || Route::is('admin.penerimaan.*') || Route::is('admin.pemakaian.*')|| Route::is('admin.pembayaran.*') || Route::is('admin.transfer.*') || Route::is('admin.penyesuaian.*') || Route::is('admin.kasir.*') || Route::is('admin.penjualan.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-exchange-alt"></i>
                    <p>
                        Transaksi
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('pembelian')
                    <li class="nav-item">
                        <a href="{{ route('admin.pembelian.index') }}" class="nav-link {{ Route::is('admin.pembelian.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>Purchase Order</p>
                        </a>
                    </li>
                    @endcan
                    @can('penerimaan')
                    <li class="nav-item">
                        <a href="{{ route('admin.penerimaan.index') }}" class="nav-link {{ Route::is('admin.penerimaan.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dolly"></i>
                            <p>Penerimaan</p>
                        </a>
                    </li>
                    @endcan
                    @can('pembayaran')
                     <li class="nav-item">
                        <a href="{{ route('admin.pembayaran.index') }}" class="nav-link {{ Route::is('admin.pembayaran.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check-alt"></i>
                            <p>Pembayaran</p>
                        </a>
                    </li>
                    @endcan

                    @can('pemakaian')
                    <li class="nav-item">
                        <a href="{{ route('admin.pemakaian.index') }}" class="nav-link {{ Route::is('admin.pemakaian.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box-open"></i>
                            <p>Pemakaian</p>
                        </a>
                    </li>
                    @endcan

                    @can('transfer')
                    <li class="nav-item">
                        <a href="{{ route('admin.transfer.index') }}" class="nav-link {{ Route::is('admin.transfer.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>Transfer Barang</p>
                        </a>
                    </li>
                    @endcan

                    @can('penyesuaian')
                    <li class="nav-item">
                        <a href="{{ route('admin.penyesuaian.index') }}" class="nav-link {{ Route::is('admin.penyesuaian.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-balance-scale"></i>
                            <p>Penyesuaian Stok</p>
                        </a>
                    </li>
                    @endcan

                    @can('kasir')
                    <li class="nav-item">
                        <a href="{{ route('admin.kasir.index') }}" class="nav-link {{ Route::is('admin.kasir.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Kasir</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.penjualan.index') }}" class="nav-link {{ Route::is('admin.penjualan.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Daftar Penjualan</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['stok', 'jurnal', 'buku_besar'])
            <!-- LAPORAN -->
            <li class="nav-item has-treeview {{ Route::is('admin.stok.*') || Route::is('admin.jurnal.*') || Route::is('admin.buku-besar.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.stok.*') || Route::is('admin.jurnal.*') || Route::is('admin.buku-besar.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-alt"></i>
                    <p>
                        Laporan
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('stok')
                    <li class="nav-item">
                        <a href="{{ route('admin.stok.index') }}" class="nav-link {{ Route::is('admin.stok.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Stok</p>
                        </a>
                    </li>
                    @endcan
                    @can('jurnal')
                    <li class="nav-item">
                        <a href="{{ route('admin.jurnal.index') }}" class="nav-link {{ Route::is('admin.jurnal.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Jurnal</p>
                        </a>
                    </li>
                    @endcan
                    @can('buku_besar')
                    <li class="nav-item">
                        <a href="{{ route('admin.buku-besar.index') }}" class="nav-link {{ Route::is('admin.buku-besar.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Buku Besar</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- PROFIL -->
        <li class="nav-header mt-3 text-muted">LAINNYA</li>
        <li class="nav-item">
            <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ Route::is('admin.profile.edit') ? 'active' : '' }}">
                <i class="nav-icon fas fa-id-card"></i>
                <p>Profil Saya</p>
            </a>
        </li>
    </ul>
</nav>