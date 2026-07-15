<x-admin>
    @section('title','Kelola Permission - ' . $role->name)
    <section class="content">
        <div class="container-fluid">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-key me-2"></i>
                        Kelola Permission &mdash; <strong>{{ $role->name }}</strong>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.role.index') }}" class="btn btn-sm btn-dark">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.role.syncPermissions', encrypt($role->id)) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        {{-- Toolbar: Select All / Deselect All --}}
                        <div class="mb-3 d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success" id="btnSelectAll">
                                <i class="fas fa-check-square me-1"></i> Pilih Semua
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="btnDeselectAll">
                                <i class="fas fa-square me-1"></i> Hapus Semua
                            </button>
                            <span class="ms-3 text-muted" id="selectedCount">
                                {{ $rolePermissions->count() }} dari {{ $permissions->count() }} permission dipilih
                            </span>
                        </div>

                        {{-- Search Box --}}
                        <div class="mb-3">
                            <input type="text" id="permissionSearch" class="form-control"
                                placeholder="&#128269; Cari permission...">
                        </div>

                        {{-- Group permissions by prefix (split by ".") --}}
                        @php
                            $grouped = $permissions->groupBy(function($p) {
                                $parts = explode('.', $p->name);
                                return count($parts) > 1 ? $parts[0] : 'Other';
                            });
                        @endphp

                        <div class="row" id="permissionList">
                            @foreach($grouped as $group => $perms)
                                <div class="col-lg-4 col-md-6 mb-4 perm-group">
                                    <div class="card border shadow-sm h-100">
                                        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                            <strong class="text-uppercase text-primary" style="font-size:0.78rem; letter-spacing:1px;">
                                                <i class="fas fa-layer-group me-1"></i> {{ $group }}
                                            </strong>
                                            <span class="badge bg-secondary group-badge">
                                                {{ $perms->whereIn('name', $rolePermissions->pluck('name')->toArray())->count() }}/{{ $perms->count() }}
                                            </span>
                                        </div>
                                        <div class="card-body py-2">
                                            @foreach($perms as $permission)
                                                <div class="form-check perm-item mb-1">
                                                    <input
                                                        class="form-check-input perm-checkbox"
                                                        type="checkbox"
                                                        name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        id="perm_{{ $permission->id }}"
                                                        {{ $rolePermissions->contains('name', $permission->name) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label perm-label" for="perm_{{ $permission->id }}" style="cursor:pointer;">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($permissions->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-lock fa-3x mb-3"></i>
                                <p>Belum ada permission yang dibuat. Tambahkan permission terlebih dahulu.</p>
                                <a href="{{ route('admin.permission.index') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah Permission
                                </a>
                            </div>
                        @endif

                    </div><!-- /.card-body -->

                    @if($permissions->isNotEmpty())
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Centang permission yang ingin diberikan kepada role <strong>{{ $role->name }}</strong>
                            </small>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Simpan Permission
                            </button>
                        </div>
                    @endif

                </form>
            </div>

        </div>
    </section>

    @section('js')
    <script>
        $(function () {

            // ── Count helper ────────────────────────────────────────────
            function updateCount() {
                var total   = $('.perm-checkbox').length;
                var checked = $('.perm-checkbox:checked').length;
                $('#selectedCount').text(checked + ' dari ' + total + ' permission dipilih');

                // update per-group badge
                $('.perm-group').each(function () {
                    var groupChecked = $(this).find('.perm-checkbox:checked').length;
                    var groupTotal   = $(this).find('.perm-checkbox').length;
                    $(this).find('.group-badge').text(groupChecked + '/' + groupTotal);
                });
            }

            // ── Select / Deselect All ────────────────────────────────────
            $('#btnSelectAll').on('click', function () {
                $('.perm-checkbox:visible').prop('checked', true);
                updateCount();
            });

            $('#btnDeselectAll').on('click', function () {
                $('.perm-checkbox:visible').prop('checked', false);
                updateCount();
            });

            // ── Live search ──────────────────────────────────────────────
            $('#permissionSearch').on('input', function () {
                var q = $(this).val().toLowerCase().trim();

                $('.perm-item').each(function () {
                    var label = $(this).find('.perm-label').text().toLowerCase();
                    $(this).toggle(q === '' || label.includes(q));
                });

                // hide empty groups
                $('.perm-group').each(function () {
                    var visible = $(this).find('.perm-item:visible').length;
                    $(this).toggle(visible > 0);
                });

                updateCount();
            });

            // ── Checkbox change ──────────────────────────────────────────
            $(document).on('change', '.perm-checkbox', function () {
                updateCount();
            });

            // Initial count
            updateCount();
        });
    </script>
    @endsection
</x-admin>
