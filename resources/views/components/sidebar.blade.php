<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard -->
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        @role('admin')
            <li class="nav-header mt-3 text-muted">MANAJEMEN PENGGUNA</li>

            <li class="nav-item">
                <a href="{{ route('admin.user.index') }}" class="nav-link {{ Route::is('admin.user.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Users</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.role.index') }}" class="nav-link {{ Route::is('admin.role.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-tag"></i>
                    <p>Roles</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.permission.index') }}" class="nav-link {{ Route::is('admin.permission.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-key"></i>
                    <p>Permissions</p>
                </a>
            </li>

            <li class="nav-header mt-3 text-muted">MASTER DATA</li>

            <li class="nav-item">
                <a href="{{ route('admin.barang.index') }}" class="nav-link {{ Route::is('admin.barang.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ Route::is('admin.suppliers.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-truck"></i>
                    <p>Supplier</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.gudang.index') }}" class="nav-link {{ Route::is('admin.gudang.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-warehouse"></i>
                    <p>Gudang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.departemen.index') }}" class="nav-link {{ Route::is('admin.departemen.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-building"></i>
                    <p>Departemen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.akun.index') }}" class="nav-link {{ Route::is('admin.akun.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>Akun</p>
                </a>
            </li>

            <li class="nav-header mt-3 text-muted">TRANSAKSI</li>

            <li class="nav-item">
                <a href="{{ route('admin.pembelian.index') }}" class="nav-link {{ Route::is('admin.pembelian.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cart-plus"></i>
                    <p>Pembelian</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.penerimaan.index') }}" class="nav-link {{ Route::is('admin.penerimaan.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-dolly-flatbed"></i>
                    <p>Penerimaan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.pemakaian.index') }}" class="nav-link {{ Route::is('admin.pemakaian.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-people-carry"></i>
                    <p>Pemakaian</p>
                </a>
            </li>

            <li class="nav-header mt-3 text-muted">LAPORAN</li>

            <li class="nav-item">
                <a href="{{ route('admin.stok.index') }}" class="nav-link {{ Route::is('admin.stok.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-archive"></i>
                    <p>Stok</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.jurnal.index') }}" class="nav-link {{ Route::is('admin.jurnal.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-balance-scale"></i>
                    <p>Jurnal</p>
                </a>
            </li>
        @endrole

        <li class="nav-header mt-3 text-muted">LAINNYA</li>

        <li class="nav-item">
            <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ Route::is('admin.profile.edit') ? 'active' : '' }}">
                <i class="nav-icon fas fa-id-card"></i>
                <p>Profil Saya</p>
            </a>
        </li>
    </ul>
</nav>
