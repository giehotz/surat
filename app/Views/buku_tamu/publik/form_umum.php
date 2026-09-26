<?= $this->extend('buku_tamu/publik/layout') ?>

<?= $this->section('styles') ?>
<style>
    /* Hero & Card Header */
    .form-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* Multi-step Wizard Navigation */
    .wizard-steps-container {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
    }

    .wizard-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .wizard-step {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        user-select: none;
        position: relative;
        z-index: 2;
        transition: all 0.25s ease;
    }

    .step-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #cbd5e1;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .step-check {
        display: none;
        font-size: 1.2rem;
    }

    .step-info {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .step-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #94a3b8;
    }

    .step-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #475569;
        transition: color 0.2s ease;
    }

    /* Active Step */
    .wizard-step.active .step-icon-wrap {
        background: linear-gradient(135deg, #206bc4 0%, #0054a6 100%);
        border-color: #206bc4;
        color: #ffffff;
        transform: scale(1.08);
        box-shadow: 0 4px 14px rgba(32, 107, 196, 0.35);
    }
    .wizard-step.active .step-label {
        color: #206bc4;
    }
    .wizard-step.active .step-title {
        color: #0f172a;
    }

    /* Completed Step */
    .wizard-step.completed .step-icon-wrap {
        background: #ecfdf5;
        border-color: #10b981;
        color: #059669;
    }
    .wizard-step.completed .step-num {
        display: none;
    }
    .wizard-step.completed .step-check {
        display: inline-block;
    }
    .wizard-step.completed .step-label {
        color: #059669;
    }
    .wizard-step.completed .step-title {
        color: #1e293b;
    }

    /* Connectors */
    .wizard-connector {
        flex: 1;
        height: 3px;
        background: #e2e8f0;
        margin: 0 16px;
        border-radius: 2px;
        transition: all 0.35s ease;
        position: relative;
        z-index: 1;
    }
    .wizard-connector.completed {
        background: linear-gradient(90deg, #10b981, #206bc4);
    }

    /* Step Pane Container */
    .wizard-pane {
        animation: fadeInPane 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes fadeInPane {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Section Boxes inside Steps */
    .form-section-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
    }

    .section-title-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1.25rem;
    }

    .section-number {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #206bc4;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 800;
    }

    /* Webcam Styles */
    .webcam-card {
        background: #0f172a;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        aspect-ratio: 4 / 3;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e2e8f0;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .webcam-card video,
    .webcam-card canvas {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .camera-overlay-frame {
        position: absolute;
        width: 58%;
        height: 72%;
        border: 2px dashed rgba(255, 255, 255, 0.65);
        border-radius: 50% 50% 45% 45%;
        pointer-events: none;
        box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.4);
    }

    /* Signature Pad */
    .signature-card {
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        transition: border-color 0.2s ease;
    }
    .signature-card:hover {
        border-color: #206bc4;
    }

    .signature-pad-body {
        position: relative;
        height: 230px;
        touch-action: none;
    }

    .signature-pad-body canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .signature-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #94a3b8;
        font-size: 0.88rem;
        pointer-events: none;
        user-select: none;
    }

    .btn-submit-guest {
        background: linear-gradient(135deg, #206bc4 0%, #0054a6 100%);
        border: none;
        color: #fff;
        padding: 12px 28px;
        font-size: 1.05rem;
        font-weight: 700;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 15px rgba(32, 107, 196, 0.3);
    }
    .btn-submit-guest:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(32, 107, 196, 0.4);
        color: #fff;
    }

    /* Responsive Wizard Navigation */
    @media (max-width: 768px) {
        .wizard-steps-container {
            padding: 1rem;
        }
        .step-info {
            display: none;
        }
        .wizard-connector {
            margin: 0 8px;
        }
        .step-icon-wrap {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
        }
        .signature-pad-body {
            height: 180px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-12 col-xl-11">
        <!-- Top Navigation & Title -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <div class="form-hero-badge mb-1">
                    <i class="ti ti-users"></i> Kategori Tamu Umum / Wali Murid
                </div>
                <h1 class="h2 fw-bold text-dark m-0">Formulir Buku Tamu Umum</h1>
            </div>
            <a href="<?= base_url('buku-tamu') ?>" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="ti ti-arrow-left me-1"></i> Kembali ke Menu
            </a>
        </div>

        <!-- Wizard Progress Bar -->
        <div class="wizard-steps-container mb-4 shadow-sm">
            <div class="wizard-steps">
                <!-- Step 1 Nav -->
                <div class="wizard-step active" id="step-nav-1" onclick="goToStep(1)">
                    <div class="step-icon-wrap">
                        <span class="step-num">1</span>
                        <i class="ti ti-check step-check"></i>
                    </div>
                    <div class="step-info">
                        <span class="step-label">Langkah 1</span>
                        <strong class="step-title">Identitas Diri</strong>
                    </div>
                </div>

                <div class="wizard-connector" id="connector-1"></div>

                <!-- Step 2 Nav -->
                <div class="wizard-step" id="step-nav-2" onclick="goToStep(2)">
                    <div class="step-icon-wrap">
                        <span class="step-num">2</span>
                        <i class="ti ti-check step-check"></i>
                    </div>
                    <div class="step-info">
                        <span class="step-label">Langkah 2</span>
                        <strong class="step-title">Keperluan</strong>
                    </div>
                </div>

                <div class="wizard-connector" id="connector-2"></div>

                <!-- Step 3 Nav -->
                <div class="wizard-step" id="step-nav-3" onclick="goToStep(3)">
                    <div class="step-icon-wrap">
                        <span class="step-num">3</span>
                        <i class="ti ti-check step-check"></i>
                    </div>
                    <div class="step-info">
                        <span class="step-label">Langkah 3</span>
                        <strong class="step-title">Verifikasi Kehadiran</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card shadow-sm p-4 p-md-5">
            <form action="<?= base_url('buku-tamu/store') ?>" method="post" id="tamuForm" autocomplete="off" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="jenis_tamu" value="umum">
                <input type="hidden" name="foto_wajah_base64" id="foto_wajah_base64">
                <input type="hidden" name="tanda_tangan_base64" id="tanda_tangan_base64">

                <!-- Honeypot Bot Trap -->
                <?php if (($appSettings['buku_tamu_honeypot'] ?? '1') == '1'): ?>
                    <div style="display:none !important;" aria-hidden="true">
                        <input type="text" name="website_trap" value="" tabindex="-1" autocomplete="off">
                    </div>
                <?php endif; ?>

                <!-- ========================================== -->
                <!-- LANGKAH 1: IDENTITAS PENGUNJUNG           -->
                <!-- ========================================== -->
                <div class="wizard-pane active" id="step-pane-1">
                    <div class="form-section-box mb-4">
                        <div class="section-title-badge">
                            <span class="section-number">1</span>
                            <span>Identitas & Kontak Pengunjung</span>
                        </div>

                        <div class="mb-4">
                            <label class="form-label required fw-semibold">Kategori Kunjungan</label>
                            <div class="form-selectgroup w-100 d-flex">
                                <label class="form-selectgroup-item flex-fill">
                                    <input type="radio" name="sub_jenis_tamu" value="umum" class="form-selectgroup-input" checked onchange="toggleWaliField()">
                                    <span class="form-selectgroup-label py-2">
                                        <i class="ti ti-user me-1 text-primary"></i> Tamu Umum / Personal
                                    </span>
                                </label>
                                <label class="form-selectgroup-item flex-fill">
                                    <input type="radio" name="sub_jenis_tamu" value="wali" class="form-selectgroup-input" onchange="toggleWaliField()">
                                    <span class="form-selectgroup-label py-2">
                                        <i class="ti ti-users-group me-1 text-primary"></i> Orang Tua / Wali Murid
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required fw-semibold">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="ti ti-user text-muted"></i></span>
                                    <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Contoh: Bpk. Hidayat" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required fw-semibold">No. WhatsApp / HP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="ti ti-brand-whatsapp text-success"></i></span>
                                    <input type="tel" class="form-control" name="no_hp" id="no_hp" placeholder="Contoh: 081234567890" required>
                                </div>
                            </div>

                            <div class="col-12" id="field_wali_dari" style="display: none;">
                                <label class="form-label required fw-semibold">Wali Dari (Nama Siswa & Kelas)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="ti ti-school text-primary"></i></span>
                                    <input type="text" class="form-control" name="id_siswa_dituju" id="input_id_siswa_dituju" placeholder="Contoh: Muhammad Rizky - Kelas 5A">
                                </div>
                                <div class="form-text small text-muted">Sebutkan nama lengkap siswa yang ingin diurus keperluannya.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label required fw-semibold">Alamat / Asal Wilayah</label>
                                <textarea class="form-control" name="alamat_instansi" id="alamat_instansi" rows="2" placeholder="Contoh: Jl. Merdeka No. 10, Kel. Sukamaju" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1 Navigation Buttons -->
                    <div class="d-flex align-items-center justify-content-between pt-2">
                        <a href="<?= base_url('buku-tamu') ?>" class="btn btn-outline-secondary px-3">
                            <i class="ti ti-x me-1"></i> Batal
                        </a>
                        <button type="button" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2" onclick="nextStep(2)">
                            <span>Lanjut: Rincian Keperluan</span>
                            <i class="ti ti-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- LANGKAH 2: RINCIAN KEPERLUAN               -->
                <!-- ========================================== -->
                <div class="wizard-pane d-none" id="step-pane-2">
                    <div class="form-section-box mb-4">
                        <div class="section-title-badge">
                            <span class="section-number">2</span>
                            <span>Tujuan & Keperluan Kunjungan</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required fw-semibold">Tujuan Kunjungan</label>
                                <select class="form-select" id="tujuan_kunjungan_select" name="tujuan_kunjungan" required onchange="toggleTujuanLainnya()">
                                    <option value="" hidden>-- Pilih Tujuan --</option>
                                    <option value="Konsultasi Guru / Wali Kelas">Konsultasi Guru / Wali Kelas</option>
                                    <option value="Mengambil Rapor / Ijazah / Legalisir">Mengambil Rapor / Ijazah / Legalisir</option>
                                    <option value="Pendaftaran Siswa Baru (PPDB)">Pendaftaran Siswa Baru (PPDB)</option>
                                    <option value="Keperluan Tata Usaha / Keuangan">Keperluan Tata Usaha / Keuangan</option>
                                    <option value="Mengantar Barang / Titipan">Mengantar Barang / Titipan</option>
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                                <input type="text" class="form-control mt-2" id="tujuan_kunjungan_lainnya" placeholder="Sebutkan tujuan Anda..." style="display: none;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Petugas / Guru Dituju <span class="text-muted fw-normal">(Opsional)</span></label>
                                <select class="form-select" name="id_pegawai_dituju">
                                    <option value="">-- Tidak Spesifik / Bebas --</option>
                                    <?php foreach ($guruList as $guru): ?>
                                        <option value="<?= $guru['id'] ?>"><?= esc($guru['nama_pegawai']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-check bg-white p-3 rounded-3 border d-flex align-items-center mb-0 cursor-pointer">
                                    <input class="form-check-input ms-0 me-3" type="checkbox" name="consent_wa" value="1" checked>
                                    <span class="form-check-label text-muted small">
                                        <strong class="text-dark d-block mb-1">Persetujuan Notifikasi & Privasi</strong>
                                        Saya setuju nomor kontak ini digunakan untuk keperluan koordinasi kunjungan dan konfirmasi layanan madrasah.
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 Navigation Buttons -->
                    <div class="d-flex align-items-center justify-content-between pt-2">
                        <button type="button" class="btn btn-outline-secondary px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" onclick="prevStep(1)">
                            <i class="ti ti-arrow-left"></i>
                            <span>Kembali: Identitas Diri</span>
                        </button>
                        <button type="button" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2" onclick="nextStep(3)">
                            <span>Lanjut: Verifikasi Kehadiran</span>
                            <i class="ti ti-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- LANGKAH 3: VERIFIKASI KEHADIRAN (FOTO + TTD)-->
                <!-- ========================================== -->
                <div class="wizard-pane d-none" id="step-pane-3">
                    <div class="row g-4 mb-4">
                        <!-- Sisi Kiri: Foto Pengunjung -->
                        <div class="col-lg-6">
                            <div class="form-section-box h-100">
                                <div class="section-title-badge">
                                    <span class="section-number">3A</span>
                                    <span>Foto Kehadiran</span>
                                </div>

                                <div class="form-selectgroup w-100 d-flex mb-3">
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="foto_source" value="webcam" class="form-selectgroup-input" checked onchange="toggleFotoMethod()">
                                        <span class="form-selectgroup-label py-2">
                                            <i class="ti ti-camera me-1 text-primary"></i> Ambil Kamera
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="foto_source" value="upload" class="form-selectgroup-input" onchange="toggleFotoMethod()">
                                        <span class="form-selectgroup-label py-2">
                                            <i class="ti ti-upload me-1 text-primary"></i> Unggah File
                                        </span>
                                    </label>
                                </div>

                                <!-- Webcam View -->
                                <div id="container_webcam">
                                    <div class="webcam-card mb-2">
                                        <video id="player" autoplay playsinline></video>
                                        <canvas id="canvas" style="display: none;"></canvas>
                                        <div class="camera-overlay-frame" id="cameraFrame"></div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary flex-fill" id="captureBtn">
                                            <i class="ti ti-camera me-1"></i> Ambil Foto
                                        </button>
                                        <button type="button" class="btn btn-warning flex-fill" id="retakeBtn" style="display: none;">
                                            <i class="ti ti-rotate me-1"></i> Ambil Ulang
                                        </button>
                                    </div>
                                </div>

                                <!-- Upload File Fallback -->
                                <div id="container_upload" style="display: none;">
                                    <div class="border rounded-3 p-3 bg-white text-center">
                                        <i class="ti ti-cloud-upload text-primary fs-1 mb-2"></i>
                                        <input type="file" class="form-control" name="foto_wajah_file" id="foto_wajah_file" accept="image/*">
                                        <div class="form-text text-muted small mt-2">Format: JPG, PNG, atau WEBP. Maksimal 2MB.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sisi Kanan: Tanda Tangan Digital -->
                        <div class="col-lg-6">
                            <div class="form-section-box h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="section-title-badge mb-0">
                                        <span class="section-number">3B</span>
                                        <span>Tanda Tangan Digital</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="clear-signature">
                                        <i class="ti ti-eraser me-1"></i> Hapus
                                    </button>
                                </div>

                                <div class="signature-card">
                                    <div class="signature-pad-body">
                                        <canvas id="signature-canvas"></canvas>
                                        <div class="signature-watermark" id="signatureHint">
                                            <i class="ti ti-pencil me-1"></i> Goreskan tanda tangan di sini
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text text-muted small mt-2">
                                    <i class="ti ti-info-circle me-1"></i>Gunakan jari pada layar sentuh atau mouse untuk membubuhkan tanda tangan.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 Navigation Buttons -->
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <button type="button" class="btn btn-outline-secondary px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" onclick="prevStep(2)">
                            <i class="ti ti-arrow-left"></i>
                            <span>Kembali: Rincian Keperluan</span>
                        </button>
                        <button type="submit" class="btn btn-submit-guest shadow" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none" id="submitSpinner" role="status"></span>
                            <span id="submitText"><i class="ti ti-send me-1"></i> Simpan & Konfirmasi Kunjungan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    let currentStep = 1;

    // Validasi per langkah form
    function validateStep(step) {
        const pane = document.getElementById('step-pane-' + step);
        if (!pane) return true;

        const inputs = pane.querySelectorAll('input[required], select[required], textarea[required]');
        for (const el of inputs) {
            // Abaikan input jika elemen di dalam container tersembunyi
            if (el.offsetParent === null) continue;

            if (!el.checkValidity() || el.value.trim() === '') {
                el.reportValidity();
                el.focus();
                el.classList.add('is-invalid');
                el.addEventListener('input', () => el.classList.remove('is-invalid'), { once: true });
                return false;
            }
        }
        return true;
    }

    function goToStep(step) {
        if (step === currentStep) return;
        if (step < currentStep) {
            showStep(step);
        } else if (step === currentStep + 1) {
            if (validateStep(currentStep)) {
                showStep(step);
            }
        }
    }

    function nextStep(target) {
        if (validateStep(currentStep)) {
            showStep(target);
        }
    }

    function prevStep(target) {
        showStep(target);
    }

    function showStep(step) {
        currentStep = step;

        for (let i = 1; i <= 3; i++) {
            const pane = document.getElementById('step-pane-' + i);
            const nav = document.getElementById('step-nav-' + i);
            const conn = document.getElementById('connector-' + (i - 1));

            if (pane) {
                if (i === step) {
                    pane.classList.remove('d-none');
                    pane.classList.add('active');
                } else {
                    pane.classList.add('d-none');
                    pane.classList.remove('active');
                }
            }

            if (nav) {
                nav.classList.remove('active', 'completed');
                if (i < step) {
                    nav.classList.add('completed');
                } else if (i === step) {
                    nav.classList.add('active');
                }
            }

            if (conn) {
                if (i <= step) {
                    conn.classList.add('completed');
                } else {
                    conn.classList.remove('completed');
                }
            }
        }

        // Jika masuk ke langkah 3, refresh ukuran kanvas signature
        if (step === 3) {
            setTimeout(resizeCanvas, 150);
        }

        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    // Toggle Field Wali Murid
    function toggleWaliField() {
        const subJenis = document.querySelector('input[name="sub_jenis_tamu"]:checked').value;
        const waliField = document.getElementById('field_wali_dari');
        const inputWali = document.getElementById('input_id_siswa_dituju');
        
        if (subJenis === 'wali') {
            waliField.style.display = 'block';
            inputWali.setAttribute('required', 'required');
        } else {
            waliField.style.display = 'none';
            inputWali.removeAttribute('required');
            inputWali.value = '';
        }
    }

    // Toggle Input Tujuan Lainnya
    function toggleTujuanLainnya() {
        const sel = document.getElementById('tujuan_kunjungan_select');
        const input = document.getElementById('tujuan_kunjungan_lainnya');

        if (sel.value === 'Lainnya') {
            input.style.display = 'block';
            input.setAttribute('required', 'required');
            sel.removeAttribute('name');
            input.setAttribute('name', 'tujuan_kunjungan');
            input.focus();
        } else {
            input.style.display = 'none';
            input.removeAttribute('required');
            input.removeAttribute('name');
            sel.setAttribute('name', 'tujuan_kunjungan');
        }
    }

    // Kamera Logic
    const player = document.getElementById('player');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const cameraFrame = document.getElementById('cameraFrame');
    const fotoBase64 = document.getElementById('foto_wajah_base64');

    function toggleFotoMethod() {
        const method = document.querySelector('input[name="foto_source"]:checked').value;
        const containerWebcam = document.getElementById('container_webcam');
        const containerUpload = document.getElementById('container_upload');
        const inputFile = document.getElementById('foto_wajah_file');

        if (method === 'webcam') {
            containerWebcam.style.display = 'block';
            containerUpload.style.display = 'none';
            inputFile.value = '';
        } else {
            containerWebcam.style.display = 'none';
            containerUpload.style.display = 'block';
            fotoBase64.value = '';
            player.style.display = 'block';
            canvas.style.display = 'none';
            captureBtn.style.display = 'block';
            retakeBtn.style.display = 'none';
            if (cameraFrame) cameraFrame.style.display = 'block';
        }
    }

    // Inisialisasi Kamera
    navigator.mediaDevices?.getUserMedia({
        video: { facingMode: "user" }
    }).then((stream) => {
        player.srcObject = stream;
    }).catch((err) => {
        console.warn("Webcam tidak tersedia, beralih ke mode unggah file:", err);
        const methodUpload = document.querySelector('input[name="foto_source"][value="upload"]');
        if (methodUpload) {
            methodUpload.checked = true;
            toggleFotoMethod();
        }
    });

    captureBtn.addEventListener('click', () => {
        canvas.width = player.videoWidth || 640;
        canvas.height = player.videoHeight || 480;
        context.drawImage(player, 0, 0, canvas.width, canvas.height);
        fotoBase64.value = canvas.toDataURL('image/jpeg', 0.85);
        player.style.display = 'none';
        canvas.style.display = 'block';
        if (cameraFrame) cameraFrame.style.display = 'none';
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'block';
    });

    retakeBtn.addEventListener('click', () => {
        fotoBase64.value = '';
        player.style.display = 'block';
        canvas.style.display = 'none';
        if (cameraFrame) cameraFrame.style.display = 'block';
        captureBtn.style.display = 'block';
        retakeBtn.style.display = 'none';
    });

    // Signature Pad
    const signatureCanvas = document.getElementById('signature-canvas');
    const signatureHint = document.getElementById('signatureHint');

    const signaturePad = new SignaturePad(signatureCanvas, {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: '#0f172a',
        minWidth: 1.5,
        maxWidth: 3.5
    });

    function resizeCanvas() {
        if (!signatureCanvas || signatureCanvas.offsetWidth === 0) return;
        const data = signaturePad.isEmpty() ? null : signaturePad.toData();
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        signatureCanvas.width = signatureCanvas.offsetWidth * ratio;
        signatureCanvas.height = signatureCanvas.offsetHeight * ratio;
        signatureCanvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
        if (data) {
            signaturePad.fromData(data);
        }
    }

    signaturePad.addEventListener("beginStroke", () => {
        if (signatureHint) signatureHint.style.display = 'none';
    });

    window.addEventListener('resize', resizeCanvas);

    document.getElementById('clear-signature').addEventListener('click', function() {
        signaturePad.clear();
        if (signatureHint) signatureHint.style.display = 'block';
    });

    // Submit Validation & Handler
    document.getElementById('tamuForm').addEventListener('submit', function(e) {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            alert("Mohon bubuhkan tanda tangan Anda terlebih dahulu pada kotak yang disediakan.");
            signatureCanvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        document.getElementById('tanda_tangan_base64').value = signaturePad.toDataURL('image/png');

        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitText').innerHTML = 'Menyimpan Kunjungan...';
        document.getElementById('submitSpinner').classList.remove('d-none');
    });
</script>
<?= $this->endSection() ?>