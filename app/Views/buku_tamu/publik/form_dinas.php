<?= $this->extend('buku_tamu/publik/layout') ?>

<?= $this->section('styles') ?>
<style>
    /* Penyempurnaan styling signature pad agar lebih rapi & terkesan timbul */
    .signature-container {
        background-color: #f8f9fa;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 10px;
    }
    .signature-pad {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        min-height: 250px; /* Paksa minimal tinggi */
        max-width: 100%;
        border-radius: 6px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .signature-pad--body {
        position: relative;
        flex: 1;
        min-height: 220px;
        border: 2px solid #000000; /* Border hitam penuh agar TTD jelas */
        border-radius: 6px;
        overflow: hidden;
    }
    .signature-pad--body canvas {
        width: 100%;
        height: 220px; /* Diperlebar & ditinggikan untuk kenyamanan TTD di desktop */
        cursor: crosshair;
        touch-action: none; /* Mencegah layar tergeser/scroll ketika corat-coret di ponsel */
    }
    
    /* Styling area kamera */
    .webcam-container {
        background-color: #f8f9fa;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
    }
    #player, #canvas {
        width: 100%;
        max-height: 280px; /* Diperbesar agar tampilan kamera lebih luas di desktop */
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    #player { background: #000; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header Modern -->
<div class="page-header d-print-none mb-4 mt-3">
    <div class="container-xl p-0">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-muted text-uppercase tracking-wide mb-1">
                    Layanan Publik Terpadu
                </div>
                <h2 class="page-title text-dark fw-bold">
                    <i class="ti ti-briefcase me-2 text-blue"></i> Pengisian Buku Tamu Dinas
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="<?= base_url('buku-tamu') ?>" class="btn btn-outline-secondary d-none d-sm-inline-block">
                    <i class="ti ti-arrow-left icon me-2"></i> Kembali ke Menu Utama
                </a>
                <a href="<?= base_url('buku-tamu') ?>" class="btn btn-outline-secondary d-sm-none btn-icon" aria-label="Kembali" title="Kembali">
                    <i class="ti ti-arrow-left icon"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-important alert-danger alert-dismissible mb-4" role="alert">
                <div class="d-flex">
                    <div><i class="ti ti-alert-circle icon me-2"></i></div>
                    <div><?= session()->getFlashdata('error') ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom p-4">
                <h3 class="card-title mb-0 fw-semibold text-muted">
                    <i class="ti ti-edit-circle me-2"></i> Form Registrasi Tamu Instansi
                </h3>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="<?= base_url('buku-tamu/store') ?>" method="post" enctype="multipart/form-data" id="tamuForm" autocomplete="off">
                    <?= csrf_field() ?>
                    <input type="hidden" name="jenis_tamu" value="khusus">
                    <input type="hidden" name="foto_wajah_base64" id="foto_wajah_base64">
                    <input type="hidden" name="tanda_tangan_base64" id="tanda_tangan_base64">

                    <div class="row">
                        <!-- KOLOM KIRI: Form Input (Diperlebar menjadi 8 kolom di Desktop) -->
                        <div class="col-lg-8">
                            
                            <!-- BAGIAN 1: Identitas Diri & Instansi -->
                            <div class="mb-4 text-blue fw-bold text-uppercase tracking-wide" style="font-size: 0.85rem;">
                                <i class="ti ti-id me-1"></i> 1. Identitas Diri & Instansi
                            </div>
                            
                            <div class="row bg-light rounded-3 p-3 mb-5 mx-0 border">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Nama Lengkap</label>
                                    <div class="input-group input-group-flat">
                                        <span class="input-group-text"><i class="ti ti-user"></i></span>
                                        <input type="text" class="form-control" name="nama_lengkap" placeholder="Bpk/Ibu..." required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIP / NIK <span class="text-muted fw-normal">(Opsional)</span></label>
                                    <input type="text" class="form-control" name="nip" placeholder="Masukkan Nomor Induk (Jika ada)">
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label required">Jabatan</label>
                                    <input type="text" class="form-control" name="jabatan" placeholder="Misal: Pengawas Madrasah" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Asal Instansi</label>
                                    <div class="input-group input-group-flat">
                                        <span class="input-group-text"><i class="ti ti-building"></i></span>
                                        <input type="text" class="form-control" name="alamat_instansi" placeholder="Misal: Kemenag Kota" required>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label required">No. HP / Kontak Aktif (WA)</label>
                                    <div class="input-group input-group-flat">
                                        <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                        <input type="text" class="form-control" name="no_hp" placeholder="08xxxxxxxxx" required>
                                    </div>
                                </div>
                            </div>

                            <!-- BAGIAN 2: Detail Kunjungan -->
                            <div class="mb-4 text-blue fw-bold text-uppercase tracking-wide" style="font-size: 0.85rem;">
                                <i class="ti ti-calendar-event me-1"></i> 2. Rincian Kunjungan
                            </div>

                            <div class="row mb-5">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Tujuan Kunjungan Resmi</label>
                                    <input type="text" class="form-control" name="tujuan_kunjungan" placeholder="Supervisi / Visitasi / Rapat..." required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Ingin Bertemu Dengan</label>
                                    <select class="form-select" name="id_pegawai_dituju" required>
                                        <option value="" hidden>-- Pilih Pegawai/Guru --</option>
                                        <?php foreach($guruList as $guru): ?>
                                            <option value="<?= $guru['id'] ?>"><?= esc($guru['nama_pegawai']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Dokumen Pendukung <span class="text-muted fw-normal">(Opsional)</span></label>
                                    <input type="file" class="form-control" name="dokumen_pendukung" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-hint">Unggah surat tugas atau dokumen terkait (PDF/Gambar, max 2MB).</small>
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="form-check bg-light p-3 rounded border">
                                        <input class="form-check-input ms-0 me-3" type="checkbox" name="consent_wa" checked>
                                        <span class="form-check-label fw-medium">
                                            Saya setuju untuk dihubungi terkait urusan dinas (Sesuai UU Pelindungan Data Pribadi).
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: Verifikasi (Kamera & TTD, disesuaikan menjadi 4 kolom) -->
                        <div class="col-lg-4 ps-lg-4 border-start-lg">
                            
                            <!-- BAGIAN 3: Verifikasi Kehadiran -->
                            <div class="mb-4 text-blue fw-bold text-uppercase tracking-wide" style="font-size: 0.85rem;">
                                <i class="ti ti-camera-check me-1"></i> 3. Verifikasi Kehadiran
                            </div>

                            <!-- Foto -->
                            <div class="mb-4">
                                <label class="form-label">Identitas Foto <span class="text-muted fw-normal">(Opsional)</span></label>
                                
                                <!-- Toggle Source -->
                                <div class="form-selectgroup form-selectgroup-boxes d-flex mb-3">
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="foto_source" value="webcam" class="form-selectgroup-input" checked onchange="toggleFotoMethod()">
                                        <span class="form-selectgroup-label d-flex align-items-center p-2">
                                            <span class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </span>
                                            <span class="form-selectgroup-label-content">
                                                <span class="d-block fw-medium">Ambil Foto</span>
                                            </span>
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="foto_source" value="upload" class="form-selectgroup-input" onchange="toggleFotoMethod()">
                                        <span class="form-selectgroup-label d-flex align-items-center p-2">
                                            <span class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </span>
                                            <span class="form-selectgroup-label-content">
                                                <span class="d-block fw-medium">Unggah File</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>

                                <!-- Container Webcam -->
                                <div id="container_webcam" class="webcam-container">
                                    <video id="player" autoplay playsinline></video>
                                    <canvas id="canvas" style="display:none;"></canvas>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary w-100" id="captureBtn">
                                            <i class="ti ti-camera me-2"></i> Ambil Foto Wajah
                                        </button>
                                        <button type="button" class="btn btn-warning w-100" id="retakeBtn" style="display:none;">
                                            <i class="ti ti-refresh me-2"></i> Ulangi Foto
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">Pastikan wajah terlihat jelas di kamera.</small>
                                </div>

                                <!-- Container Upload -->
                                <div id="container_upload" class="bg-light p-3 rounded-3 border" style="display: none;">
                                    <div class="mb-2">
                                        <label class="form-label small">Pilih File Foto (JPG/PNG, Max 2MB)</label>
                                        <input type="file" class="form-control" name="foto_wajah_file" id="foto_wajah_file" accept="image/*">
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Unggah dokumen identitas atau foto diri sebagai verifikasi resmi.</small>
                                </div>
                            </div>

                            <!-- TTD -->
                            <div class="mb-3">
                                <label class="form-label required">Tanda Tangan Digital</label>
                                <div class="signature-container">
                                    <div class="signature-pad">
                                        <div class="signature-pad--body">
                                            <canvas id="signature-canvas"></canvas>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted" style="font-size: 0.75rem;">Tanda tangan di dalam kotak</small>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="clear-signature">
                                            <i class="ti ti-eraser me-1"></i> Bersihkan
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Footer / Actions -->
                    <hr class="mt-4 mb-4">
                    <div class="d-flex justify-content-end align-items-center gap-2">
                        <a href="<?= base_url('buku-tamu') ?>" class="btn btn-link text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center gap-2" id="submitBtn">
                            <span id="submitText"><i class="ti ti-send"></i> Simpan Data Kunjungan</span>
                            <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Signature Pad Library dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>

<script>
    // --- WEBCAM LOGIC ---
    const player = document.getElementById('player');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const fotoBase64 = document.getElementById('foto_wajah_base64');
    let isCaptured = false;
 
    function toggleFotoMethod() {
        const method = document.querySelector('input[name="foto_source"]:checked').value;
        const containerWebcam = document.getElementById('container_webcam');
        const containerUpload = document.getElementById('container_upload');
        const inputBase64 = document.getElementById('foto_wajah_base64');
        const inputFile = document.getElementById('foto_wajah_file');

        if (method === 'webcam') {
            containerWebcam.style.display = 'block';
            containerUpload.style.display = 'none';
            inputFile.value = ''; 
        } else {
            containerWebcam.style.display = 'none';
            containerUpload.style.display = 'block';
            inputBase64.value = '';
            player.style.display = 'block';
            canvas.style.display = 'none';
            captureBtn.style.display = 'block';
            retakeBtn.style.display = 'none';
            isCaptured = false;
        }
    }

    navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
        .then((stream) => { player.srcObject = stream; })
        .catch((err) => { 
            console.error("Camera error:", err); 
            // Fallback UI jika kamera ditolak / error
            player.parentElement.innerHTML = '<div class="p-4 text-muted"><i class="ti ti-camera-off fs-1 d-block mb-2"></i>Akses kamera ditolak atau tidak ditemukan.</div>';
        });

    captureBtn.addEventListener('click', () => {
        canvas.width = player.videoWidth; canvas.height = player.videoHeight;
        context.drawImage(player, 0, 0, canvas.width, canvas.height);
        fotoBase64.value = canvas.toDataURL('image/jpeg', 0.8);
        player.style.display = 'none'; canvas.style.display = 'block';
        captureBtn.style.display = 'none'; retakeBtn.style.display = 'block';
        isCaptured = true;
    });

    retakeBtn.addEventListener('click', () => {
        fotoBase64.value = '';
        player.style.display = 'block'; canvas.style.display = 'none';
        captureBtn.style.display = 'block'; retakeBtn.style.display = 'none';
        isCaptured = false;
    });

    // --- SIGNATURE PAD LOGIC ---
    const signatureCanvas = document.getElementById('signature-canvas');
    
    // Resize canvas agar sesuai viewport / container CSS tanpa nge-stretch tintanya
    function resizeCanvas() {
        var data = signaturePad.isEmpty() ? null : signaturePad.toData();
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
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
        penColor: 'rgb(0, 0, 0)'
    });

    window.onresize = resizeCanvas;
    // Panggil saat document ready agar canvas tidak bug ukuran
    setTimeout(resizeCanvas, 200);

    document.getElementById('clear-signature').addEventListener('click', function () {
        signaturePad.clear();
    });

    // --- SUBMISSION LOGIC ---
    document.getElementById('tamuForm').addEventListener('submit', function(e) {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            alert("Mohon isi tanda tangan Anda pada kotak yang disediakan!");
            return false;
        }
        
        // Simpan TTD ke input hidden
        document.getElementById('tanda_tangan_base64').value = signaturePad.toDataURL('image/png');

        // Loading spinner
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitText').innerHTML = '<i class="ti ti-send"></i> Mengirim...';
        document.getElementById('submitSpinner').classList.remove('d-none');
    });
</script>
<?= $this->endSection() ?>