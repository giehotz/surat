<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title text-white mb-0">
                        <i class="ti ti-upload me-2"></i> Upload Template Dokumen Word (.docx)
                    </h3>
                    <div class="text-white-50 small">Sistem akan secara otomatis memindai tag placeholder ${...} di dalam dokumen.</div>
                </div>
                <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-light btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Batal
                </a>
            </div>

            <form action="<?= base_url('dokumen-template/store-upload') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger mb-3">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label required">Nama Template Dokumen</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Surat Rekomendasi Siswa Berprestasi" value="<?= old('nama') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Jelaskan tujuan dan fungsi dokumen ini..."><?= old('deskripsi') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">File Template (.docx)</label>
                        <input type="file" name="file_docx" class="form-control" accept=".docx" required>
                        <small class="text-muted d-block mt-1">
                            Hanya format <strong>.docx</strong> (Microsoft Word) dengan ukuran maksimal 10 MB.
                        </small>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm mt-3">
                        <h4 class="alert-title"><i class="ti ti-info-circle me-1"></i> Format Kop Surat & Tag Placeholder Resmi Aplikasi:</h4>
                        <div class="text-secondary small">
                            Dokumen Word Anda dapat memanfaatkan tag otomatis yang sinkron langsung dengan <strong>Pengaturan Kop Madrasah</strong> di aplikasi ini:
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <strong class="text-dark">Tag Kop Madrasah (Otomatis Diisi Sistem):</strong>
                                    <ul class="mb-2 mt-1 ps-3">
                                        <li><code>${kop_kementerian}</code> : Baris 1 (Kementerian Agama RI)</li>
                                        <li><code>${kop_kantor_kementerian}</code> : Baris 2 (Kantor Kemenag Kab)</li>
                                        <li><code>${kop_nama}</code> : Baris 3 (Nama MIN 2 Tanggamus)</li>
                                        <li><code>${kop_alamat}</code> : Alamat lengkap instansi</li>
                                        <li><code>${kop_kontak}</code> : Email / Telp instansi</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-dark">Tag Pejabat & Surat (Otomatis/Form):</strong>
                                    <ul class="mb-2 mt-1 ps-3">
                                        <li><code>${nomor_surat}</code> : Nomor surat resmi</li>
                                        <li><code>${tempat_surat}</code> : Tempat surat (Tanggamus)</li>
                                        <li><code>${tanggal_surat}</code> : Tanggal surat (Format Indo)</li>
                                        <li><code>${kepala_nama}</code> : Nama Kepala Madrasah</li>
                                        <li><code>${kepala_nip}</code> : NIP Kepala Madrasah</li>
                                    </ul>
                                </div>
                            </div>
                            <em>Catatan: Gunakan format kurung kurawal dolar <code>${nama_tag}</code> atau <code>{{nama_tag}}</code>. Tag kustom lainnya akan otomatis dijadikan field isian pada form.</em>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="ti ti-scan me-1"></i> Upload & Pindai Placeholder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
