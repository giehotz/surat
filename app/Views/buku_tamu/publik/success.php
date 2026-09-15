<?= $this->extend('buku_tamu/publik/layout') ?>

<?= $this->section('styles') ?>
<style>
    .success-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 3.5rem 2.5rem;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08);
        max-width: 620px;
        margin: 1.5rem auto;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .success-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .success-icon-badge {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.75rem;
        font-size: 3.5rem;
        animation: pulseSuccess 2.5s infinite;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
    }

    @keyframes pulseSuccess {
        0% { transform: scale(0.96); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6); }
        70% { transform: scale(1); box-shadow: 0 0 0 18px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.96); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .countdown-track {
        height: 6px;
        background: #f1f5f9;
        border-radius: 4px;
        margin: 2rem 0 1rem;
        overflow: hidden;
    }

    .countdown-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #059669);
        width: 100%;
        transition: width 1s linear;
        border-radius: 4px;
    }

    .btn-action-success {
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-3">
    <div class="success-card shadow-sm">
        <div class="success-icon-badge">
            <i class="ti ti-check"></i>
        </div>
        
        <h1 class="display-6 fw-extrabold text-dark mb-2">Registrasi Berhasil!</h1>
        <p class="text-muted fs-3 mb-4">
            Terima kasih telah mengisi buku tamu. Data kunjungan Anda telah tersimpan dengan aman di sistem.
        </p>

        <div class="bg-light p-4 rounded-3 mb-4 border text-start">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar bg-success-lt text-success rounded-3 p-2">
                    <i class="ti ti-armchair fs-1"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark m-0 mb-1">Informasi Kunjungan</h4>
                    <p class="text-muted small m-0">
                        Silakan menunggu di ruang tamu atau ruang pelayanan. Petugas kami akan segera melayani keperluan Anda.
                    </p>
                </div>
            </div>
        </div>

        <div class="countdown-track">
            <div class="countdown-fill" id="progressBar"></div>
        </div>
        
        <p class="text-muted mb-4 small" id="countdown">Mengalihkan kembali ke beranda dalam 5 detik...</p>

        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="<?= base_url('buku-tamu') ?>" class="btn btn-success btn-action-success shadow-sm">
                <i class="ti ti-home fs-2"></i> Ke Halaman Utama
            </a>
            <a href="<?= base_url('buku-tamu/umum') ?>" class="btn btn-outline-primary btn-action-success">
                <i class="ti ti-plus fs-2"></i> Tamu Baru
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let timeLeft = 5;
    const countdownEl = document.getElementById('countdown');
    const progressBar = document.getElementById('progressBar');
    
    const interval = setInterval(() => {
        timeLeft--;
        const percentage = (timeLeft / 5) * 100;
        progressBar.style.width = percentage + '%';
        
        countdownEl.innerText = `Mengalihkan kembali ke beranda dalam ${timeLeft} detik...`;
        
        if (timeLeft <= 0) {
            clearInterval(interval);
            window.location.href = '<?= base_url('buku-tamu') ?>';
        }
    }, 1000);
</script>
<?= $this->endSection() ?>
