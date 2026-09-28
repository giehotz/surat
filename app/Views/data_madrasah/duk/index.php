<!-- Toolbar & Filter Cakupan DUK -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
    <!-- Filter Cakupan Pegawai -->
    <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="text-secondary small fw-medium me-1">
            <i class="ti ti-filter icon me-1"></i> Cakupan DUK:
        </span>
        <div class="btn-group" role="group" aria-label="Cakupan DUK">
            <a href="<?= base_url('data-madrasah?tab=duk&duk_scope=pns') ?>" 
               class="btn btn-sm <?= ($selected_duk_scope ?? 'pns') === 'pns' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                <i class="ti ti-user-check icon me-1"></i> PNS Saja (Standar BKN)
            </a>
            <a href="<?= base_url('data-madrasah?tab=duk&duk_scope=asn') ?>" 
               class="btn btn-sm <?= ($selected_duk_scope ?? 'pns') === 'asn' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                <i class="ti ti-users-group icon me-1"></i> ASN (PNS + PPPK)
            </a>
            <a href="<?= base_url('data-madrasah?tab=duk&duk_scope=all') ?>" 
               class="btn btn-sm <?= ($selected_duk_scope ?? 'pns') === 'all' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                <i class="ti ti-briefcase icon me-1"></i> Semua Pegawai
            </a>
        </div>
    </div>

    <!-- Tombol Aksi: Cetak Resmi & Ekspor Excel -->
    <div class="btn-list">
        <a href="<?= base_url('data-madrasah/duk/cetak?duk_scope=' . ($selected_duk_scope ?? 'pns')) ?>" 
           target="_blank" 
           class="btn btn-outline-primary"
           data-bs-toggle="tooltip" 
           title="Buka format cetak resmi landscape dengan Kop Madrasah dan tanda tangan Kepala Madrasah">
            <i class="ti ti-printer icon me-1"></i> Cetak Lembar DUK
        </a>
        <a href="<?= base_url('data-madrasah/duk/export-excel?duk_scope=' . ($selected_duk_scope ?? 'pns')) ?>" 
           class="btn btn-outline-success"
           data-bs-toggle="tooltip" 
           title="Unduh data DUK terformat rapi dalam file spreadsheet Excel (.xlsx)">
            <i class="ti ti-file-spreadsheet icon me-1"></i> Ekspor Excel
        </a>
    </div>
</div>

<!-- Hierarchy Rule Insight Card -->
<div class="card bg-blue-lt border-0 rounded-3 mb-3">
    <div class="card-body p-3">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-md bg-blue text-white rounded-3 me-3 flex-shrink-0">
                <i class="ti ti-stairs-up fs-2"></i>
            </div>
            <div class="flex-fill">
                <div class="fw-bold text-blue mb-1">
                    Aturan Baku Daftar Urut Kepangkatan (DUK) Madrasah
                </div>
                <div class="text-secondary small">
                    <span class="badge bg-yellow text-yellow-fg me-1">1</span> <strong>Kepala Madrasah</strong> berada di urutan teratas DUK
                    <span class="mx-1">•</span>
                    <strong>PNS</strong> diurutkan berdasarkan Pangkat/Golongan (IV/e s.d I/a) &amp; Masa Kerja
                    <span class="mx-1">•</span>
                    <strong>PPPK</strong> diurutkan dari Golongan tertinggi (XVII s.d I) &amp; Masa Kerja
                    <span class="mx-1">•</span>
                    <strong>Honorer</strong> diurutkan berdasarkan TMT pengangkatan paling awal/lama.
                </div>
            </div>
            <div class="d-none d-lg-block text-end flex-shrink-0 ms-3">
                <div class="h2 mb-0 font-tabular fw-bold text-blue"><?= count($duk ?? []) ?></div>
                <div class="text-secondary small">Pegawai Terdaftar</div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel DUK -->
