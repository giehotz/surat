<div class="tab-pane <?= ($active_tab ?? '') == 'identitas' ? 'active show' : '' ?>" id="tab-identitas">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="ti ti-building-bank me-2 text-primary"></i>Profil & Logo Institusi
        </h3>
    </div>
    
    <div class="card-body">
        <form action="<?= base_url('pengaturan/update-identitas') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- BAGIAN 1: LOGO -->
            <h4 class="subheader text-muted mb-3">Logo Resmi</h4>
            <div class="row mb-5">
                <div class="col-12">
                    <div class="d-flex align-items-center p-3 border rounded bg-light-subtle">
                        <div class="me-4 position-relative">
                            <span class="avatar avatar-xl rounded-circle shadow-sm" id="logo-preview" style="width: 100px; height: 100px; background-image: url('<?= !empty($settings['sekolah_logo']) ? base_url('uploads/logo/' . $settings['sekolah_logo']) : 'https://ui-avatars.com/api/?name=' . urlencode('Nama Sekolah') . '&color=ffffff&background=0061A2' ?>')"></span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="mb-2">
                                <label class="form-label d-inline-block mb-0">Pilih Logo Baru</label>
                            </div>
                            <div class="input-group mb-2">
                                <input type="file" class="form-control" name="sekolah_logo" accept="image/png, image/jpeg, image/jpg" id="logo-upload" onchange="previewImage(this)">
                                <?php if (!empty($settings['sekolah_logo'])) : ?>
                                    <a href="<?= base_url('pengaturan/delete-logo') ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus logo ini? Logo akan diganti dengan inisial.');" data-bs-toggle="tooltip" title="Hapus Logo Saat Ini">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <small class="form-hint text-muted"><i class="ti ti-info-circle me-1"></i>Disarankan format PNG transparan atau JPG. Rasio 1:1. Maksimal 2MB.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: INFORMASI DASAR -->
            <h4 class="subheader text-muted mb-3">Informasi Utama</h4>
            <div class="row g-4 mb-5">
                <div class="col-md-12">
                    <label class="form-label">Kementerian / Yayasan <span class="text-muted fw-normal">(Opsional)</span></label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text">
                            <i class="ti ti-building-estate"></i>
                        </span>
                        <input type="text" class="form-control ps-1" name="sekolah_kementerian" value="<?= esc($settings['sekolah_kementerian'] ?? '') ?>" placeholder="Contoh: KEMENTERIAN AGAMA REPUBLIK INDONESIA">
                    </div>
                    <small class="form-hint mt-1"><i class="ti ti-info-circle me-1"></i>Jika diisi, teks ini akan muncul di atas nama institusi pada kop surat cetak PDF.</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label required">Nama Institusi / Sekolah</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text">
                            <i class="ti ti-school"></i>
                        </span>
                        <input type="text" class="form-control ps-1" name="sekolah_nama" value="<?= esc($settings['sekolah_nama'] ?? '') ?>" placeholder="Masukkan nama resmi institusi..." required>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-6">
                    <label class="form-label">NPSN / NSS</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text">
                            <i class="ti ti-id"></i>
                        </span>
                        <input type="text" class="form-control ps-1" name="sekolah_npsn" value="<?= esc($settings['sekolah_npsn'] ?? '') ?>" placeholder="Nomor Pokok Sekolah Nasional">
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-6">
                    <label class="form-label">NSM <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Nomor Statistik Madrasah">?</span></label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text">
                            <i class="ti ti-hash"></i>
                        </span>
                        <input type="text" class="form-control ps-1" name="sekolah_nsm" value="<?= esc($settings['sekolah_nsm'] ?? '') ?>" placeholder="Nomor Statistik Madrasah (opsional)">
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3: ALAMAT & KONTAK -->
            <h4 class="subheader text-muted mb-3">Alamat & Kontak</h4>
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <label class="form-label required">Alamat Lengkap</label>
                    <textarea class="form-control" name="sekolah_alamat" rows="3" placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota..." required><?= esc($settings['sekolah_alamat'] ?? '') ?></textarea>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Media Kontak</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text">
                            <i class="ti ti-address-book"></i>
                        </span>
                        <input type="text" class="form-control ps-1" name="sekolah_kontak" placeholder="Cth: 021-1234567 | info@sekolah.sch.id | www.sekolah.sch.id" value="<?= esc($settings['sekolah_kontak'] ?? '') ?>">
                    </div>
                    <small class="form-hint mt-2">Gabungkan nomor telepon, email, atau website. Pisahkan dengan tanda garis lurus (|).</small>
                </div>
            </div>

            <!-- ACTION BUTTON -->
            <div class="hr-text mt-4 mb-4">Pastikan data sudah benar</div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="ti ti-device-floppy"></i>
                    Simpan Perubahan Identitas
                </button>
            </div>
        </form>
    </div>
</div>