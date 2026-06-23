<div class="tab-pane <?= ($active_tab ?? '') == 'pengguna' ? 'active show' : '' ?>" id="tab-pengguna">
    
    <!-- 1. HEADER DENGAN DESKRIPSI (UI/UX) -->
    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center py-3">
        <div>
            <h3 class="card-title mb-0">Daftar Pengguna Sistem</h3>
            <p class="text-muted small mb-0 mt-1">Kelola data pengguna, peran, serta hak akses dalam sistem.</p>
        </div>
        <div class="card-actions">
            <!-- Tombol responsif: Teks di desktop, hanya ikon di mobile -->
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary d-none d-sm-inline-flex">
                <i class="ti ti-plus icon me-2"></i> Tambah Pengguna
            </a>
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary btn-icon d-sm-none" aria-label="Tambah Pengguna" data-bs-toggle="tooltip" title="Tambah Pengguna">
                <i class="ti ti-plus icon"></i>
            </a>
        </div>
    </div>

    <!-- 2. SEARCH & FILTER BAR (Peningkatan UX) -->
    <div class="card-body border-bottom py-3 bg-light bg-opacity-50">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <div class="text-muted d-none d-md-block">
                Tampilkan
                <div class="mx-2 d-inline-block">
                    <select class="form-select form-select-sm" id="per-page" aria-label="Entri per halaman">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                entri
            </div>
            <div class="ms-auto d-flex gap-2 w-100 w-md-auto">
                <select class="form-select form-select-sm w-auto" id="filter-role" aria-label="Filter Peran">
                    <option value="">Semua Peran</option>
                    <option value="admin">Administrator</option>
                    <option value="pimpinan">Pimpinan</option>
                    <option value="admin_tamu">Admin Buku Tamu</option>
                    <option value="staf">Staf / Pegawai</option>
                </select>
                <div class="input-icon flex-fill">
                    <span class="input-icon-addon">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" class="form-control form-control-sm" id="search-user" placeholder="Cari pengguna..." aria-label="Cari pengguna">
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TABEL DATA -->
    <div class="table-responsive">
        <!-- Tambahan table-hover untuk feedback interaksi baris -->
        <table class="table card-table table-vcenter text-nowrap datatable table-hover">
            <thead>
                <tr>
                    <th class="w-1 text-muted">No.</th>
                    <th>Pengguna</th>
                    <th>Username</th>
                    <th>Jabatan</th>
                    <th>Peran</th>
                    <th class="text-end w-5">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($users) && count($users) > 0): ?>
                    <?php $i = 1;
                    foreach ($users as $user): ?>
                        <tr data-role="<?= esc($user['role']) ?>">
                            <td class="text-muted"><?= $i++ ?></td>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <!-- Avatar diperbaiki: bulat penuh & warna dinamis -->
                                    <span class="avatar me-3 rounded-circle shadow-sm" style="background-image: url('https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap']) ?>&background=random&color=fff')"></span>
                                    <div class="flex-fill">
                                        <div class="font-weight-bold text-body mb-1"><?= esc($user['nama_lengkap']) ?></div>
                                        <div class="text-muted small">
                                            <i class="ti ti-mail me-1"></i>
                                            <a href="mailto:<?= esc($user['email']) ?>" class="text-reset text-decoration-none hover-primary"><?= esc($user['email']) ?></a>
</div>

