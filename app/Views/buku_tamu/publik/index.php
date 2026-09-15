<?= $this->extend('buku_tamu/publik/layout') ?>

<?= $this->section('styles') ?>
<style>
    .portal-hero {
        padding: 1.5rem 0 1rem 0;
    }

    .greeting-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, rgba(32, 107, 196, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
        color: #206bc4;
        border: 1px solid rgba(32, 107, 196, 0.2);
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .kiosk-card-link {
        text-decoration: none;
        display: block;
        height: 100%;
        color: inherit;
    }

    .kiosk-card {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 24px;
        padding: 2.5rem 2rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
    }

    .kiosk-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        transition: all 0.3s ease;
    }

    /* Tamu Umum Card */
    .card-umum::before {
        background: linear-gradient(90deg, #206bc4, #6366f1);
    }
    .card-umum:hover {
        transform: translateY(-10px);
        border-color: #206bc4;
        box-shadow: 0 20px 35px -10px rgba(32, 107, 196, 0.25);
    }
    .icon-box-umum {
        width: 100px;
        height: 100px;
        border-radius: 24px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #206bc4;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: transform 0.3s ease;
    }
    .card-umum:hover .icon-box-umum {
        transform: scale(1.1) rotate(-3deg);
        background: linear-gradient(135deg, #206bc4 0%, #1d4ed8 100%);
        color: #ffffff;
    }

    /* Tamu Dinas Card */
    .card-dinas::before {
        background: linear-gradient(90deg, #10b981, #059669);
    }
    .card-dinas:hover {
        transform: translateY(-10px);
        border-color: #10b981;
        box-shadow: 0 20px 35px -10px rgba(16, 185, 129, 0.25);
    }
    .icon-box-dinas {
        width: 100px;
        height: 100px;
        border-radius: 24px;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: transform 0.3s ease;
    }
    .card-dinas:hover .icon-box-dinas {
        transform: scale(1.1) rotate(3deg);
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
    }

    .tag-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 500;
        margin: 2px 3px;
    }
    .tag-pill-umum {
        background: #f1f5f9;
        color: #475569;
    }
    .tag-pill-dinas {
        background: #f1f5f9;
        color: #475569;
    }

    .action-btn-custom {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        margin-top: 1.5rem;
    }

    /* Live Clock Widget */
    .clock-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 50px;
        padding: 12px 36px;
        display: inline-flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 6px 20px -4px rgba(0,0,0,0.05);
    }
    .clock-time {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 1px;
        font-variant-numeric: tabular-nums;
    }
    .clock-divider {
        width: 2px;
        height: 32px;
        background: #cbd5e1;
    }
    .clock-date {
        font-size: 0.95rem;
        font-weight: 600;
        color: #64748b;
        text-align: left;
    }

    /* 3 Step Explainer */
    .step-box {
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(226, 232, 240, 0.7);
        border-radius: 16px;
        padding: 1rem;
        text-align: center;
    }
    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #206bc4;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="portal-hero text-center mb-4">
    <div class="greeting-badge shadow-sm">
        <i class="ti ti-sparkles"></i> Selamat Datang di Resepsionis Digital
    </div>
    <h1 class="display-5 fw-extrabold text-dark tracking-tight mb-2">
        Buku Tamu Digital
    </h1>
    <p class="fs-3 text-muted mx-auto" style="max-width: 650px;">
        Silakan pilih kategori kunjungan Anda untuk memulai pencatatan buku tamu yang cepat, mudah, dan aman.
    </p>
</div>

<!-- Kategori Pilihan Tamu -->
<div class="row justify-content-center g-4 mb-5">
    <!-- Tamu Umum / Wali Murid -->
    <div class="col-md-6 col-lg-5">
        <a href="<?= base_url('buku-tamu/umum') ?>" class="kiosk-card-link">
            <div class="kiosk-card card-umum">
                <div>
                    <div class="icon-box-umum shadow-sm">
                        <i class="ti ti-users" style="font-size: 3.5rem;"></i>
                    </div>
                    <h2 class="fw-bold text-dark mb-2 fs-2">Tamu Umum / Wali Murid</h2>
                    <p class="text-muted fs-4 mb-4 lh-sm">
                        Kunjungan personal orang tua siswa, wali murid, alumni, legalisir, pendaftaran PPDB, atau masyarakat umum.
                    </p>
                    <div class="mb-4">
                        <span class="tag-pill tag-pill-umum"><i class="ti ti-check me-1 text-primary"></i> Wali Siswa</span>
                        <span class="tag-pill tag-pill-umum"><i class="ti ti-check me-1 text-primary"></i> Pengambilan Rapor</span>
                        <span class="tag-pill tag-pill-umum"><i class="ti ti-check me-1 text-primary"></i> Legalisir Ijazah</span>
                        <span class="tag-pill tag-pill-umum"><i class="ti ti-check me-1 text-primary"></i> PPDB / Konsultasi</span>
                    </div>
                </div>
                <div>
                    <div class="btn btn-primary action-btn-custom shadow-sm w-100">
                        <span>Isi Buku Tamu Umum</span>
                        <i class="ti ti-arrow-right fs-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Tamu Dinas / Instansi -->
    <div class="col-md-6 col-lg-5">
        <a href="<?= base_url('buku-tamu/dinas') ?>" class="kiosk-card-link">
            <div class="kiosk-card card-dinas">
                <div>
                    <div class="icon-box-dinas shadow-sm">
                        <i class="ti ti-briefcase" style="font-size: 3.5rem;"></i>
                    </div>
                    <h2 class="fw-bold text-dark mb-2 fs-2">Tamu Dinas / Instansi</h2>
                    <p class="text-muted fs-4 mb-4 lh-sm">
                        Kunjungan kedinasan resmi dari Kementerian Agama, Dinas Pendidikan, Pengawas, Asesor, atau Rekanan Instansi.
                    </p>
                    <div class="mb-4">
                        <span class="tag-pill tag-pill-dinas"><i class="ti ti-check me-1 text-success"></i> Pengawas Madrasah</span>
                        <span class="tag-pill tag-pill-dinas"><i class="ti ti-check me-1 text-success"></i> Kemenag / Disdik</span>
                        <span class="tag-pill tag-pill-dinas"><i class="ti ti-check me-1 text-success"></i> Monitoring & Monev</span>
                        <span class="tag-pill tag-pill-dinas"><i class="ti ti-check me-1 text-success"></i> Rekanan & Instansi</span>
                    </div>
                </div>
                <div>
                    <div class="btn btn-success action-btn-custom shadow-sm w-100">
                        <span>Isi Buku Tamu Dinas</span>
                        <i class="ti ti-arrow-right fs-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Widget Jam & Tanggal Realtime -->
<div class="text-center mb-5">
    <div class="clock-card">
        <div class="d-flex align-items-center gap-2">
            <i class="ti ti-clock text-primary fs-2"></i>
            <div class="clock-time" id="realtimeClock">00:00:00</div>
        </div>
        <div class="clock-divider"></div>
        <div class="clock-date">
            <div class="fw-bold text-dark" id="realtimeDate">Senin, 1 Januari 2026</div>
            <div class="text-muted small">Waktu Indonesia Barat (WIB)</div>
        </div>
    </div>
</div>

<!-- 3 Langkah Mudah -->
<div class="row justify-content-center g-3" style="max-width: 900px; margin: 0 auto;">
    <div class="col-12 col-md-4">
        <div class="step-box shadow-sm">
            <div class="step-number">1</div>
            <div class="fw-bold text-dark">Pilih Kategori</div>
            <div class="text-muted small">Tentukan jenis kunjungan Umum atau Kedinasan</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="step-box shadow-sm">
            <div class="step-number">2</div>
            <div class="fw-bold text-dark">Isi Identitas & Foto</div>
            <div class="text-muted small">Lengkapi data kunjungan dan ambil foto selfie</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="step-box shadow-sm">
            <div class="step-number">3</div>
            <div class="fw-bold text-dark">Selesai & Konfirmasi</div>
            <div class="text-muted small">Data tersimpan langsung dan notifikasi terkirim</div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateLiveClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString('id-ID', options);
        
        document.getElementById('realtimeClock').textContent = timeString;
        document.getElementById('realtimeDate').textContent = dateString;
    }
    
    updateLiveClock();
    setInterval(updateLiveClock, 1000);
</script>
<?= $this->endSection() ?>