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
                            <i class="far fa-circle nav-icon"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    @endcan
                    @can('role')
                    <li class="nav-item">
                        <a href="{{ route('admin.role.index') }}" class="nav-link {{ Route::is('admin.role.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                    @endcan
                    @can('permission')
                    <li class="nav-item">
                        <a href="{{ route('admin.permission.index') }}" class="nav-link {{ Route::is('admin.permission.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
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
                            <i class="far fa-circle nav-icon"></i>
                            <p>Barang</p>
                        </a>
                    </li>
                    @endcan
                    @can('satuan')
                      <li class="nav-item">
                        <a href="{{ route('admin.satuan.index') }}" class="nav-link {{ Route::is('admin.satuan.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Satuan Barang</p>
                        </a>
                    </li>
                    @endcan
                    @can('supplier')
                    <li class="nav-item">
                        <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ Route::is('admin.suppliers.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Supplier</p>
                        </a>
                    </li>
                    @endcan
                    @can('gudang')
                    <li class="nav-item">
                        <a href="{{ route('admin.gudang.index') }}" class="nav-link {{ Route::is('admin.gudang.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Gudang</p>
                        </a>
                    </li>
                    @endcan
                    @can('departemen')
                    <li class="nav-item">
                        <a href="{{ route('admin.departemen.index') }}" class="nav-link {{ Route::is('admin.departemen.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Departemen</p>
                        </a>
                    </li>
                    @endcan
                    @can('coa')
                    <li class="nav-item">
                        <a href="{{ route('admin.akun.index') }}" class="nav-link {{ Route::is('admin.akun.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Coa</p>
                        </a>
                    </li>
                    @endcan
                    @can('ppn')
                    <li class="nav-item">
                        <a href="{{ route('admin.ppn.index') }}" class="nav-link {{ Route::is('admin.ppn.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>PPN</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['pembelian', 'penerimaan', 'pembayaran', 'pemakaian'])
            <!-- TRANSAKSI -->
            <li class="nav-item has-treeview {{ Route::is('admin.pembelian.*') || Route::is('admin.penerimaan.*') || Route::is('admin.pemakaian.*')|| Route::is('admin.pembayaran.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.pembelian.*') || Route::is('admin.penerimaan.*') || Route::is('admin.pemakaian.*')|| Route::is('admin.pembayaran.*') ? 'active' : '' }}">
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
                            <i class="far fa-circle nav-icon"></i>
                            <p>Purchase Order</p>
                        </a>
                    </li>
                    @endcan
                    @can('penerimaan')
                    <li class="nav-item">
                        <a href="{{ route('admin.penerimaan.index') }}" class="nav-link {{ Route::is('admin.penerimaan.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Penerimaan</p>
                        </a>
                    </li>
                    @endcan
                    @can('pembayaran')
                     <li class="nav-item">
                        <a href="{{ route('admin.pembayaran.index') }}" class="nav-link {{ Route::is('admin.pembayaran.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pembayaran</p>
                        </a>
                    </li>
                    @endcan
                    
                    @can('pemakaian')
                    <li class="nav-item">
                        <a href="{{ route('admin.pemakaian.index') }}" class="nav-link {{ Route::is('admin.pemakaian.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pemakaian</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['stok', 'jurnal'])
            <!-- LAPORAN -->
            <li class="nav-item has-treeview {{ Route::is('admin.stok.*') || Route::is('admin.jurnal.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.stok.*') || Route::is('admin.jurnal.*') ? 'active' : '' }}">
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
                            <i class="far fa-circle nav-icon"></i>
                            <p>Stok</p>
                        </a>
                    </li>
                    @endcan
                    @can('jurnal')
                    <li class="nav-item">
                        <a href="{{ route('admin.jurnal.index') }}" class="nav-link {{ Route::is('admin.jurnal.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Jurnal</p>
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
