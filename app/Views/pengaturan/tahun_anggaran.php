<div class="tab-pane <?= ($active_tab ?? '') == 'tahun-anggaran' ? 'active show' : '' ?>" id="tab-tahun-anggaran">
    <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="card-title">
                <i class="ti ti-calendar-time me-2 text-primary"></i>Manajemen Tahun Anggaran
            </h3>
            <p class="card-subtitle text-muted mt-1">Kelola periode aktif yang digunakan di seluruh sistem.</p>
        </div>
        <div class="card-actions">
            <button type="button" class="btn btn-primary shadow-sm d-none d-sm-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modal-tambah-tahun">
                <i class="ti ti-plus"></i> Tambah Tahun Baru
            </button>
            <button type="button" class="btn btn-primary btn-icon shadow-sm d-sm-none" data-bs-toggle="modal" data-bs-target="#modal-tambah-tahun" aria-label="Tambah Tahun">
                <i class="ti ti-plus"></i>
            </button>
        </div>
    </div>
    
    <div class="card-body">
        
        <!-- Pindah Info ke Atas: Edukasi User di Awal -->
        <div class="alert alert-info bg-info-lt mb-4" role="alert">
            <div class="d-flex">
                <div>
                    <i class="ti ti-info-circle icon alert-icon text-info me-3 mt-1" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h4 class="alert-title mb-1">Panduan Penggunaan</h4>
                    <div class="text-secondary">
                        Tahun dengan status <strong>Aktif</strong> akan digunakan sebagai referensi utama dalam pembuatan surat dan penomoran. Demi menjaga integritas data, sistem memproteksi tahun yang sedang aktif agar tidak terhapus secara tidak sengaja.
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Dibungkus Border agar lebih rapi -->
        <div class="table-responsive border rounded-3 shadow-sm">
            <table class="table table-vcenter card-table table-hover mb-0">
                <thead class="bg-light-subtle">
                    <tr>
                        <th class="w-1 text-center text-muted">No</th>
                        <th>Tahun Anggaran</th>
                        <th>Status</th>
                        <th class="w-1 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tahun_anggaran_list)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="empty bg-light-subtle rounded m-3 py-5">
                                    <div class="empty-icon text-muted mb-3">
                                        <i class="ti ti-calendar-off" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                    <h4 class="empty-title">Belum Ada Data Tahun</h4>
                                    <p class="empty-subtitle text-muted mb-4">Anda belum memasukkan data tahun anggaran satupun.</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah-tahun">
                                        <i class="ti ti-plus me-2"></i> Tambah Tahun Sekarang
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $no = 1; 
                        $activeYear = $settings['tahun_anggaran'] ?? '';
                        foreach ($tahun_anggaran_list as $row): 
                            $isActive = ($activeYear == $row['tahun']);
                        ?>
                            <!-- Highlight Baris yang Aktif -->
                            <tr class="<?= $isActive ? 'bg-primary-lt' : '' ?>">
                                <td class="text-center text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!-- Avatar Icon membedakan status -->
                                        <div class="avatar avatar-sm me-3 <?= $isActive ? 'bg-primary text-white shadow-sm' : 'bg-light text-secondary' ?>">
                                            <i class="ti ti-calendar-stats fs-3"></i>
                                        </div>
                                        <div>
                                            <strong class="text-reset d-block fs-4"><?= esc($row['tahun']) ?></strong>
                                            <?php if ($isActive): ?>
                                                <small class="text-primary fw-medium">Periode Berjalan</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($isActive): ?>
                                        <span class="badge bg-primary text-white d-inline-flex align-items-center py-1 px-2">
                                            <i class="ti ti-check me-1"></i> Aktif Sekarang
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-lt text-secondary d-inline-flex align-items-center py-1 px-2">
                                            Tersedia
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-list justify-content-center flex-nowrap">
                                        <?php if (!$isActive): ?>
                                            <form action="<?= base_url('pengaturan/aktifkan-tahun-anggaran/' . $row['tahun']) ?>" method="post" class="m-0">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-icon btn-outline-success btn-sm" 
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Gunakan Tahun Ini">
                                                    <i class="ti ti-power fs-3"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <form action="<?= base_url('pengaturan/delete-tahun-anggaran/' . $row['id']) ?>" 
                                              method="post" 
                                              class="m-0"
                                               onsubmit="return confirm('Peringatan: Menghapus tahun <?= esc($row['tahun']) ?> mungkin akan mempengaruhi data yang terkait. Lanjutkan?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" 
                                                    class="btn btn-icon <?= $isActive ? 'btn-ghost-secondary disabled opacity-50' : 'btn-outline-danger' ?> btn-sm" 
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $isActive ? 'Tahun aktif tidak bisa dihapus' : 'Hapus Tahun' ?>"
                                                    <?= $isActive ? 'disabled' : '' ?>>
                                                <i class="ti ti-trash fs-3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Tahun dengan UI yang Diperbarui -->
<div class="modal modal-blur fade" id="modal-tambah-tahun" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <!-- Header Modal Berwarna -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ti ti-calendar-plus me-2 fs-2"></i> Tambah Tahun
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('pengaturan/store-tahun-anggaran') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label required text-center w-100">Masukkan Angka Tahun</label>
                        <!-- Input diperbesar dan dirata-tengah karena hanya 4 digit -->
                        <div class="input-icon mb-2 mt-2">
                            <span class="input-icon-addon">
                                <i class="ti ti-calendar-event text-primary"></i>
                            </span>
                            <input type="number" class="form-control form-control-lg text-center fw-bold fs-2" name="tahun" 
                                   value="<?= old('tahun') ?>"
                                   placeholder="<?= date('Y') + 1 ?>" 
                                   min="2000" max="2100" required autofocus>
                        </div>
                        <small class="form-hint text-center d-block">Gunakan format 4 digit angka (misal: <?= date('Y') ?>).</small>
                    </div>
                </div>
                <div class="modal-footer bg-light-subtle">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="ti ti-device-floppy"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>