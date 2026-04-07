<?= $this->extend('buku_tamu/publik/layout') ?>

<?= $this->section('content') ?>
<div class="container-tight py-4">
    <div class="empty">
        <div class="empty-icon">
            <i class="ti ti-clock-off text-muted" style="font-size: 5rem;"></i>
        </div>
        <p class="empty-title">Layanan Buku Tamu Tutup</p>
        <p class="empty-subtitle text-muted">
            <?= esc($message) ?>
        </p>
        <div class="empty-action">
            <a href="<?= base_url('/') ?>" class="btn btn-primary">
                <i class="ti ti-arrow-left icon me-2"></i> Kembali ke Dashboard Utama
            </a>
        </div>
        
        <div class="mt-5 border-top pt-4">
            <h4 class="text-muted fw-medium mb-3">Jadwal Operasional Resmi:</h4>
            <div class="row justify-content-center">
                <div class="col-auto">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <i class="ti ti-calendar me-1 text-primary"></i> 
                            <strong>Senin - Sabtu</strong>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <i class="ti ti-clock me-1 text-primary"></i> 
                            <strong>07:30 - 16:00 WIB</strong>
                        </div>
                    </div>
                </div>
            </div>
            <p class="mt-3 text-muted small">Silakan berkunjung kembali pada jam operasional di atas.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
