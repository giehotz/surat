<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-orange text-white d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title text-white mb-0">
                        <i class="ti ti-clock-exclamation me-2"></i> Buat Surat Pemberitahuan Gangguan Absensi (PUSAKA)
                    </h3>
                    <div class="text-white-50 small">Keterangan resmi kendala absensi online pada aplikasi PUSAKA Kementerian Agama RI.</div>
                </div>
                <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-light btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <form action="<?= base_url('dokumen-template/generate/gangguan_absen') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">

                    <!-- IDENTITAS SURAT -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-orange"><i class="ti ti-hash me-1"></i> 1. Identitas Surat Pemberitahuan</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label required mb-0">Nomor Surat</label>
                                        <?php if (!empty($latest_nomor_surat) && $latest_nomor_surat !== '-'): ?>
                                            <span class="badge bg-warning-lt text-warning d-inline-flex align-items-center gap-1" style="cursor: pointer;" 
                                                  title="Klik untuk mengisi nomor surat ini"
                                                  onclick="document.getElementById('nomor_surat').value='<?= esc($latest_nomor_surat, 'js') ?>'; document.getElementById('nomor_surat').focus();">
                                                <i class="ti ti-history"></i> Terakhir: <strong class="ms-1 font-monospace"><?= esc($latest_nomor_surat) ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control font-monospace" required 
                                           value="<?= esc($nextNomor ?: 'B-      /Mi.08.06/KP.01.2/' . date('m/Y')) ?>"
                                           placeholder="Contoh: B-123/Mi.08.06/KP.01.2/09/2026">
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <small class="text-muted">Nomor surat pemberitahuan.</small>
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
                                    <label class="form-label required">Lampiran</label>
                                    <input type="text" name="lampiran" class="form-control" required value="1 (satu) Berkas">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Tempat Surat</label>
                                    <input type="text" name="tempat_surat" class="form-control" required value="Tanggamus">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Tanggal Surat</label>
                                    <input type="date" name="tanggal_surat" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KENDALA GANGGUAN ABSENSI -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-orange"><i class="ti ti-alert-triangle me-1"></i> 2. Rincian Gangguan / Kendala Teknis</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label required">Hari & Tanggal Terjadi Gangguan</label>
                                    <input type="date" name="hari_tanggal_gangguan" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Waktu Kejadian</label>
                                    <input type="text" name="waktu_gangguan" class="form-control" required value="06.30 s.d. 08.00 WIB" placeholder="Contoh: 06.30 s.d. 08.00 WIB">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Status Aplikasi</label>
                                    <input type="text" class="form-control" readonly value="Aplikasi PUSAKA Kemenag RI">
                                </div>
                                <div class="col-12">
                                    <label class="form-label required">Uraian / Keterangan Gangguan</label>
                                    <textarea name="keterangan_gangguan" class="form-control" rows="3" required>Aplikasi PUSAKA mengalami gangguan server down / kendala koneksi HTTP 500 saat pegawai melakukan presensi kehadiran pagi, sehingga seluruh pegawai terkendala melakukan absensi online dan dialihkan menggunakan presensi manual.</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KEPALA MADRASAH -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-orange"><i class="ti ti-user-check me-1"></i> 3. Penandatangan (Kepala Madrasah)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Nama Kepala Madrasah</label>
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
                    <button type="submit" class="btn btn-orange text-white btn-lg shadow-sm">
                        <i class="ti ti-download me-1"></i> Generate & Download Surat Pemberitahuan (.docx)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
