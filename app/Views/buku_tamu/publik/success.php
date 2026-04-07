<?= $this->extend('buku_tamu/publik/layout') ?>

<?= $this->section('styles') ?>
<style>
    .success-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 3rem 2rem;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 2rem auto;
        position: relative;
        overflow: hidden;
    }

    .success-icon-container {
        width: 120px;
        height: 120px;
        background: #2fb344;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 4rem;
        animation: pulseSuccess 2s infinite;
        box-shadow: 0 0 0 0 rgba(47, 179, 68, 0.4);
    }

    @keyframes pulseSuccess {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(47, 179, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 20px rgba(47, 179, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(47, 179, 68, 0); }
    }

    .countdown-progress {
        height: 4px;
        background: #eee;
        border-radius: 2px;
        margin: 2rem 0 1rem;
        position: relative;
    }

    .countdown-bar {
        height: 100%;
        background: #2fb344;
        width: 100%;
        transition: width 1s linear;
        border-radius: 2px;
    }

    .btn-finish {
        padding: 0.8rem 2.5rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-finish:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="success-card border-top border-success border-5">
        <div class="success-icon-container">
            <i class="ti ti-check"></i>
        </div>
        
        <h1 class="display-5 fw-bold text-dark mb-2">Registrasi Berhasil!</h1>
        <p class="text-muted fs-3 mb-4">
            Terima kasih telah berkunjung. Data Anda telah kami catat dengan aman di sistem.
        </p>

        <div class="bg-success-lt p-4 rounded-3 mb-4">
            <p class="mb-0 fw-medium text-success fs-4">
                "Mohon menunggu di ruang tunggu yang disediakan. Petugas kami akan segera melayani Anda."
            </p>
        </div>

        <div class="countdown-progress">
            <div class="countdown-bar" id="progressBar"></div>
        </div>
        
        <p class="text-muted mb-4 small" id="countdown">Mengalihkan secara otomatis dalam 5 detik...</p>

        <div class="d-flex justify-content-center gap-3">
            <a href="<?= base_url('buku-tamu') ?>" class="btn btn-success btn-finish d-flex align-items-center gap-2">
                <i class="ti ti-home fs-2"></i> Kembali Ke Beranda
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
        
        countdownEl.innerText = `Mengalihkan secara otomatis dalam ${timeLeft} detik...`;
        
        if (timeLeft <= 0) {
            clearInterval(interval);
            window.location.href = '<?= base_url('buku-tamu') ?>';
        }
    }, 1000);
</script>
<?= $this->endSection() ?>

