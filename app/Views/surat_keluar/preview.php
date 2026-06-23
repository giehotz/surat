<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="page-header">
    <h1>Preview Import Data Surat Keluar</h1>
</div>

<div class="card">
    <div class="card-body">
        <p>Berikut adalah pratinjau data yang akan diimpor:</p>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No Surat</th>
                        <th>Tujuan</th>
                        <th>Tgl Surat</th>
                        <th>Tgl Kirim</th>
                        <th>Perihal</th>
                        <th>Lampiran</th>
                        <th>Tipe Penyimpanan</th>
                        <th>Link Cloud</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($importData as $row): ?>
                    <tr>
                        <td><?= esc($row['nomor_surat']); ?></td>
                        <td><?= esc($row['tujuan']); ?></td>
                        <td><?= esc($row['tanggal_surat']); ?></td>
                        <td><?= esc($row['tanggal_kirim']); ?></td>
                        <td><?= esc($row['perihal']); ?></td>
                        <td><?= esc($row['lampiran']); ?></td>
                        <td><?= esc($row['tipe_penyimpanan']); ?></td>
                        <td><?= esc($row['file_link']); ?></td>
                        <td><?= esc($row['keterangan']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <form action="<?= base_url('surat-keluar/store-import'); ?>" method="post">
            <?= csrf_field(); ?>
            <p class="text-muted small">Data akan disimpan sebanyak <strong><?= count($importData) ?></strong> entri.</p>
            
            <button type="submit" class="btn btn-success">
                <i class="ti ti-check"></i> Simpan Semua Data
            </button>
            <a href="<?= base_url('surat-keluar/import'); ?>" class="btn btn-warning">
                <i class="ti ti-refresh"></i> Ulangi Import
            </a>
            <a href="<?= base_url('surat-keluar'); ?>" class="btn btn-link">Kembali ke Daftar</a>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>