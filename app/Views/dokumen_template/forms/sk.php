<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-danger text-white d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title text-white mb-0">
                        <i class="ti ti-gavel me-2"></i> Buat Surat Keputusan (SK) (Word .docx)
                    </h3>
                    <div class="text-white-50 small">Surat Keputusan resmi Kepala Madrasah dengan konsiderans Menimbang, Mengingat, dan Memutuskan.</div>
                </div>
                <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-light btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <form action="<?= base_url('dokumen-template/generate/sk') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">

                    <!-- IDENTITAS SK -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-danger"><i class="ti ti-hash me-1"></i> 1. Identitas Surat Keputusan</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label required mb-0">Nomor SK</label>
                                        <?php if (!empty($latest_nomor_surat) && $latest_nomor_surat !== '-'): ?>
                                            <span class="badge bg-danger-lt text-danger d-inline-flex align-items-center gap-1" style="cursor: pointer;" 
                                                  title="Klik untuk mengisi nomor surat ini"
                                                  onclick="document.getElementById('nomor_surat').value='<?= esc($latest_nomor_surat, 'js') ?>'; document.getElementById('nomor_surat').focus();">
                                                <i class="ti ti-history"></i> Nomor Terakhir: <strong class="ms-1 font-monospace"><?= esc($latest_nomor_surat) ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control font-monospace" required 
                                           value="<?= esc($nextNomor ?: 'B-      /Mi.08.06/PP.00.4/' . date('m/Y')) ?>"
                                           placeholder="Contoh: B-123/Mi.08.06/PP.00.4/09/2026">
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <small class="text-muted">Nomor Surat Keputusan (SK) yang akan dicetak.</small>
                                        <?php if (!empty($latest_nomor_surat) && $latest_nomor_surat !== '-'): ?>
                                            <small>
                                                <a href="javascript:void(0)" class="text-decoration-none text-muted" onclick="document.getElementById('nomor_surat').value='<?= esc($latest_nomor_surat, 'js') ?>'; document.getElementById('nomor_surat').focus();">
                                                    <i class="ti ti-replace me-1"></i>Gunakan nomor terakhir
                                                </a>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Tempat Ditetapkan</label>
                                    <input type="text" name="tempat_surat" class="form-control" required value="Tanggamus">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Tanggal Penetapan</label>
                                    <input type="date" name="tanggal_surat" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label required">Tentang (Judul SK)</label>
                                    <input type="text" name="tentang_sk" class="form-control fw-bold" required 
                                           placeholder="Contoh: PEMBAGIAN TUGAS MENGAJAR DAN TUGAS TAMBAHAN GURU TAHUN PELAJARAN 2026/2027">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KONSIDERANS -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-danger"><i class="ti ti-list-details me-1"></i> 2. Konsiderans (Menimbang & Mengingat)</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Menimbang</label>
                                <textarea name="menimbang" class="form-control" rows="3" required placeholder="a. bahwa untuk kelancaran kegiatan belajar mengajar...&#10;b. bahwa berdasarkan pertimbangan sebagaimana dimaksud pada huruf a..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Mengingat</label>
                                <textarea name="mengingat" class="form-control" rows="4" required placeholder="1. Undang-Undang Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional;&#10;2. Undang-Undang Nomor 14 Tahun 2005 tentang Guru dan Dosen;&#10;3. Peraturan Pemerintah Nomor 19 Tahun 2005 tentang Standar Nasional Pendidikan."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- DIKTUM KEPUTUSAN -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-danger"><i class="ti ti-check me-1"></i> 3. Diktum Keputusan (Memutuskan)</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">KESATU</label>
                                <textarea name="diktum_kesatu" class="form-control" rows="3" required placeholder="Menetapkan pembagian tugas mengajar dan tugas tambahan bagi guru sebagaimana tercantum dalam lampiran keputusan ini."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">KEDUA</label>
                                <textarea name="diktum_kedua" class="form-control" rows="2" required placeholder="Masing-masing guru melaporkan pelaksanaan tugasnya secara berkala kepada Kepala Madrasah."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- PENANDATANGAN -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-danger"><i class="ti ti-user-check me-1"></i> 4. Penandatangan (Kepala Madrasah)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Nama Kepala</label>
                                    <input type="text" name="kepala_nama" class="form-control" required value="<?= esc($settings['pejabat_kepsek_nama'] ?? 'SIPULLOH, M.Pd.I') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">NIP</label>
                                    <input type="text" name="kepala_nip" class="form-control" required value="<?= esc($settings['pejabat_kepsek_nip'] ?? '197005272007011022') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INTEGRASI AGENDA -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="catat_surat_keluar" value="1" id="catatSuratKeluar" checked>
                        <label class="form-check-label fw-bold" for="catatSuratKeluar">
                            Catat secara otomatis ke Buku Agenda Surat Keluar
                        </label>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-danger btn-lg shadow-sm">
                        <i class="ti ti-download me-1"></i> Generate & Download SK (.docx)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
