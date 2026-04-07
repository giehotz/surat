<?= $this->extend('buku_tamu/publik/layout') ?>

<?= $this->section('styles') ?>
<style>
    /* Styling khusus untuk layar Kiosk/Resepsionis */
    .kiosk-wrapper {
        min-height: 70vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .kiosk-card {
        border: 2px solid transparent;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        background-color: #ffffff;
    }
    
    .kiosk-card:hover {
        transform: translateY(-8px);
        border-color: #206bc4;
        box-shadow: 0 15px 30px rgba(32, 107, 196, 0.15) !important;
    }

    /* Varian warna untuk Tamu Dinas agar berbeda */
    .kiosk-card.card-dinas:hover {
        border-color: #2fb344;
        box-shadow: 0 15px 30px rgba(47, 179, 68, 0.15) !important;
    }
    
    .icon-wrapper {
        width: 110px;
        height: 110px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 1.5rem;
    }
    
    .icon-wrapper-primary {
        background-color: rgba(32, 107, 196, 0.1);
        color: #206bc4;
    }
    
    .icon-wrapper-success {
        background-color: rgba(47, 179, 68, 0.1);
        color: #2fb344;
    }
    
    .clock-widget {
        display: inline-block;
        background: #ffffff;
        border: 1px solid #e6e8e9;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border-radius: 50px;
        padding: 12px 35px;
    }

    /* Animated Aurora Background Effect */
    .bg-shape {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        z-index: -1;
        opacity: 0.5;
        animation: float 20s infinite ease-in-out alternate;
    }
    
    .bg-shape-1 {
        width: 600px;
        height: 600px;
        background: rgba(32, 107, 196, 0.18); /* Tabler Primary Blue */
        top: -150px;
        left: -150px;
        animation-duration: 25s;
    }
    
    .bg-shape-2 {
        width: 500px;
        height: 500px;
        background: rgba(47, 179, 68, 0.12); /* Tabler Success Green */
        bottom: -100px;
        right: -100px;
        animation-duration: 22s;
        animation-delay: -5s;
    }

    .bg-shape-3 {
        width: 700px;
        height: 700px;
        background: rgba(116, 184, 255, 0.1); /* Light Blue */
        top: 20%;
        left: 20%;
        animation-duration: 30s;
        animation-delay: -10s;
    }
    
    @keyframes float {
        0% { transform: translate(0, 0) scale(1) rotate(0deg); }
        33% { transform: translate(50px, 30px) scale(1.1) rotate(5deg); }
        66% { transform: translate(-30px, 60px) scale(0.9) rotate(-5deg); }
        100% { transform: translate(20px, -40px) scale(1) rotate(0deg); }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="kiosk-wrapper py-4 position-relative">
    <!-- Animated Background Layers -->
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
    <div class="bg-shape bg-shape-3"></div>
    <!-- Header Ucapan Selamat Datang -->
    <div class="text-center mb-5">
        <div class="mb-3">
            <!-- Jika Anda punya logo madrasah, bisa dimasukkan di sini -->
            <!-- <img src="<?= base_url('assets/logo.png') ?>" alt="Logo" height="70"> -->
            <i class="ti ti-book-2 text-primary" style="font-size: 3rem;"></i>
        </div>
        <h1 class="display-5 fw-bold text-dark tracking-tight mb-2">Selamat Datang di <?= esc($appSettings['sekolah_nama'] ?? 'Madrasah') ?></h1>
        <p class="fs-2 text-muted">Silakan pilih tujuan kunjungan Anda pada menu di bawah ini</p>
    </div>

    <!-- Pilihan Menu Kiosk -->
    <div class="row justify-content-center g-4 mt-2">
        
        <!-- Tamu Umum -->
        <div class="col-md-6 col-lg-5">
            <a href="<?= base_url('buku-tamu/umum') ?>" class="card kiosk-card text-decoration-none h-100 shadow-sm">
                <div class="card-body text-center py-5 px-4">
                    <div class="icon-wrapper icon-wrapper-primary">
                        <i class="ti ti-users" style="font-size: 4.5rem;"></i>
                    </div>
                    <h2 class="card-title fw-bold mb-2 text-dark" style="font-size: 1.8rem;">Tamu Umum / Wali Murid</h2>
                    <p class="text-muted fs-3 mb-0 lh-base">Untuk kunjungan wali murid, masyarakat umum, pendaftaran, pengambilan ijazah, dll.</p>
                </div>
            </a>
        </div>
        
        <!-- Tamu Dinas -->
        <div class="col-md-6 col-lg-5">
            <a href="<?= base_url('buku-tamu/dinas') ?>" class="card kiosk-card card-dinas text-decoration-none h-100 shadow-sm">
                <div class="card-body text-center py-5 px-4">
                    <div class="icon-wrapper icon-wrapper-success">
                        <i class="ti ti-briefcase" style="font-size: 4.5rem;"></i>
                    </div>
                    <h2 class="card-title fw-bold mb-2 text-dark" style="font-size: 1.8rem;">Tamu Instansi / Dinas</h2>
                    <p class="text-muted fs-3 mb-0 lh-base">Dikhususkan untuk pengawas, asesor, aparatur sipil, atau tamu resmi instansi terkait.</p>
                </div>
            </a>
        </div>
        
    </div>

    <!-- Widget Jam Realtime -->
    <div class="text-center mt-5 pt-4">
        <div class="clock-widget">
            <div class="h2 fw-bold text-dark mb-0" id="realtimeClock" style="letter-spacing: 2px;">00:00:00</div>
            <div class="fs-4 text-muted fw-medium" id="realtimeDate">Senin, 1 Januari 2000</div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString('id-ID', options);
        
        document.getElementById('realtimeClock').textContent = timeString;
        document.getElementById('realtimeDate').textContent = dateString;
    }
    
    // Jalankan segera dan update setiap detik
    updateClock();
    setInterval(updateClock, 1000);
</script>
<?= $this->endSection() ?>