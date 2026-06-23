<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-xl">
    <div class="page-header d-print-none mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Import Data Surat Keluar
                </h2>
                <div class="text-muted mt-1">Unggah berkas Excel untuk memperbarui database secara massal.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Unggah Berkas</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div><i class="ti ti-alert-circle icon me-2"></i></div>
                                <div><?= session()->getFlashdata('error'); ?></div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="form-label">1. Unduh Template</label>
                        <p class="text-muted small">Gunakan format standar agar sistem dapat membaca data dengan benar.</p>
                        <a href="<?= base_url('surat-keluar/download-template'); ?>" class="btn btn-outline-primary">
                            <i class="ti ti-download me-2"></i> Download Template Excel
                        </a>
                    </div>

                    <hr class="my-4">

                    <form action="<?= base_url('surat-keluar/preview'); ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="mb-4">
                            <label class="form-label">2. Pilih File Excel</label>
                            <input type="file" class="form-control" name="file_excel" accept=".xls,.xlsx" required>
                            <small class="form-hint">Format yang didukung: <strong>.xls</strong> atau <strong>.xlsx</strong></small>
                        </div>

                        <div class="form-footer d-flex align-items-center">
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-upload me-2"></i> Upload & Preview
                            </button>
                            <a href="<?= base_url('surat-keluar'); ?>" class="btn btn-link ms-auto text-muted">
                                Batal & Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-none bg-transparent">
                <div class="card-body">
                    <h4 class="card-title text-uppercase text-muted fw-bold">Petunjuk Pengisian</h4>
                    <ul class="list-unstyled space-y-2">
                        <li class="d-flex mb-2">
                            <i class="ti ti-check text-success me-2 mt-1"></i>
                            <span>Kolom wajib diisi, kecuali <strong>Lampiran</strong>, <strong>Link Cloud</strong>, dan <strong>Keterangan</strong>.</span>
                        </li>
                        <li class="d-flex mb-2">
                            <i class="ti ti-calendar text-primary me-2 mt-1"></i>
                            <span>Format Tanggal: <code class="bg-light px-1">DD/MM/YYYY</code> (contoh: 31/12/2024).</span>
                        </li>
                        <li class="d-flex mb-2">
                            <i class="ti ti-database text-info me-2 mt-1"></i>
                            <span>Tipe Penyimpanan: Isi dengan <span class="badge bg-blue-lt">lokal</span> atau <span class="badge bg-purple-lt">cloud</span>.</span>
                        </li>
                        <li class="d-flex">
                            <i class="ti ti-link text-warning me-2 mt-1"></i>
                            <span>Jika tipe <strong>cloud</strong>, kolom link cloud opsional (bisa diisi nanti).</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>