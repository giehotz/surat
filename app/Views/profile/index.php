<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="ti ti-user-edit me-2 text-primary fs-2"></i>Edit Profil Saya
                </h3>
            </div>
            <div class="card-status-top bg-primary"></div>

            <form action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row g-4">
                        
                        <!-- KOLOM KIRI: FOTO PROFIL -->
                        <div class="col-md-4">
                            <div class="bg-light-subtle border rounded-3 p-3 text-center h-100 d-flex flex-column justify-content-center align-items-center shadow-sm">
                                <?php if (!empty($user['foto_profile'])): ?>
                                    <div class="mb-3 position-relative">
                                        <img src="<?= base_url('uploads/profiles/' . $user['foto_profile']) ?>" alt="Foto Profil" class="avatar avatar-2xl rounded-circle shadow-sm border border-3 border-white" style="width: 130px; height: 130px; object-fit: cover;">
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3">
                                        <span class="avatar avatar-2xl rounded-circle shadow-sm border border-3 border-white" style="width: 130px; height: 130px; background-image: url('https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap']) ?>&size=150&background=0061A2&color=ffffff')"></span>
                                    </div>
                                <?php endif; ?>

                                <div class="w-100 text-start mt-2">
                                    <label class="form-label fw-medium small mb-2"><i class="ti ti-camera me-1"></i> Ganti Foto (Opsional)</label>
                                    <input type="file" class="form-control form-control-sm" name="foto_profile" accept="image/png, image/jpeg, image/jpg">
                                    <small class="form-hint mt-1" style="font-size: 0.75rem;">Maks 2MB. Format: JPG/PNG.</small>
                                </div>

                                <?php if (!empty($user['foto_profile'])): ?>
                                    <div class="w-100 mt-3 pt-3 border-top">
                                        <button type="button" onclick="hapusFoto()" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-1">
                                            <i class="ti ti-trash"></i> Hapus Foto Saat Ini
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: DATA PRIBADI & KEAMANAN -->
                        <div class="col-md-8">
                            <h4 class="subheader text-muted mb-3">Informasi Akun</h4>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username (Permanen)</label>
                                    <div class="input-icon">
                                        <span class="input-icon-addon"><i class="ti ti-lock text-muted"></i></span>
                                        <input type="text" class="form-control bg-light text-muted fw-bold" value="<?= esc($user['username']) ?>" readonly tabindex="-1" data-bs-toggle="tooltip" title="Username digunakan untuk login dan tidak dapat diubah.">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label required">Nama Lengkap</label>
                                    <div class="input-icon">
                                        <span class="input-icon-addon"><i class="ti ti-user text-muted"></i></span>
                                        <input type="text" class="form-control" name="nama_lengkap" value="<?= esc($user['nama_lengkap']) ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <label class="form-label required">Alamat Email</label>
                                    <div class="input-icon">
                                        <span class="input-icon-addon"><i class="ti ti-mail text-muted"></i></span>
                                        <input type="email" class="form-control" name="email" value="<?= esc($user['email']) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="hr-text my-4">Keamanan Akun</div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Password Baru (Opsional)</label>
                                    <div class="input-icon">
                                        <span class="input-icon-addon"><i class="ti ti-key text-muted"></i></span>
                                        <input type="password" class="form-control" name="password" placeholder="Ketik password baru jika ingin mengubah...">
                                    </div>
                                    <small class="form-hint mt-2 text-warning d-flex align-items-start gap-1">
                                        <i class="ti ti-alert-triangle mt-1"></i> 
                                        <span>Biarkan kolom ini <strong>kosong</strong> jika Anda tidak ingin mengubah password akun Anda saat ini.</span>
                                    </small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="card-footer bg-light-subtle text-end py-3">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 shadow-sm">
                        <i class="ti ti-device-floppy fs-3"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Form for Deleting Photo -->
<form id="formDeletePhoto" action="<?= base_url('profile/delete-photo') ?>" method="post" style="display: none;">
    <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function hapusFoto() {
        Swal.fire({
            title: 'Hapus Foto Profil?',
            text: "Foto profil Anda akan dihapus secara permanen dan dikembalikan ke inisial nama.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="ti ti-trash"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formDeletePhoto').submit();
            }
        })
    }
</script>
<?= $this->endSection() ?>