<script>
(function() {
    var searchInput = document.getElementById('search-user');
    var roleFilter = document.getElementById('filter-role');
    var perPage = document.getElementById('per-page');
    var tableBody = document.querySelector('#tab-pengguna tbody');
    if (!tableBody || !searchInput || !roleFilter || !perPage) return;

    var allRows = Array.prototype.slice.call(tableBody.querySelectorAll('tr[data-role]'));

    function filterRows() {
        var keyword = searchInput.value.toLowerCase().trim();
        var role = roleFilter.value;
        var max = parseInt(perPage.value) || 25;

        var visible = allRows.filter(function(row) {
            if (role && row.getAttribute('data-role') !== role) return false;
            if (keyword) {
                var text = row.textContent.toLowerCase();
                if (text.indexOf(keyword) === -1) return false;
            }
            return true;
        });

        // Hide all first
        allRows.forEach(function(r) { r.style.display = 'none'; });

        // Show only matching + within page limit
        visible.slice(0, max).forEach(function(r) { r.style.display = ''; });

        // Update pagination info
        var info = document.querySelector('#tab-pengguna .card-footer .text-muted small');
        if (info) {
            var shown = Math.min(visible.length, max);
            info.innerHTML = 'Menampilkan <span>1</span> hingga <span>' + shown + '</span> dari <span>' + visible.length + '</span> entri';
        }
    }

    searchInput.addEventListener('input', filterRows);
    roleFilter.addEventListener('change', filterRows);
    perPage.addEventListener('change', filterRows);

    // Initial filter
    filterRows();
})();
</script>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <!-- Pembedaan visual untuk username -->
                                <span class="text-muted fw-medium">@<?= esc($user['username']) ?></span>
                            </td>
                            <td class="text-secondary"><?= esc($user['jabatan']) ?></td>
                            <td>
                                <!-- 4. BADGE SOFT COLORS DENGAN IKON -->
                                <?php if ($user['role'] == 'admin'): ?>
                                    <span class="badge bg-purple-lt py-1 px-2"><i class="ti ti-shield-lock me-1"></i> Administrator</span>
                                <?php elseif ($user['role'] == 'pimpinan'): ?>
                                    <span class="badge bg-blue-lt py-1 px-2"><i class="ti ti-tie me-1"></i> Pimpinan</span>
                                <?php elseif ($user['role'] == 'admin_tamu'): ?>
                                    <span class="badge bg-azure-lt py-1 px-2"><i class="ti ti-notebook me-1"></i> Admin Buku Tamu</span>
                                <?php else: ?>
                                    <span class="badge bg-green-lt py-1 px-2"><i class="ti ti-user me-1"></i> Staf / Pegawai</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <!-- 5. GHOST BUTTONS (Mengurangi keramaian visual) -->
                                <div class="btn-list flex-nowrap justify-content-end">
                                    <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-icon btn-ghost-primary" title="Edit Pengguna" data-bs-toggle="tooltip">
                                        <i class="ti ti-edit icon"></i>
                                    </a>
                                    <form action="<?= base_url('admin/users/delete/' . $user['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin ingin menghapus akun @<?= esc($user['username']) ?>?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-icon btn-ghost-danger" title="Hapus Pengguna" data-bs-toggle="tooltip">
                                            <i class="ti ti-trash icon"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- 6. EMPTY STATE UX -->
                    <tr>
                        <td colspan="6">
                            <div class="empty py-5">
                                <div class="empty-icon text-muted mb-3">
                                    <i class="ti ti-users" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                                <p class="empty-title h3 font-weight-bold">Belum ada data pengguna</p>
                                <p class="empty-subtitle text-muted mb-4">
                                    Sistem saat ini belum memiliki data pengguna yang tersimpan. Mulai tambahkan pengguna pertama Anda.
                                </p>
                                <div class="empty-action">
                                    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                                        <i class="ti ti-plus icon me-2"></i> Tambah Pengguna
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- 7. PAGINATION MOCKUP (UX) -->
    <?php if (isset($users) && count($users) > 0): ?>
    <div class="card-footer d-flex align-items-center py-3">
        <p class="m-0 text-muted small">Menampilkan <span>1</span> hingga <span><?= count($users) ?></span> dari <span><?= count($users) ?></span> entri</p>
        <ul class="pagination m-0 ms-auto">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                    <i class="ti ti-chevron-left"></i>
                </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item">
                <a class="page-link" href="#">
                    <i class="ti ti-chevron-right"></i>
                </a>
            </li>
        </ul>
    </div>
    <?php endif; ?>

</div>