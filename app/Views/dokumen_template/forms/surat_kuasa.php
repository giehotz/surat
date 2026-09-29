<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-purple text-white d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title text-white mb-0">
                        <i class="ti ti-certificate me-2"></i> Buat Surat Kuasa (Word .docx)
                    </h3>
                    <div class="text-white-50 small">Pelimpahan wewenang kedinasan dari pihak pertama kepada pihak kedua.</div>
                </div>
                <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-light btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <form action="<?= base_url('dokumen-template/generate/surat_kuasa') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">

                    <!-- IDENTITAS SURAT -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-purple"><i class="ti ti-hash me-1"></i> 1. Identitas Surat Kuasa</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label required mb-0">Nomor Surat Kuasa</label>
                                        <?php if (!empty($latest_nomor_surat) && $latest_nomor_surat !== '-'): ?>
                                            <span class="badge bg-purple-lt text-purple d-inline-flex align-items-center gap-1" style="cursor: pointer;" 
                                                  title="Klik untuk mengisi nomor surat ini"
                                                  onclick="document.getElementById('nomor_surat').value='<?= esc($latest_nomor_surat, 'js') ?>'; document.getElementById('nomor_surat').focus();">
                                                <i class="ti ti-history"></i> Nomor Terakhir: <strong class="ms-1 font-monospace"><?= esc($latest_nomor_surat) ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control font-monospace" required 
                                           value="<?= esc($nextNomor ?: 'B-      /Mi.08.06/KP.01.2/' . date('m/Y')) ?>"
                                           placeholder="Contoh: B-123/Mi.08.06/KP.01.2/09/2026">
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <small class="text-muted">Nomor surat kuasa yang akan dicetak pada dokumen.</small>
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
                                    <label class="form-label required">Tanggal</label>
                                    <input type="date" name="tanggal_surat" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PIHAK PERTAMA (PEMBERI KUASA) -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-purple"><i class="ti ti-user-check me-1"></i> 2. Pihak Pertama (Pemberi Kuasa)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label required">Nama Lengkap</label>
                                    <input type="text" name="pemberi_nama" class="form-control" required value="<?= esc($settings['pejabat_kepsek_nama'] ?? 'SIPULLOH, M.Pd.I') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">NIP</label>
                                    <input type="text" name="pemberi_nip" class="form-control" required value="<?= esc($settings['pejabat_kepsek_nip'] ?? '197005272007011022') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Jabatan</label>
                                    <input type="text" name="pemberi_jabatan" class="form-control" required value="Kepala Madrasah">
                                </div>
                                <div class="col-12">
                                    <label class="form-label required">Alamat Instansi / Rumah</label>
                                    <input type="text" name="pemberi_alamat" class="form-control" required value="<?= esc($settings['sekolah_alamat'] ?? 'MIN 2 Tanggamus, Jl. Lintas Barat No. 12') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PIHAK KEDUA (PENERIMA KUASA) -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-purple"><i class="ti ti-user-plus me-1"></i> 3. Pihak Kedua (Penerima Kuasa)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Pilih dari Master Pegawai (Otomatis Isi)</label>
                                    <select class="form-select select2-guru" onchange="pilihPenerima(this)">
                                        <option value="">-- Pilih Guru/Pegawai --</option>
                                        <?php foreach ($gurus as $g): ?>
                                            <option value="<?= esc($g['id']) ?>"
                                                    data-nama="<?= esc($g['nama_pegawai']) ?>"
                                                    data-nip="<?= esc($g['nip'] ?? '-') ?>"
                                                    data-jabatan="<?= esc($g['jabatan_mengajar'] ?? 'Guru') ?>">
                                                <?= esc($g['nama_pegawai']) ?> (NIP: <?= esc($g['nip'] ?? '-') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Nama Penerima Kuasa</label>
                                    <input type="text" name="penerima_nama" id="kuasaNama" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">NIP / NIK</label>
                                    <input type="text" name="penerima_nip" id="kuasaNip" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Jabatan</label>
                                    <input type="text" name="penerima_jabatan" id="kuasaJabatan" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label required">Alamat Penerima Kuasa</label>
                                    <input type="text" name="penerima_alamat" class="form-control" required value="Kabupaten Tanggamus">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KEPERLUAN KUASA -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-purple"><i class="ti ti-file-text me-1"></i> 4. Uraian Kuasa / Wewenang</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Wewenang / Keperluan Yang Dikuasakan</label>
                                <textarea name="keperluan_kuasa" class="form-control" rows="4" required 
                                          placeholder="Contoh: Mengambil berkas pencairan dana Bantuan Operasional Sekolah (BOS) Tahap I Tahun Anggaran 2026 pada Bank Mandiri KCP Pringsewu, serta menandatangani dokumen dan bukti penerimaan yang bersangkutan."></textarea>
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
                    <button type="submit" class="btn btn-purple text-white btn-lg shadow-sm">
                        <i class="ti ti-download me-1"></i> Generate & Download Surat Kuasa (.docx)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function pilihPenerima(elem) {
    const opt = elem.options[elem.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('kuasaNama').value = opt.getAttribute('data-nama') || '';
        document.getElementById('kuasaNip').value = opt.getAttribute('data-nip') || '';
        document.getElementById('kuasaJabatan').value = opt.getAttribute('data-jabatan') || '';
    }
}
</script>

<?= $this->endSection() ?>
