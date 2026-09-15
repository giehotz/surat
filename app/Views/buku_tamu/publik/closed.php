<?= $this->extend('buku_tamu/publik/layout') ?>

<?= $this->section('styles') ?>
<style>
    .closed-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 3.5rem 2.5rem;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08);
        max-width: 650px;
        margin: 2rem auto;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .closed-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }

    .closed-icon-badge {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #d97706;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3rem;
        box-shadow: 0 10px 20px rgba(217, 119, 6, 0.15);
    }

    .info-schedule-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem;
        text-align: center;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-3">
    <div class="closed-card shadow-sm">
        <div class="closed-icon-badge">
            <i class="ti ti-clock-off"></i>
        </div>
        
        <h1 class="display-6 fw-extrabold text-dark mb-2">Layanan Buku Tamu Ditutup</h1>
        <p class="text-muted fs-3 mb-4">
            <?= esc($message ?? 'Maaf, layanan buku tamu digital saat ini sedang berada di luar jam operasional.') ?>
        </p>

        <div class="row g-3 justify-content-center mb-4">
            <div class="col-sm-6">
                <div class="info-schedule-box">
                    <div class="text-muted small fw-semibold text-uppercase mb-1"><i class="ti ti-calendar me-1 text-primary"></i> Hari Pelayanan</div>
                    <div class="h3 fw-bold text-dark m-0">Senin - Sabtu</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="info-schedule-box">
                    <div class="text-muted small fw-semibold text-uppercase mb-1"><i class="ti ti-clock me-1 text-primary"></i> Jam Operasional</div>
                    <div class="h3 fw-bold text-dark m-0">
                        <?= esc($appSettings['buku_tamu_open_time'] ?? '07:30') ?> - <?= esc($appSettings['buku_tamu_close_time'] ?? '16:00') ?> WIB
                    </div>
                </div>
            </div>
        </div>

        <p class="text-muted small mb-4">
            Silakan berkunjung kembali pada jadwal operasional resmi madrasah. Terima kasih atas pengertian Anda.
        </p>

        <a href="<?= base_url('/') ?>" class="btn btn-primary rounded-pill px-4">
            <i class="ti ti-arrow-left me-1"></i> Ke Halaman Utama
        </a>
    </div>
</div>
<?= $this->endSection() ?>
