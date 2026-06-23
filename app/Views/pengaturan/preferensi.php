<div class="tab-pane <?= ($active_tab ?? '') == 'preferensi' ? 'active show' : '' ?>" id="tab-preferensi">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="ti ti-settings me-2 text-primary"></i>Preferensi Sistem Utama
        </h3>
    </div>
    <div class="card-body">
        
        <!-- Peringatan Konteks di Atas -->
        <div class="alert alert-info bg-info-lt mb-4" role="alert">
            <div class="d-flex">
                <div>
                    <i class="ti ti-info-circle icon alert-icon text-info me-3 mt-1" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h4 class="alert-title mb-1">Dampak Preferensi</h4>
                    <div class="text-secondary">
                        Pengaturan di bawah ini akan mengubah cara sistem beroperasi secara keseluruhan (Global). Pastikan Anda menyesuaikannya dengan standar operasional instansi Anda.
                    </div>
                </div>
            </div>
        </div>

        <form action="<?= base_url('pengaturan/update-preferensi') ?>" method="post">
            <?= csrf_field() ?>

            <!-- BAGIAN 1: Identitas -->
            <h4 class="subheader text-muted mb-3">Identitas Visual</h4>
            <div class="row mb-5">
                <div class="col-md-8">
                    <label class="form-label required">Nama / Singkatan Aplikasi</label>
                    <div class="input-group input-group-flat shadow-sm">
                        <span class="input-group-text">
                            <i class="ti ti-typography"></i>
                        </span>
                        <input type="text" class="form-control ps-1" name="app_nama" value="<?= esc($settings['app_nama'] ?? '') ?>" placeholder="Misal: E-Surat V2" required>
                    </div>
                    <small class="form-hint mt-2">Nama ini akan mendominasi teks logo sudut kiri atas sistem dan <em>title browser</em> Anda.</small>
                </div>
            </div>

            <!-- BAGIAN 2: Data & Konfigurasi -->
            <h4 class="subheader text-muted mb-3">Konfigurasi Data & Penyimpanan</h4>
            <div class="row g-4 mb-4">
                
                <div class="col-md-6">
                    <label class="form-label">Tahun Anggaran Aktif</label>
                    <div class="input-group input-group-flat shadow-sm">
                        <span class="input-group-text">
                            <i class="ti ti-calendar-event"></i>
                        </span>
                        <select class="form-select ps-1" name="tahun_anggaran">
                            <option value="">-- Tampilkan Semua Tahun --</option>
                            <?php 
                            $selectedYear = $settings['tahun_anggaran'] ?? '';
                            foreach ($tahun_anggaran_list as $row): 
                            ?>
                                <option value="<?= $row['tahun'] ?>" <?= $selectedYear == $row['tahun'] ? 'selected' : '' ?>><?= $row['tahun'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <small class="form-hint mt-2">
                        <i class="ti ti-filter me-1"></i>Jika dipilih, maka data Surat Masuk, Surat Keluar, dan Disposisi hanya akan memunculkan data pada tahun tersebut.
                    </small>
                </div>

                <?php
                $metodeLampiran = (!empty($settings['metode_lampiran'])) ? explode(',', $settings['metode_lampiran']) : [];
                ?>
                <div class="col-12 mt-4">
                    <label class="form-label mb-3">Metode Penyimpanan Lampiran Surat</label>
                    
                    <div class="row g-3">
                        <!-- Opsi 1: Upload Lokal -->
                        <div class="col-md-6">
                            <label class="form-check form-switch card card-body p-3 mb-0 h-100 shadow-sm" for="metode_upload" style="cursor: pointer;">
                                <div class="d-flex align-items-center w-100">
                                    <div class="bg-primary-lt rounded p-2 me-3">
                                        <i class="ti ti-server text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-check-label fw-bold d-block fs-4">Server Lokal</div>
                                        <div class="text-muted small mt-1">Upload file langsung dan simpan di dalam server aplikasi ini.</div>
                                    </div>
                                    <div class="ms-3">
                                        <input class="form-check-input m-0" type="checkbox" role="switch" id="metode_upload" name="metode_lampiran[]" value="upload" style="transform: scale(1.2);" <?= in_array('upload', $metodeLampiran) ? 'checked' : '' ?>>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Opsi 2: Link Cloud -->
                        <div class="col-md-6">
                            <label class="form-check form-switch card card-body p-3 mb-0 h-100 shadow-sm" for="metode_link" style="cursor: pointer;">
                                <div class="d-flex align-items-center w-100">
                                    <div class="bg-success-lt rounded p-2 me-3">
                                        <i class="ti ti-cloud-link text-success" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-check-label fw-bold d-block fs-4">Tautan Cloud</div>
                                        <div class="text-muted small mt-1">Menyimpan surat menggunakan link (Google Drive, OneDrive, dll).</div>
                                    </div>
                                    <div class="ms-3">
                                        <input class="form-check-input m-0" type="checkbox" role="switch" id="metode_link" name="metode_lampiran[]" value="link" style="transform: scale(1.2);" <?= in_array('link', $metodeLampiran) ? 'checked' : '' ?>>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <small class="form-hint mt-3">
                        <i class="ti ti-bulb me-1"></i>Anda dapat mengaktifkan salah satu atau keduanya sekaligus. Pilihan ini akan menentukan form input apa yang muncul saat staf menambahkan lampiran surat.
                    </small>
                </div>

                <!-- BAGIAN 3: Link Folder Google Drive -->
                <div class="col-12 mt-4">
                    <label class="form-label mb-2">
                        <i class="ti ti-brand-google-drive me-1 text-warning"></i>Link Folder Google Drive (Opsional)
                    </label>
                    <div class="card card-body p-3 shadow-sm">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-warning-lt rounded p-2 flex-shrink-0">
                                <i class="ti ti-folder-link text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="input-group input-group-flat">
                                    <span class="input-group-text">
                                        <i class="ti ti-link"></i>
                                    </span>
                                    <input type="url" class="form-control ps-1" 
                                           name="link_folder_drive" 
                                           value="<?= esc($settings['link_folder_drive'] ?? '') ?>" 
                                           placeholder="https://drive.google.com/drive/folders/xxxxxxx">
                                </div>
                                <small class="form-hint mt-2">
                                    <i class="ti ti-info-circle me-1"></i>Jika diisi, tombol <strong>"Buka Folder Drive"</strong> akan muncul di form Surat Masuk & Surat Keluar, 
                                    sehingga staf bisa langsung membuka folder bersama tanpa harus mencari-cari link secara manual.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Garis Pembatas Aksi -->
            <div class="hr-text mt-5 mb-4">Pastikan data sudah sesuai</div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="ti ti-device-floppy fs-3"></i>
                    Simpan Preferensi
                </button>
            </div>
        </form>
    </div>
</div>