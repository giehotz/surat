<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title text-white mb-0">
                        <i class="ti ti-briefcase me-2"></i> Buat Surat Tugas (Word .docx)
                    </h3>
                    <div class="text-white-50 small">Dokumen resmi kedinasan perorangan atau rombongan pegawai/guru.</div>
                </div>
                <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-light btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <form action="<?= base_url('dokumen-template/generate/surat_tugas') ?>" method="post" id="formSuratTugas">
                <?= csrf_field() ?>
                <div class="card-body">
                    
                    <!-- 1. IDENTITAS SURAT -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-primary"><i class="ti ti-hash me-1"></i> 1. Identitas Surat</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="form-label required mb-0">Nomor Surat</label>
                                        <?php if (!empty($latest_nomor_surat) && $latest_nomor_surat !== '-'): ?>
                                            <span class="badge bg-primary-lt text-primary d-inline-flex align-items-center gap-1" style="cursor: pointer;" 
                                                  title="Klik untuk mengisi nomor surat ini"
                                                  onclick="document.getElementById('nomor_surat').value='<?= esc($latest_nomor_surat, 'js') ?>'; document.getElementById('nomor_surat').focus();">
                                                <i class="ti ti-history"></i> Nomor Terakhir: <strong class="ms-1 font-monospace"><?= esc($latest_nomor_surat) ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control font-monospace" required 
                                           value="<?= esc($nextNomor ?: 'B-      /Mi.08.06/KP.02.3/' . date('m/Y')) ?>" 
                                           placeholder="Contoh: B-123/Mi.08.06/KP.02.3/09/2026">
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <small class="text-muted">Nomor surat resmi yang akan dicetak pada dokumen.</small>
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
                                    <label class="form-label required">Tempat Surat</label>
                                    <input type="text" name="tempat_surat" class="form-control" required value="Tanggamus">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Tanggal Surat</label>
                                    <input type="date" name="tanggal_surat" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. PEJABAT PEMBERI TUGAS -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-primary"><i class="ti ti-user-check me-1"></i> 2. Pejabat Yang Bertanda Tangan (Pemberi Tugas)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label required">Nama Kepala / Pejabat</label>
                                    <input type="text" name="kepala_nama" class="form-control" required 
                                           value="<?= esc($settings['pejabat_kepsek_nama'] ?? 'SIPULLOH, M.Pd.I') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">NIP</label>
                                    <input type="text" name="kepala_nip" class="form-control" required 
                                           value="<?= esc($settings['pejabat_kepsek_nip'] ?? '197005272007011022') ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Jabatan</label>
                                    <input type="text" name="kepala_jabatan" class="form-control" required value="Kepala Madrasah">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Pangkat / Gol.</label>
                                    <input type="text" name="kepala_pangkat" class="form-control" required value="Pembina, IV/a">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. DAFTAR PEGAWAI YANG DITUGASKAN (MULTI-PEGAWAI) -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2 d-flex align-items-center justify-content-between">
                            <strong class="text-primary"><i class="ti ti-users me-1"></i> 3. Pegawai / Guru Yang Ditugaskan (Bisa Lebih Dari 1 Orang)</strong>
                            <button type="button" class="btn btn-sm btn-outline-success" id="btnAddPegawai">
                                <i class="ti ti-plus me-1"></i> Tambah Pegawai
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-vcenter mb-0" id="tablePegawai">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;" class="text-center">No</th>
                                            <th style="width: 250px;">Pilih Dari Master Guru</th>
                                            <th>Nama Lengkap</th>
                                            <th style="width: 180px;">NIP</th>
                                            <th style="width: 180px;">Pangkat / Gol.</th>
                                            <th style="width: 180px;">Jabatan</th>
                                            <th style="width: 60px;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pegawaiContainer">
                                        <!-- Row 1 Default -->
                                        <tr class="pegawai-row" data-index="0">
                                            <td class="text-center fw-bold row-number">1</td>
                                            <td>
                                                <select class="form-select select-guru" onchange="pilihGuru(this)">
                                                    <option value="">-- Pilih Pegawai --</option>
                                                    <?php foreach ($gurus as $g): ?>
                                                        <option value="<?= esc($g['id']) ?>" 
                                                                data-nama="<?= esc($g['nama_pegawai']) ?>"
                                                                data-nip="<?= esc($g['nip'] ?? '-') ?>"
                                                                data-pangkat="<?= esc($g['pangkat_golongan'] ?? '-') ?>"
                                                                data-jabatan="<?= esc($g['jabatan_mengajar'] ?? 'Guru') ?>">
                                                            <?= esc($g['nama_pegawai']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="pegawai[0][nama]" class="form-control field-nama" required placeholder="Nama Lengkap">
                                            </td>
                                            <td>
                                                <input type="text" name="pegawai[0][nip]" class="form-control field-nip" placeholder="NIP">
                                            </td>
                                            <td>
                                                <input type="text" name="pegawai[0][pangkat]" class="form-control field-pangkat" placeholder="Pangkat/Golongan">
                                            </td>
                                            <td>
                                                <input type="text" name="pegawai[0][jabatan]" class="form-control field-jabatan" placeholder="Jabatan">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-icon btn-sm btn-outline-danger btn-remove-row" disabled>
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 4. RINCIAN TUGAS & KEGIATAN -->
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong class="text-primary"><i class="ti ti-calendar-event me-1"></i> 4. Rincian Pelaksanaan Tugas</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label required">Keperluan / Uraian Tugas</label>
                                    <textarea name="keperluan" class="form-control" rows="3" required 
                                              placeholder="Contoh: Mengikuti Rapat Koordinasi dan Bimbingan Teknis Implementasi Kurikulum Merdeka pada Kantor Kementerian Agama Kabupaten Tanggamus."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Tempat / Lokasi Tujuan</label>
                                    <input type="text" name="tempat_tujuan" class="form-control" required 
                                           placeholder="Contoh: Aula Kantor Kemenag Kabupaten Tanggamus">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Hari & Tanggal Kegiatan</label>
                                    <input type="text" name="tanggal_kegiatan" class="form-control" required 
                                           placeholder="Contoh: Senin s.d. Selasa, 06 s.d. 07 Oktober 2026">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. OPSI INTEGRASI AGENDA SURAT KELUAR -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="catat_surat_keluar" value="1" id="catatSuratKeluar" checked>
                        <label class="form-check-label fw-bold" for="catatSuratKeluar">
                            Catat secara otomatis ke Buku Agenda Surat Keluar
                        </label>
                        <div class="text-muted small">Jika dicentang, nomor surat, perihal, dan tanggal akan tersimpan ke database arsip surat keluar.</div>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        <i class="ti ti-download me-1"></i> Generate & Download Word (.docx)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let rowIndex = 1;

function pilihGuru(selectElem) {
    const selectedOption = selectElem.options[selectElem.selectedIndex];
    const row = selectElem.closest('tr');
    
    if (selectedOption && selectedOption.value) {
        row.querySelector('.field-nama').value = selectedOption.getAttribute('data-nama') || '';
        row.querySelector('.field-nip').value = selectedOption.getAttribute('data-nip') || '';
        row.querySelector('.field-pangkat').value = selectedOption.getAttribute('data-pangkat') || '';
        row.querySelector('.field-jabatan').value = selectedOption.getAttribute('data-jabatan') || '';
    }
}

document.getElementById('btnAddPegawai').addEventListener('click', function() {
    const container = document.getElementById('pegawaiContainer');
    const firstRow = container.querySelector('.pegawai-row');
    const newRow = firstRow.cloneNode(true);
    
    newRow.setAttribute('data-index', rowIndex);
    
    // Reset values
    newRow.querySelector('.select-guru').selectedIndex = 0;
    const inputs = newRow.querySelectorAll('input');
    inputs.forEach(input => {
        const fieldName = input.className.match(/field-([a-z]+)/);
        if (fieldName) {
            input.name = `pegawai[${rowIndex}][${fieldName[1]}]`;
        }
        input.value = '';
    });
    
    // Enable remove button
    const removeBtn = newRow.querySelector('.btn-remove-row');
    removeBtn.disabled = false;
    removeBtn.onclick = function() {
        newRow.remove();
        updateRowNumbers();
    };
    
    container.appendChild(newRow);
    rowIndex++;
    updateRowNumbers();
});

function updateRowNumbers() {
    const rows = document.querySelectorAll('#pegawaiContainer .pegawai-row');
    rows.forEach((row, idx) => {
        row.querySelector('.row-number').innerText = idx + 1;
        const removeBtn = row.querySelector('.btn-remove-row');
        if (rows.length === 1) {
            removeBtn.disabled = true;
        } else {
            removeBtn.disabled = false;
            removeBtn.onclick = function() {
                row.remove();
                updateRowNumbers();
            };
        }
    });
}
</script>

<?= $this->endSection() ?>
