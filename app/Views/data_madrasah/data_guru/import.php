<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="page-title">
            Import Data Guru & Pegawai
        </h2>
    </div>
    <div class="col-auto ms-auto">
        <a href="<?= base_url('data-guru') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left icon me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload File Excel</h3>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="card-body pb-0">
                    <div class="alert alert-danger" role="alert">
                        <div class="d-flex">
                            <div><i class="ti ti-alert-triangle icon alert-icon"></i></div>
                            <div><?= session()->getFlashdata('error') ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-title">Petunjuk Import</h4>
                    <div class="text-secondary">
                        <ol class="mb-3">
                            <li>Unduh template Excel yang kami sediakan dengan menekan tombol <strong>Download Template</strong>.</li>
                            <li>Isi data guru sesuai dengan kolom pada template tersebut.</li>
                            <li>Kolom nama pegawai <strong>WAJIB</strong> diisi, sisanya opsional sesuai ketersediaan data.</li>
                            <li>Format khusus tanggal (<strong>Tanggal Lahir, Mulai Tugas & TMT</strong>) pastikan berformat <strong>YYYY-MM-DD</strong> (Contoh: 1980-01-01).</li>
                            <li>Setelah data siap, unggah kembali file tersebut menggunakan form di bawah ini.</li>
                        </ol>
                        <h5 class="mb-2">Daftar Kolom Template:</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered bg-white">
                                <thead>
                                    <tr>
                                        <th>Kolom</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>nama_pegawai</td><td><span class="badge bg-red-lt">Wajib</span> Nama Lengkap & Gelar</td></tr>
                                    <tr><td>nip / peg_id_nuptk</td><td>Identitas Pegawai</td></tr>
                                    <tr><td>tempat_lahir / tanggal_lahir</td><td>Info Kelahiran (Format Tgl: YYYY-MM-DD)</td></tr>
                                    <tr><td>jabatan_mengajar</td><td>Contoh: Guru Kelas / Mapel</td></tr>
                                    <tr><td>pangkat_golongan</td><td>Contoh: IV/a, III/b, dll</td></tr>
                                    <tr><td>pendidikan_terakhir / perguruan_tinggi</td><td>Riwayat Pendidikan</td></tr>
                                    <tr><td>mulai_tugas / tmt_cpns_honorer</td><td>Info TMT (Format Tgl: YYYY-MM-DD)</td></tr>
                                    <tr><td>status_kepegawaian</td><td>PNS / CPNS / Honorer / dll</td></tr>
                                    <tr><td>email / no_handphone</td><td>Informasi Kontak</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <a href="<?= base_url('data-guru/download-template') ?>" class="btn btn-warning">
                        <i class="ti ti-download icon me-2"></i> Download Template Excel
                    </a>
                </div>

                <form action="<?= base_url('data-guru/store-import') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label required">Pilih File Excel (.xlsx / .xls)</label>
                        <input type="file" class="form-control" name="file_excel" accept=".xlsx, .xls" required>
                    </div>

                    <div class="form-footer mt-4 text-center">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-upload icon me-2"></i> Mulai Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>