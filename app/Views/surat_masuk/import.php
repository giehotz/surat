<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row row-cards justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Import Data Surat Masuk</h3>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible m-3" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="ti ti-alert-circle icon alert-icon"></i>
                        </div>
                        <div>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>

            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label">1. Download Template Excel</label>
                    <p class="text-muted">Silahkan download template excel berikut dan isi data surat masuk sesuai format yang telah ditentukan.</p>
                    <a href="<?= base_url('surat-masuk/download-template') ?>" class="btn btn-outline-success">
                        <i class="ti ti-download icon"></i> Download Template
                    </a>
                </div>

                <hr>

                <form action="<?= base_url('surat-masuk/preview') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label required">2. Upload File Excel</label>
                        <p class="text-muted mb-2">Pilih file excel (.xlsx, .xls) yang telah diisi dengan data surat masuk.</p>
                        <input type="file" class="form-control" name="file_excel" accept=".xlsx, .xls" required>
                    </div>

                    <div class="form-footer">
                        <a href="<?= base_url('surat-masuk') ?>" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="ti ti-eye icon"></i>
                            Preview Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>