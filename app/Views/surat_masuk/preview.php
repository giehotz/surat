<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Preview Import Data Surat Masuk</h3>
            </div>

            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-muted">
                        Total Data: <span class="badge bg-blue ms-1"><?= count($importData) ?></span> baris
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No</th>
                            <th>Nomor Surat</th>
                            <th>Pengirim</th>
                            <th>Tanggal Surat</th>
                            <th>Tanggal Terima</th>
                            <th>Perihal</th>
                            <th>Lampiran</th>
                            <th>Metode Penyimpanan</th>
                            <th>Link Cloud</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $validCount = 0;
                        $invalidCount = 0;
                        $no = 1;
                        foreach ($importData as $index => $row) :
                            $isValid = true;
                            $errorMsgs = [];

                            if (empty($row['nomor_surat']) || empty($row['pengirim']) || empty($row['tanggal_surat']) || empty($row['tanggal_terima']) || empty($row['perihal'])) {
                                $isValid = false;
                                $errorMsgs[] = "Kolom wajib ada yang kosong.";
                            }

                            $metode = strtolower($row['tipe_penyimpanan']);
                            if (!in_array($metode, ['lokal', 'cloud'])) {
                                $isValid = false;
                                $errorMsgs[] = "Metode penyimpanan hanya 'lokal' atau 'cloud'.";
                            }

                            if ($metode == 'cloud' && empty($row['file_link'])) {
                                $isValid = false;
                                $errorMsgs[] = "Link cloud harus diisi jika metode cloud.";
                            }

                            if ($isValid) {
                                $validCount++;
                            } else {
                                $invalidCount++;
                            }
                        ?>
                            <tr class="<?= !$isValid ? 'table-danger' : '' ?>">
                                <td><?= $no++ ?></td>
                                <td><?= esc($row['nomor_surat']) ?></td>
                                <td><?= esc($row['pengirim']) ?></td>
                                <td><?= esc($row['tanggal_surat']) ?></td>
                                <td><?= esc($row['tanggal_terima']) ?></td>
                                <td><?= esc($row['perihal']) ?></td>
                                <td><?= esc($row['lampiran']) ?></td>
                                <td><?= esc($row['tipe_penyimpanan']) ?></td>
                                <td class="text-truncate" style="max-width: 150px;"><?= esc($row['file_link']) ?></td>
                                <td>
                                    <?php if ($isValid): ?>
                                        <span class="badge bg-success">Valid</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger" title="<?= implode(', ', $errorMsgs) ?>">Tidak Valid</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex align-items-center">
                <a href="<?= base_url('surat-masuk/import') ?>" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left icon"></i> Kembali
                </a>

                <form action="<?= base_url('surat-masuk/store-import') ?>" method="post" class="ms-auto" id="form-import">
                    <?= csrf_field() ?>
                    <input type="hidden" name="import_data" value="<?= base64_encode(json_encode($importData)) ?>">
                    <button type="submit" class="btn btn-primary" <?= $validCount === 0 ? 'disabled' : '' ?> onclick="return confirm('Proses import akan menyimpan <?= $validCount ?> baris data yang valid. Lanjutkan?')">
                        <i class="ti ti-device-floppy icon"></i> Simpan Data (<?= $validCount ?> Valid)
                    </button>

                    <?php if ($invalidCount > 0): ?>
                        <div class="text-danger mt-2 small text-end">
                            * Terdapat <?= $invalidCount ?> baris data tidak valid yang akan diabaikan.
                        </div>
                    <?php endif; ?>
                </form>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>