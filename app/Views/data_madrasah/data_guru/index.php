<!-- Toolbar Data Guru -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div class="text-secondary small">
        Kelola profil pendidik, tenaga kependidikan, riwayat jabatan, dan masa kerja.
    </div>
    <div class="btn-list">
        <a href="<?= base_url('data-guru/import') ?>" class="btn btn-outline-success">
            <i class="ti ti-file-import icon me-1"></i> Import Excel
        </a>
        <a href="<?= base_url('data-guru/create') ?>" class="btn btn-primary">
            <i class="ti ti-plus icon me-1"></i> Tambah Pegawai
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap table-hover datatable-guru">
        <thead>
            <tr>
                <th class="w-1">No</th>
                <th>Profil Pegawai</th>
                <th>Status & Jabatan</th>
                <th>Pendidikan</th>
                <th>Masa Kerja</th>
                <th>Estimasi Pensiun</th>
                <th>Kontak</th>
                <th class="w-1 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data_guru)) : ?>
                <?php
                $i = 1;
                $colors = ['bg-blue-lt', 'bg-azure-lt', 'bg-indigo-lt', 'bg-purple-lt', 'bg-pink-lt', 'bg-red-lt', 'bg-orange-lt', 'bg-yellow-lt', 'bg-lime-lt', 'bg-green-lt', 'bg-teal-lt', 'bg-cyan-lt'];
                foreach ($data_guru as $guru) :
                    $nama = (string)esc($guru['nama_pegawai']);
                    $inisial = strtoupper(substr($nama, 0, 1) . (strpos($nama, ' ') !== false ? substr(explode(' ', $nama)[1], 0, 1) : ''));
                    $bgColor = $colors[ord(strtoupper($nama[0] ?? 'A')) % count($colors)];
                    $status = strtolower($guru['status_kepegawaian'] ?? '');
                ?>
                    <tr>
                        <td class="text-secondary"><?= $i++ ?></td>

                        <!-- Profil Pegawai -->
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm me-3 <?= $bgColor ?> text-uppercase fw-bold"><?= $inisial ?></span>
                                <div>
                                    <div class="fw-bold">
                                        <a href="<?= base_url('data-guru/berkas/' . $guru['id']) ?>" class="text-reset text-decoration-none" data-bs-toggle="tooltip" title="Buka berkas dokumen">
                                            <?= $nama ?>
                                        </a>
                                    </div>
                                    <div class="text-secondary small mt-1 font-monospace" style="font-size: 0.8rem;">
                                        <?php if (!empty($guru['nip'])) : ?>
                                            <span>NIP: <?= esc($guru['nip']) ?></span>
                                        <?php elseif (!empty($guru['peg_id_nuptk'])) : ?>
                                            <span>NUPTK: <?= esc($guru['peg_id_nuptk']) ?></span>
                                        <?php else : ?>
                                            <span class="text-muted">NIP/NUPTK: -</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Status & Jabatan -->
                        <td>
                            <div>
                                <?php if ($status === 'pns') : ?>
                                    <span class="badge bg-green-lt text-green fw-semibold mb-1">PNS</span>
                                <?php elseif ($status === 'pppk') : ?>
                                    <span class="badge bg-purple-lt text-purple fw-semibold mb-1">PPPK</span>
                                <?php elseif (in_array($status, ['honorer', 'gtt', 'ptt'])) : ?>
                                    <span class="badge bg-orange-lt text-orange fw-semibold mb-1">Honorer</span>
                                <?php else : ?>
                                    <span class="badge bg-secondary-lt fw-medium mb-1"><?= esc($guru['status_kepegawaian'] ?: 'Non-PNS') ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="text-secondary small">
                                <?= esc($guru['jabatan_mengajar'] ?: 'Tenaga Pendidik') ?>
                                <?php if (!empty($guru['pangkat_golongan'])) : ?>
                                    <span class="text-muted">• <?= esc($guru['pangkat_golongan']) ?></span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Pendidikan -->
                        <td>
                            <?php if (!empty($guru['pendidikan_terakhir'])) : ?>
                                <div class="fw-medium"><?= esc($guru['pendidikan_terakhir']) ?></div>
                                <div class="text-secondary small text-truncate" style="max-width: 220px;" data-bs-toggle="tooltip" title="<?= esc($guru['perguruan_tinggi'] ?: '-') ?>">
                                    <?= esc($guru['perguruan_tinggi'] ?: '-') ?>
                                </div>
                            <?php else : ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>

                        <!-- Masa Kerja -->
                        <td>
                            <div class="small">
                                <div class="fw-medium">
                                    <i class="ti ti-briefcase text-secondary me-1"></i><?= hitung_masa_kerja($guru['mulai_tugas']) ?>
                                </div>
                                <?php if (!empty($guru['tmt_cpns_honorer'])) : ?>
                                    <div class="text-secondary" style="font-size: 0.75rem;">
                                        TMT: <?= date('d/m/Y', strtotime($guru['tmt_cpns_honorer'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Estimasi Pensiun -->
                        <td>
                            <div class="small">
                                <div class="text-blue fw-medium">
                                    <?= hitung_tanggal_pensiun($guru['tanggal_lahir']) ?>
                                </div>
                                <div class="text-secondary" style="font-size: 0.75rem;">Batas Usia 60 Thn</div>
                            </div>
                        </td>

                        <!-- Kontak -->
                        <td>
                            <div class="small">
                                <?php if (!empty($guru['no_handphone'])) : ?>
                                    <div><i class="ti ti-phone text-secondary me-1"></i> <?= esc($guru['no_handphone']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($guru['email'])) : ?>
                                    <div class="text-secondary text-truncate" style="max-width: 200px;" title="<?= esc($guru['email']) ?>">
                                        <i class="ti ti-mail text-secondary me-1"></i> <?= esc($guru['email']) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (empty($guru['no_handphone']) && empty($guru['email'])) : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Aksi -->
                        <td class="text-center">
                            <div class="btn-list flex-nowrap justify-content-center">
                                <a href="<?= base_url('data-guru/berkas/' . $guru['id']) ?>" class="btn btn-icon btn-sm btn-ghost-info" data-bs-toggle="tooltip" title="Berkas Dokumen">
                                    <i class="ti ti-folder icon"></i>
                                </a>
                                <a href="<?= base_url('data-guru/edit/' . $guru['id']) ?>" class="btn btn-icon btn-sm btn-ghost-primary" data-bs-toggle="tooltip" title="Edit Pegawai">
                                    <i class="ti ti-edit icon"></i>
                                </a>
                                <form action="<?= base_url('data-guru/delete/' . $guru['id']) ?>" method="post" class="d-inline form-delete-guru">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn btn-icon btn-sm btn-ghost-danger btn-delete-item" data-title="Hapus Pegawai <?= $nama ?>?" data-text="Data pegawai dan riwayatnya akan dihapus." data-bs-toggle="tooltip" title="Hapus Data">
                                        <i class="ti ti-trash icon"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8" class="p-0">
                        <div class="empty py-5">
                            <div class="empty-icon">
                                <i class="ti ti-users-minus text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <p class="empty-title">Belum ada data pegawai</p>
                            <p class="empty-subtitle text-muted">
                                Data guru dan tenaga kependidikan belum ditambahkan ke dalam sistem.
                            </p>
                            <div class="empty-action">
                                <a href="<?= base_url('data-guru/create') ?>" class="btn btn-primary">
                                    <i class="ti ti-plus icon me-1"></i> Tambah Pegawai Pertama
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>