<div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap table-hover datatable-duk">
        <thead>
            <tr>
                <th class="w-1 text-center">No. DUK</th>
                <th>Nama Pegawai &amp; NIP / NUPTK</th>
                <th class="text-center">Golongan / Ruang</th>
                <th class="text-center">TMT Golongan</th>
                <th>Jabatan</th>
                <th class="text-center">Masa Kerja</th>
                <th>Pendidikan Terakhir</th>
                <th class="text-center">Usia</th>
                <th class="text-center">TMT Awal</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($duk)) : ?>
                <?php
                $colors = ['bg-blue-lt', 'bg-azure-lt', 'bg-indigo-lt', 'bg-purple-lt', 'bg-pink-lt', 'bg-red-lt', 'bg-orange-lt', 'bg-yellow-lt', 'bg-lime-lt', 'bg-green-lt', 'bg-teal-lt', 'bg-cyan-lt'];
                foreach ($duk as $item) :
                    $no = (int) $item['no_urut_duk'];
                    $nama = (string) esc($item['nama_pegawai']);
                    $inisial = strtoupper(substr($nama, 0, 1) . (strpos($nama, ' ') !== false ? substr(explode(' ', $nama)[1], 0, 1) : ''));
                    $bgColor = $colors[ord(strtoupper($nama[0] ?? 'A')) % count($colors)];
                    $status = strtolower($item['status_kepegawaian'] ?? '');
                    $isKamad = ($item['duk_tier'] ?? 0) === 4000;
                ?>
                    <tr class="<?= $isKamad ? 'table-warning-lt fw-medium' : '' ?>">
                        <!-- No. DUK dengan Peringkat Podium -->
                        <td class="text-center">
                            <?php if ($no === 1) : ?>
                                <span class="badge bg-yellow text-yellow-fg px-2 py-1 rounded-pill" data-bs-toggle="tooltip" title="Peringkat 1 DUK (Kepala Madrasah)">
                                    <i class="ti ti-crown icon me-1"></i> #1
                                </span>
                            <?php elseif ($no === 2) : ?>
                                <span class="badge bg-secondary-lt text-secondary px-2 py-1 rounded-pill fw-bold" data-bs-toggle="tooltip" title="Peringkat 2 DUK">
                                    <i class="ti ti-medal icon me-1"></i> #2
                                </span>
                            <?php elseif ($no === 3) : ?>
                                <span class="badge bg-amber-lt text-amber px-2 py-1 rounded-pill fw-bold" data-bs-toggle="tooltip" title="Peringkat 3 DUK">
                                    <i class="ti ti-medal-2 icon me-1"></i> #3
                                </span>
                            <?php else : ?>
                                <span class="badge bg-transparent text-secondary border font-tabular px-2 py-1 rounded-pill">
                                    <?= $no ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Profil Pegawai -->
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm me-3 <?= $bgColor ?> text-uppercase fw-bold"><?= $inisial ?></span>
                                <div>
                                    <div class="fw-bold">
                                        <a href="<?= base_url('data-guru/berkas/' . $item['id']) ?>" class="text-reset text-decoration-none" data-bs-toggle="tooltip" title="Buka berkas dokumen pegawai">
                                            <?= $nama ?>
                                        </a>
                                        <?php if ($isKamad) : ?>
                                            <span class="badge bg-primary text-primary-fg ms-1" style="font-size: 0.65rem;">KAMAD</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-secondary small font-monospace mt-1" style="font-size: 0.8rem;">
                                        <?php if (!empty($item['nip'])) : ?>
                                            <span>NIP: <?= esc($item['nip']) ?></span>
                                        <?php elseif (!empty($item['peg_id_nuptk'])) : ?>
                                            <span>NUPTK: <?= esc($item['peg_id_nuptk']) ?></span>
                                        <?php else : ?>
                                            <span class="text-muted">NIP/NUPTK: -</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Golongan / Ruang -->
                        <td class="text-center">
                            <?php if (!empty($item['duk_label_golongan']) && $item['duk_label_golongan'] !== '-') : ?>
                                <span class="badge bg-blue-lt font-monospace px-2 py-1 fw-bold">
                                    <?= esc($item['duk_label_golongan']) ?>
                                </span>
                            <?php else : ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>

                        <!-- TMT Golongan -->
                        <td class="text-center font-tabular small">
                            <?php if (!empty($item['duk_tmt_pangkat']) && $item['duk_tmt_pangkat'] !== '2099-12-31') : ?>
                                <span><?= format_tanggal_indo($item['duk_tmt_pangkat']) ?></span>
                            <?php else : ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>

                        <!-- Jabatan Mengajar -->
                        <td>
                            <span class="text-body fw-medium"><?= esc($item['jabatan_mengajar'] ?? '-') ?></span>
                        </td>

                        <!-- Masa Kerja (Realtime) -->
                        <td class="text-center">
                            <span class="badge bg-teal-lt font-tabular px-2 py-1 fw-medium">
                                <i class="ti ti-clock icon me-1"></i>
                                <?= esc($item['duk_masa_kerja_format']) ?>
                            </span>
                        </td>

                        <!-- Pendidikan Terakhir -->
                        <td>
                            <div>
                                <span class="badge bg-indigo-lt fw-semibold"><?= esc($item['pendidikan_terakhir'] ?? '-') ?></span>
                                <?php if (!empty($item['perguruan_tinggi'])) : ?>
                                    <div class="text-secondary small text-truncate mt-1" style="max-width: 180px;" title="<?= esc($item['perguruan_tinggi']) ?>">
                                        <?= esc($item['perguruan_tinggi']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Usia -->
                        <td class="text-center font-tabular small">
                            <span class="text-secondary fw-medium"><?= esc($item['duk_usia_format']) ?></span>
                        </td>

                        <!-- TMT Awal / Pengangkatan -->
                        <td class="text-center font-tabular small text-secondary">
                            <?php if (!empty($item['duk_tmt_pengangkatan']) && $item['duk_tmt_pengangkatan'] !== '2099-12-31') : ?>
                                <span><?= format_tanggal_indo($item['duk_tmt_pengangkatan']) ?></span>
                            <?php else : ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>

                        <!-- Status Kepegawaian -->
                        <td class="text-center">
                            <?php if ($status === 'pns') : ?>
                                <span class="badge bg-success-lt px-2 py-1 fw-bold">PNS</span>
                            <?php elseif ($status === 'pppk') : ?>
                                <span class="badge bg-azure-lt px-2 py-1 fw-bold">PPPK</span>
                            <?php else : ?>
                                <span class="badge bg-secondary-lt px-2 py-1">Honorer</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <div class="empty">
                            <div class="empty-icon text-secondary">
                                <i class="ti ti-users-minus fs-1"></i>
                            </div>
                            <p class="empty-title">Tidak ada data pegawai</p>
                            <p class="empty-subtitle text-secondary">
                                Tidak ditemukan data pegawai untuk cakupan yang dipilih (<?= esc($selected_duk_scope ?? 'pns') ?>).
                            </p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
