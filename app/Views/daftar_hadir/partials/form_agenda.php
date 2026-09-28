<!-- Panel Konfigurasi Agenda & Notulen (Hanya Tampil di Layar) -->
<div class="card shadow-sm border-0 rounded-3 mb-4 d-print-none">
    <div class="card-header py-3">
        <h3 class="card-title fw-bold">
            <i class="ti ti-adjustments me-2 text-primary"></i> Pengaturan Informasi Agenda & Notulen
        </h3>
    </div>
    <div class="card-body">
        
        <form id="form-simpan-notulen" action="<?= base_url('daftar-hadir/store-notulen') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Informasi Acara -->
                <div class="col-md-6">
                    <label class="form-label required">Judul Agenda / Rapat</label>
                    <input type="text" name="judul_rapat" id="input-judul" class="form-control" value="<?= old('judul_rapat', 'DAFTAR HADIR RAPAT DEWAN GURU DAN STAF') ?>" placeholder="Contoh: DAFTAR HADIR RAPAT EVALUASI PEMBELAJARAN" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subjudul / Keterangan Tambahan</label>
                    <input type="text" name="subjudul" id="input-subjudul" class="form-control" value="<?= old('subjudul', 'Tahun Pelajaran ' . esc($appSettings['tahun_anggaran'] ?? date('Y'))) ?>" placeholder="Contoh: Awal Semester Genap">
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal_kegiatan" id="input-tanggal" class="form-control" value="<?= old('tanggal_kegiatan', esc($default_tanggal)) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Waktu Pelaksanaan</label>
                    <input type="text" name="waktu" id="input-waktu" class="form-control" value="<?= old('waktu', '08.00 WIB s.d. Selesai') ?>" placeholder="Contoh: 09.00 - 12.00 WIB">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tempat Pelaksanaan</label>
                    <input type="text" name="tempat" id="input-tempat" class="form-control" value="<?= old('tempat', 'Ruang Guru / Aula Madrasah') ?>" placeholder="Contoh: Ruang Rapat Lt. 2">
                </div>

                <!-- Pengaturan Notulen Rapat -->
                <div class="col-12 pt-3 border-top">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-teal">
                                <i class="ti ti-files me-1"></i> Jumlah Lembar Cetak
                            </label>
                            <div class="input-group">
                                <input type="number" name="jumlah_lembar" id="input-jumlah-lembar" class="form-control" min="0" max="10" value="<?= old('jumlah_lembar', 1) ?>">
                                <span class="input-group-text">Lembar A4</span>
                            </div>
                            <small class="text-muted">Isi 0 jika hanya mencetak presensi.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nama Notulis Rapat</label>
                            <input type="text" name="nama_notulis" id="input-nama-notulis" class="form-control" value="<?= old('nama_notulis', '') ?>" placeholder="Contoh: ARIYANI, S.Pd.I">
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">NIP Notulis (Opsional)</label>
                            <input type="text" name="nip_notulis" id="input-nip-notulis" class="form-control" value="<?= old('nip_notulis', '') ?>" placeholder="Contoh: 198801152009032011">
                        </div>

                        <!-- Pilihan Format / Sumber Notulen -->
                        <div class="col-12">
                            <label class="form-label fw-bold mb-2">
                                <i class="ti ti-source_code me-1 text-primary"></i> Pilihan Format Sumber Notulen:
                            </label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-selectgroup-item w-100">
                                        <input type="radio" name="metode_notulen" value="editor" class="form-selectgroup-input" checked onchange="ubahMetodeNotulen('editor')">
                                        <span class="form-selectgroup-label d-flex align-items-center p-3">
                                            <i class="ti ti-edit icon fs-2 text-primary me-2"></i>
                                            <div>
                                                <div class="fw-bold">Ketik di Editor (TinyMCE)</div>
                                                <div class="text-muted small">Input hasil rapat & cetak A4 rapi</div>
                                            </div>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-selectgroup-item w-100">
                                        <input type="radio" name="metode_notulen" value="upload" class="form-selectgroup-input" onchange="ubahMetodeNotulen('upload')">
                                        <span class="form-selectgroup-label d-flex align-items-center p-3">
                                            <i class="ti ti-upload icon fs-2 text-success me-2"></i>
                                            <div>
                                                <div class="fw-bold">Unggah Berkas Dokumen</div>
                                                <div class="text-muted small">Upload file PDF, Word, atau Scan</div>
                                            </div>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-selectgroup-item w-100">
                                        <input type="radio" name="metode_notulen" value="manual" class="form-selectgroup-input" onchange="ubahMetodeNotulen('manual')">
                                        <span class="form-selectgroup-label d-flex align-items-center p-3">
                                            <i class="ti ti-pencil icon fs-2 text-secondary me-2"></i>
                                            <div>
                                                <div class="fw-bold">Tulis Tangan Manual</div>
                                                <div class="text-muted small">Lembar bergaris titik-titik siap cetak</div>
                                            </div>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Box 1: Area Editor TinyMCE -->
                        <div class="col-12" id="box-editor-notulen">
                            <!-- Tombol Melayang Keluar Fullscreen (Tampil saat mode fullscreen aktif) -->
                            <button type="button" id="btn-exit-fullscreen" class="btn btn-danger btn-pill shadow-lg d-print-none align-items-center" style="display: none; position: fixed; top: 16px; right: 24px; z-index: 100005; padding: 9px 20px; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 20px rgba(214, 57, 57, 0.5); cursor: pointer;" onclick="toggleEditorFullscreen()" title="Keluar dari Layar Penuh">
                                <i class="ti ti-x me-1 fs-2"></i> Tutup Layar Penuh (ESC)
                            </button>

                            <div class="p-3 rounded-3 border" style="background-color: var(--tblr-card-header-bg, rgba(var(--tblr-body-color-rgb), 0.025));">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <label class="form-label mb-0 fw-bold">
                                        <i class="ti ti-notes me-1 text-primary"></i> Teks Naskah Notulen Rapat:
                                    </label>
                                    <div class="btn-list">
                                        <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" onclick="muatTemplateNotulen()">
                                            <i class="ti ti-template me-1"></i> Muat Template Standar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm" onclick="toggleEditorFullscreen()">
                                            <i class="ti ti-maximize me-1"></i> Layar Penuh (Fullscreen)
                                        </button>
                                    </div>
                                </div>
                                <textarea name="isi_notulen" id="isi_notulen_editor" class="form-control" rows="8"><?= old('isi_notulen', '') ?></textarea>
                                <small class="text-muted mt-1 d-block">
                                    <i class="ti ti-info-circle me-1"></i> Gunakan tombol <strong>Layar Penuh (Fullscreen)</strong> di toolbar editor atau di atas agar mengetik lebih fokus tanpa gangguan tampilan lain.
                                </small>
                            </div>
                        </div>

                        <!-- Box 2: Area Upload Berkas -->
                        <div class="col-12" id="box-upload-notulen" style="display: none;">
                            <div class="p-4 rounded-3 border" style="background-color: var(--tblr-card-header-bg, rgba(var(--tblr-body-color-rgb), 0.025));">
                                <label class="form-label fw-bold mb-2">
                                    <i class="ti ti-paperclip me-1 text-success"></i> Pilih Berkas Dokumen Notulen / Scan Hasil Rapat:
                                </label>
                                <input type="file" name="file_notulen" id="file_notulen" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text mt-2 text-muted">
                                    Format yang didukung: <strong>PDF, Microsoft Word (.doc, .docx), serta Gambar/Scan (.jpg, .jpeg, .png)</strong>. Ukuran berkas maksimal <strong>5 MB</strong>.
                                </div>
                            </div>
                        </div>

                        <!-- Box 3: Keterangan Mode Manual Tulis Tangan -->
                        <div class="col-12" id="box-manual-notulen" style="display: none;">
                            <div class="alert alert-info mb-0">
                                <i class="ti ti-info-circle me-2"></i>
                                Sistem akan mencetak lembar format bergaris titik-titik (dotted lines) kosong siap tulis tangan saat rapat berlangsung. Data agenda tetap dapat disimpan ke arsip.
                            </div>
                        </div>

                        <!-- Sakelar Cetak Kop & Tanda Tangan -->
                        <div class="col-12 pt-2 border-top">
                            <div class="d-flex flex-wrap gap-4 align-items-center">
                                <label class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="toggle-kop" checked>
                                    <span class="form-check-label fw-medium">Sertakan Kop Madrasah di Daftar Hadir</span>
                                </label>
                                <label class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="toggle-ttd-hadir" checked>
                                    <span class="form-check-label fw-medium">Sertakan Tanda Tangan di Daftar Hadir</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
