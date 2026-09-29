<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-cyan text-white d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title text-white mb-0">
                        <i class="ti ti-file-text me-2"></i> Form <?= esc($template['nama']) ?>
                    </h3>
                    <div class="text-white-50 small"><?= esc($template['deskripsi'] ?? 'Template dokumen kustom.') ?></div>
                </div>
                <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-light btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <form action="<?= base_url('dokumen-template/generate/' . $template['kode']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">

                    <!-- IDENTITAS DASAR DOKUMEN -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-cyan"><i class="ti ti-hash me-1"></i> Identitas Pokok Dokumen</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label required mb-0">Nomor Surat</label>
                                        <?php if (!empty($latest_nomor_surat) && $latest_nomor_surat !== '-'): ?>
                                            <span class="badge bg-cyan-lt text-cyan d-inline-flex align-items-center gap-1" style="cursor: pointer;" 
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
                                        <small class="text-muted">Nomor surat resmi yang akan dicetak pada dokumen.</small>
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
                                    <label class="form-label required">Tempat Surat</label>
                                    <input type="text" name="tempat_surat" class="form-control" required value="Tanggamus">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Tanggal Surat</label>
                                    <input type="date" name="tanggal_surat" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DYNAMIC FIELDS -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-cyan"><i class="ti ti-forms me-1"></i> Isian Khusus Template (<?= count($fields) ?> Field)</strong>
                        </div>
                        <div class="card-body">
                            <?php if (empty($fields)): ?>
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-circle me-1"></i> Belum ada field yang dikonfigurasi untuk template ini.
                                    <a href="<?= base_url('dokumen-template/configure-fields/' . $template['id']) ?>" class="alert-link">Klik di sini untuk mengonfigurasi field.</a>
                                </div>
                            <?php else: ?>
                                <div class="row g-3">
                                    <?php foreach ($fields as $f): ?>
                                        <?php 
                                        $req = $f['is_required'] ? 'required' : ''; 
                                        $col = ($f['field_type'] === 'textarea') ? 'col-12' : 'col-md-6';
                                        ?>
                                        <div class="<?= $col ?>">
                                            <label class="form-label <?= $f['is_required'] ? 'required' : '' ?>">
                                                <?= esc($f['field_label']) ?>
                                                <small class="text-muted font-monospace">(${<?= esc($f['field_key']) ?>})</small>
                                            </label>

                                            <?php if ($f['field_type'] === 'textarea'): ?>
                                                <textarea name="<?= esc($f['field_key']) ?>" class="form-control" rows="3" <?= $req ?>></textarea>

                                            <?php elseif ($f['field_type'] === 'date'): ?>
                                                <input type="date" name="<?= esc($f['field_key']) ?>" class="form-control" <?= $req ?> value="<?= date('Y-m-d') ?>">

                                            <?php elseif ($f['field_type'] === 'number'): ?>
                                                <input type="number" name="<?= esc($f['field_key']) ?>" class="form-control" <?= $req ?>>

                                            <?php elseif ($f['field_type'] === 'guru_select'): ?>
                                                <select name="<?= esc($f['field_key']) ?>" class="form-select select2" <?= $req ?>>
                                                    <option value="">-- Pilih Guru / Pegawai --</option>
                                                    <?php foreach ($gurus as $g): ?>
                                                        <option value="<?= esc($g['nama_pegawai']) ?> (NIP. <?= esc($g['nip'] ?? '-') ?>)">
                                                            <?= esc($g['nama_pegawai']) ?> (<?= esc($g['nip'] ?? '-') ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                            <?php else: ?>
                                                <input type="text" name="<?= esc($f['field_key']) ?>" class="form-control" <?= $req ?>>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- PENANDATANGAN -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-cyan"><i class="ti ti-user-check me-1"></i> Penandatangan (Kepala Madrasah)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label required">Nama Kepala</label>
                                    <input type="text" name="kepala_nama" class="form-control" required value="<?= esc($settings['pejabat_kepsek_nama'] ?? 'SIPULLOH, M.Pd.I') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">NIP</label>
                                    <input type="text" name="kepala_nip" class="form-control" required value="<?= esc($settings['pejabat_kepsek_nip'] ?? '197005272007011022') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Jabatan</label>
                                    <input type="text" name="kepala_jabatan" class="form-control" required value="Kepala Madrasah">
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
                    <button type="submit" class="btn btn-cyan text-white btn-lg shadow-sm">
                        <i class="ti ti-download me-1"></i> Generate & Download Dokumen (.docx)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
