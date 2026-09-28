<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h3 class="card-title fw-bold mb-0">
            <i class="ti ti-archive me-2 text-primary"></i> Arsip Notulen Rapat Tersimpan
        </h3>
        <span class="badge bg-primary-lt"><?= count($riwayat_notulen ?? []) ?> Dokumen Tersimpan</span>
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
            <thead>
                <tr>
                    <th class="w-1">No</th>
                    <th>Tanggal Rapat</th>
                    <th>Judul Agenda Rapat</th>
                    <th>Notulis</th>
                    <th>Metode Notulen</th>
                    <th>Berkas / Naskah</th>
                    <th class="text-center w-1">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($riwayat_notulen)): ?>
                    <?php $no = 1; foreach ($riwayat_notulen as $row): ?>
                        <tr>
                            <td class="text-muted"><?= $no++ ?></td>
                            <td>
                                <span class="badge bg-azure-lt">
                                    <?= format_tanggal_indo($row['tanggal_kegiatan']) ?>
                                </span>
                                <?php if (!empty($row['waktu'])): ?>
                                    <div class="text-muted small mt-1"><?= esc($row['waktu']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?= esc($row['judul_rapat']) ?></div>
                                <?php if (!empty($row['subjudul'])): ?>
                                    <div class="text-muted small"><?= esc($row['subjudul']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($row['tempat'])): ?>
                                    <div class="text-muted small"><i class="ti ti-map-pin me-1"></i><?= esc($row['tempat']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?= esc($row['nama_notulis'] ?: '-') ?></div>
                                <?php if (!empty($row['nip_notulis'])): ?>
                                    <small class="text-muted">NIP. <?= esc($row['nip_notulis']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['metode_notulen'] === 'editor'): ?>
                                    <span class="badge bg-blue-lt">
                                        <i class="ti ti-edit me-1"></i> Teks Editor
                                    </span>
                                <?php elseif ($row['metode_notulen'] === 'upload'): ?>
                                    <span class="badge bg-green-lt">
                                        <i class="ti ti-upload me-1"></i> File Upload
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-lt">
                                        <i class="ti ti-pencil me-1"></i> Tulis Tangan
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['metode_notulen'] === 'editor' && !empty($row['isi_notulen'])): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="lihatNaskahModal(<?= esc(json_encode($row['judul_rapat'])) ?>, <?= esc(json_encode($row['isi_notulen'])) ?>)">
                                        <i class="ti ti-eye me-1"></i> Baca Naskah
                                    </button>
                                <?php elseif ($row['metode_notulen'] === 'upload' && !empty($row['file_lampiran'])): ?>
                                    <a href="<?= base_url('daftar-hadir/download-notulen/' . $row['id']) ?>" class="btn btn-sm btn-outline-success">
                                        <i class="ti ti-download me-1"></i> Unduh Berkas
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Format Fisik Cetak</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <form action="<?= base_url('daftar-hadir/delete-notulen/' . $row['id']) ?>" method="post" id="form-del-<?= $row['id'] ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-icon" onclick="konfirmasiHapus(<?= $row['id'] ?>, '<?= esc(addslashes($row['judul_rapat'])) ?>')" data-bs-toggle="tooltip" title="Hapus Arsip">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ti ti-archive-off fs-1 d-block mb-2 text-secondary"></i>
                            Belum ada arsip notulen rapat yang disimpan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
