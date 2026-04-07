<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Preview Import Data Prestasi Siswa</h3>
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
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th>Jenis Prestasi</th>
                            <th>Tingkat</th>
                            <th>Nama Lomba</th>
                            <th>Peringkat</th>
                            <th>Keterangan</th>
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

                            // Basic validation simulation for preview display
                            if (empty($row['tanggal']) || empty($row['nama_siswa']) || empty($row['nisn']) || empty($row['jenis_prestasi']) || empty($row['tingkat']) || empty($row['nama_lomba']) || empty($row['peringkat'])) {
                                $isValid = false;
                                $errorMsgs[] = "Data wajib ada yang kosong.";
                            }
                            if (!in_array($row['jenis_prestasi'], ['Akademik', 'Non Akademik'])) {
                                $isValid = false;
                                $errorMsgs[] = "Jenis Prestasi tidak valid.";
                            }
                            if (!in_array($row['tingkat'], ['Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'])) {
                                $isValid = false;
                                $errorMsgs[] = "Tingkat tidak valid.";
                            }

                            if ($isValid) {
                                $validCount++;
                            } else {
                                $invalidCount++;
                            }
                        ?>
                            <tr class="<?= !$isValid ? 'table-danger' : '' ?>">
                                <td><?= $no++ ?></td>
                                <td><?= esc($row['tanggal']) ?></td>
                                <td><?= esc($row['nama_siswa']) ?></td>
                                <td><?= esc($row['nisn']) ?></td>
                                <td><?= esc($row['jenis_prestasi']) ?></td>
                                <td><?= esc($row['tingkat']) ?></td>
                                <td><?= esc($row['nama_lomba']) ?></td>
                                <td><?= esc($row['peringkat']) ?></td>
                                <td><?= esc($row['keterangan']) ?></td>
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
                <a href="<?= base_url('prestasi-siswa/import') ?>" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left icon"></i> Kembali
                </a>

                <form action="<?= base_url('prestasi-siswa/store-import') ?>" method="post" class="ms-auto" id="form-import">
                    <?= csrf_field() ?>
                    <!-- Passing data as base64 encoded JSON -->
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