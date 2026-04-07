<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">Buat Disposisi Surat Masuk</h3>
            </div>
            <div class="card-status-top bg-info"></div>

            <form action="<?= base_url('disposisi/store') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="surat_masuk_id" value="<?= esc($surat_masuk_id ?? '') ?>">

                <div class="card-body">
                    <!-- Tampilan Ringkas Surat Terkait -->
                    <div class="alert alert-info bg-azure-lt" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-info-circle icon alert-icon"></i>
                            </div>
                            <div>
                                <h4 class="alert-title">Informasi Surat</h4>
                                <div class="text-secondary">Anda sedang membuat instruksi disposisi untuk Surat Masuk pencatatan <strong><?= esc($surat['nomor_agenda']) ?></strong> perihal <em><?= esc($surat['perihal']) ?></em>.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 mt-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required">Tujuan / Penerima Disposisi</label>
                            <select class="form-select" name="ke_user_id" id="ke_user_id" required>
                                <option value="">--- Pilih Penerima ---</option>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= esc($u['nama_lengkap']) ?> - <?= esc($u['jabatan']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Tenggat Waktu Penyelesaian</label>
                            <input type="date" class="form-control" name="tenggat_waktu" id="tenggat_waktu" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Isi Instruksi / Catatan Disposisi</label>
                        <textarea class="form-control" name="instruksi" id="instruksi" rows="4" placeholder="Tuliskan instruksi yang harus dilakukan..." required></textarea>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="<?= base_url('surat-masuk') ?>" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-info"><i class="ti ti-send icon"></i> Kirim Disposisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>