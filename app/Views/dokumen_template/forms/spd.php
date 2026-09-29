<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title text-white mb-0">
                        <i class="ti ti-car me-2"></i> Buat Surat Perjalanan Dinas (SPD)
                    </h3>
                    <div class="text-white-50 small">Dokumen resmi rincian perjalanan dinas dinas luar / kegiatan madrasah.</div>
                </div>
                <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-light btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <form action="<?= base_url('dokumen-template/generate/spd') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">

                    <!-- IDENTITAS DOKUMEN -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-success"><i class="ti ti-hash me-1"></i> 1. Identitas Dokumen SPD</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label required mb-0">Nomor Surat SPD</label>
                                        <?php if (!empty($latest_nomor_surat) && $latest_nomor_surat !== '-'): ?>
                                            <span class="badge bg-success-lt text-success d-inline-flex align-items-center gap-1" style="cursor: pointer;" 
                                                  title="Klik untuk mengisi nomor surat ini"
                                                  onclick="document.getElementById('nomor_surat').value='<?= esc($latest_nomor_surat, 'js') ?>'; document.getElementById('nomor_surat').focus();">
                                                <i class="ti ti-history"></i> Nomor Terakhir: <strong class="ms-1 font-monospace"><?= esc($latest_nomor_surat) ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control font-monospace" required 
                                           value="<?= esc($nextNomor ?: 'B-      /Mi.08.06/KU.01.1/' . date('m/Y')) ?>"
                                           placeholder="Contoh: B-123/Mi.08.06/KU.01.1/09/2026">
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <small class="text-muted">Nomor surat SPD yang akan dicetak pada dokumen.</small>
                                        <?php if (!empty($latest_nomor_surat) && $latest_nomor_surat !== '-'): ?>
                                            <small>
                                                <a href="javascript:void(0)" class="text-decoration-none text-muted" onclick="document.getElementById('nomor_surat').value='<?= esc($latest_nomor_surat, 'js') ?>'; document.getElementById('nomor_surat').focus();">
                                                    <i class="ti ti-replace me-1"></i>Gunakan nomor terakhir
                                                </a>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Tempat Dikeluarkan</label>
                                    <input type="text" name="tempat_surat" class="form-control" required value="Tanggamus">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Tanggal SPD</label>
                                    <input type="date" name="tanggal_surat" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PEGAWAI YANG DIPERINTAHKAN -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-success"><i class="ti ti-user me-1"></i> 2. Pegawai Yang Melaksanakan Perjalanan Dinas</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Pilih Pegawai dari Master Guru (Otomatis Isi)</label>
                                    <select class="form-select select2-guru" id="selectSpdGuru" onchange="pilihSpdGuru(this)">
                                        <option value="">-- Pilih Pegawai --</option>
                                        <?php foreach ($gurus as $g): ?>
                                            <option value="<?= esc($g['id']) ?>"
                                                    data-nama="<?= esc($g['nama_pegawai']) ?>"
                                                    data-nip="<?= esc($g['nip'] ?? '-') ?>"
                                                    data-pangkat="<?= esc($g['pangkat_golongan'] ?? '-') ?>"
                                                    data-jabatan="<?= esc($g['jabatan_mengajar'] ?? 'Guru') ?>">
                                                <?= esc($g['nama_pegawai']) ?> (NIP: <?= esc($g['nip'] ?? '-') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Nama Pegawai</label>
                                    <input type="text" name="pegawai_nama" id="spdNama" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">NIP</label>
                                    <input type="text" name="pegawai_nip" id="spdNip" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Pangkat / Golongan</label>
                                    <input type="text" name="pegawai_pangkat" id="spdPangkat" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Jabatan</label>
                                    <input type="text" name="pegawai_jabatan" id="spdJabatan" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RINCIAN PERJALANAN DINAS -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-success"><i class="ti ti-map-pin me-1"></i> 3. Rincian Perjalanan & Transportasi</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label required">Maksud Perjalanan Dinas</label>
                                    <textarea name="maksud_perjalanan" class="form-control" rows="2" required 
                                              placeholder="Contoh: Menghadiri Rapat Koordinasi dan Verifikasi Berkas TPG pada Kantor Wilayah Kemenag."></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Tingkat Biaya</label>
                                    <select name="tingkat_biaya" class="form-select">
                                        <option value="Tingkat D / Standar">Tingkat D / Standar</option>
                                        <option value="Tingkat C">Tingkat C</option>
                                        <option value="Tingkat B">Tingkat B</option>
                                        <option value="Tingkat A">Tingkat A</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Alat Angkutan</label>
                                    <input type="text" name="alat_angkutan" class="form-control" required value="Kendaraan Darat / Kendaraan Dinas">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Lama Perjalanan (Hari)</label>
                                    <input type="number" name="lama_perjalanan" id="spdLama" class="form-control" required value="1" min="1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Tempat Berangkat</label>
                                    <input type="text" name="tempat_berangkat" class="form-control" required value="MIN 2 Tanggamus">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Tempat Tujuan</label>
                                    <input type="text" name="tempat_tujuan" class="form-control" required placeholder="Contoh: Bandar Lampung">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Tanggal Berangkat</label>
                                    <input type="date" name="tanggal_berangkat" id="spdBerangkat" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Tanggal Harus Kembali</label>
                                    <input type="date" name="tanggal_kembali" id="spdKembali" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Pengikut / Peserta Lainnya (Jika Ada)</label>
                                    <input type="text" name="daftar_pengikut" class="form-control" placeholder="Contoh: 1. Ahmad (Guru), 2. Siti (Staf) - Kosongkan jika tidak ada" value="-">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PEMBEBANAN ANGGARAN & PEJABAT -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-success"><i class="ti ti-cash me-1"></i> 4. Pembebanan Anggaran & Pejabat PPK</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Instansi Anggaran</label>
                                    <input type="text" name="instansi_anggaran" class="form-control" required value="DIPA MIN 2 Tanggamus">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Akun / Mata Anggaran</label>
                                    <input type="text" name="mata_anggaran" class="form-control" value="524111 (Belanja Perjalanan Dinas Biasa)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Pejabat Pembuat Komitmen / Kepala Madrasah</label>
                                    <input type="text" name="kepala_nama" class="form-control" required value="<?= esc($settings['pejabat_kepsek_nama'] ?? 'SIPULLOH, M.Pd.I') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">NIP Pejabat</label>
                                    <input type="text" name="kepala_nip" class="form-control" required value="<?= esc($settings['pejabat_kepsek_nip'] ?? '197005272007011022') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keterangan Lain-Lain</label>
                                    <input type="text" name="keterangan_lain" class="form-control" value="-">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INTEGRASI AGENDA -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="catat_surat_keluar" value="1" id="catatSuratKeluar" checked>
                        <label class="form-check-label fw-bold" for="catatSuratKeluar">
                            Catat secara otomatis ke Buku Agenda Surat Keluar
                        </label>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-success btn-lg shadow-sm">
                        <i class="ti ti-download me-1"></i> Generate & Download SPD (.docx)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function pilihSpdGuru(elem) {
    const opt = elem.options[elem.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('spdNama').value = opt.getAttribute('data-nama') || '';
        document.getElementById('spdNip').value = opt.getAttribute('data-nip') || '';
        document.getElementById('spdPangkat').value = opt.getAttribute('data-pangkat') || '';
        document.getElementById('spdJabatan').value = opt.getAttribute('data-jabatan') || '';
    }
}

// Auto hitung lama perjalanan
const tgl1 = document.getElementById('spdBerangkat');
const tgl2 = document.getElementById('spdKembali');
const durasi = document.getElementById('spdLama');

function hitungDurasi() {
    if (tgl1.value && tgl2.value) {
        const d1 = new Date(tgl1.value);
        const d2 = new Date(tgl2.value);
        const diffTime = d2 - d1;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        if (diffDays >= 1) {
            durasi.value = diffDays;
        }
    }
}
tgl1.addEventListener('change', hitungDurasi);
tgl2.addEventListener('change', hitungDurasi);
</script>

<?= $this->endSection() ?>
