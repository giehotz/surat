<?= $this->extend('buku_tamu/publik/layout') ?>

<?= $this->section('styles') ?>
<style>
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

    .form-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .form-section-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Webcam Container */
    .webcam-card {
        background: #0f172a;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    #player, #canvas {
        width: 100%;
        height: 240px;
        object-fit: cover;
        display: block;
    }
    .camera-overlay-frame {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 150px;
        height: 180px;
        border: 2px dashed rgba(255, 255, 255, 0.6);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Signature Container */
    .signature-card {
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 8px;
        position: relative;
        transition: border-color 0.2s;
    }
    .signature-card:focus-within {
        border-color: #206bc4;
    }
    .signature-pad-body {
        position: relative;
        width: 100%;
        height: 180px;
        border-radius: 10px;
        background: #ffffff;
        overflow: hidden;
    }
    .signature-pad-body canvas {
        width: 100%;
        height: 100%;
        cursor: crosshair;
        touch-action: none;
    }
    .signature-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #cbd5e1;
        font-size: 0.9rem;
        font-weight: 500;
        pointer-events: none;
        user-select: none;
    }

    .btn-submit-guest {
        background: linear-gradient(135deg, #206bc4 0%, #0054a6 100%);
        border: none;
        color: #fff;
        padding: 14px 32px;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 15px rgba(32, 107, 196, 0.3);
    }
    .btn-submit-guest:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(32, 107, 196, 0.4);
        color: #fff;
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
                    <i class="ti ti-users"></i> Kategori Tamu Umum
                </div>
                <h1 class="h2 fw-bold text-dark m-0">Formulir Buku Tamu Umum</h1>
            </div>
            <a href="<?= base_url('buku-tamu') ?>" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="form-card shadow-sm p-4 p-md-5">
            <form action="<?= base_url('buku-tamu/store') ?>" method="post" id="tamuForm" autocomplete="off" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="jenis_tamu" value="umum">
                <input type="hidden" name="foto_wajah_base64" id="foto_wajah_base64">
                <input type="hidden" name="tanda_tangan_base64" id="tanda_tangan_base64">

                <div class="row g-4">
                    <!-- KOLOM KIRI: Data Tamu & Keperluan (7 Kolom) -->
                    <div class="col-lg-7">
                        <!-- BAGIAN 1: Identitas Tamu -->
                        <div class="form-section-box">
                            <div class="section-title-badge">
                                <span class="section-number">1</span>
                                <span>Identitas Pengunjung</span>
                            </div>

                            <div class="mb-3">
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
                                        <input type="text" class="form-control" name="nama_lengkap" placeholder="Contoh: Bpk. Hidayat" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required fw-semibold">No. WhatsApp / HP</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="ti ti-brand-whatsapp text-success"></i></span>
                                        <input type="text" class="form-control" name="no_hp" placeholder="Contoh: 081234567890" required>
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
                                    <textarea class="form-control" name="alamat_instansi" rows="2" placeholder="Contoh: Jl. Merdeka No. 10, Kel. Sukamaju" required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- BAGIAN 2: Rincian Keperluan -->
                        <div class="form-section-box mb-0">
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
                                            <strong class="text-dark d-block">Persetujuan Notifikasi & Privasi</strong>
                                            Saya setuju nomor kontak ini digunakan untuk keperluan koordinasi kunjungan dan konfirmasi layanan.
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Verifikasi Foto & Tanda Tangan (5 Kolom) -->
                    <div class="col-lg-5">
                        <!-- BAGIAN 3: Verifikasi Foto -->
                        <div class="form-section-box">
                            <div class="section-title-badge">
                                <span class="section-number">3</span>
                                <span>Foto Pengunjung</span>
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
                                <input type="file" class="form-control" name="foto_wajah_file" id="foto_wajah_file" accept="image/*">
                                <div class="form-text text-muted small">Format: JPG, PNG, atau WEBP. Maksimal 2MB.</div>
                            </div>
                        </div>

                        <!-- BAGIAN 4: Tanda Tangan Digital -->
                        <div class="form-section-box">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="section-title-badge mb-0">
                                    <span class="section-number">4</span>
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
                            <div class="form-text text-muted small mt-1">Gunakan jari di layar sentuh atau mouse untuk membubuhkan tanda tangan.</div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-submit-guest w-100" id="submitBtn">
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
    let isCaptured = false;

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
            isCaptured = false;
        }
    }

    // Start Camera
    navigator.mediaDevices?.getUserMedia({
        video: { facingMode: "user" }
    }).then((stream) => {
        player.srcObject = stream;
    }).catch((err) => {
        console.warn("Webcam unavailable:", err);
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
        isCaptured = true;
    });

    retakeBtn.addEventListener('click', () => {
        fotoBase64.value = '';
        player.style.display = 'block';
        canvas.style.display = 'none';
        if (cameraFrame) cameraFrame.style.display = 'block';
        captureBtn.style.display = 'block';
        retakeBtn.style.display = 'none';
        isCaptured = false;
    });

    // Signature Pad
    const signatureCanvas = document.getElementById('signature-canvas');
    const signatureHint = document.getElementById('signatureHint');

    function resizeCanvas() {
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

    const signaturePad = new SignaturePad(signatureCanvas, {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: '#0f172a',
        minWidth: 1.5,
        maxWidth: 3.5
    });

    signaturePad.addEventListener("beginStroke", () => {
        if (signatureHint) signatureHint.style.display = 'none';
    });

    window.addEventListener('resize', resizeCanvas);
    setTimeout(resizeCanvas, 250);

    document.getElementById('clear-signature').addEventListener('click', function() {
        signaturePad.clear();
        if (signatureHint) signatureHint.style.display = 'block';
    });

    // Form Submit Listener
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