<div class="tab-pane <?= ($active_tab ?? '') == 'buku-tamu' ? 'active show' : '' ?>" id="tab-buku-tamu">
    <div class="card-header border-bottom">
        <h3 class="card-title">
            <i class="ti ti-notebook me-2 text-green"></i>Kontrol Operasional Buku Tamu
        </h3>
    </div>
    
    <div class="card-body">
        <form action="<?= base_url('pengaturan/update-buku-tamu') ?>" method="post">
            <?= csrf_field() ?>

            <!-- STATUS OPERASIONAL -->
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h4 class="mb-1">Status Layanan Publik</h4>
                    <p class="text-muted small mb-0">Tentukan apakah pendaftaran tamu saat ini dibuka atau ditutup secara manual.</p>
                </div>
                <div class="col-auto">
                    <select class="form-select w-auto" name="buku_tamu_mode">
                        <option value="auto" <?= ($settings['buku_tamu_mode'] ?? 'auto') == 'auto' ? 'selected' : '' ?>>Otomatis (Ikuti Jadwal)</option>
                        <option value="open" <?= ($settings['buku_tamu_mode'] ?? 'auto') == 'open' ? 'selected' : '' ?>>Buka Paksa (Selalu Buka)</option>
                        <option value="closed" <?= ($settings['buku_tamu_mode'] ?? 'auto') == 'closed' ? 'selected' : '' ?>>Tutup Paksa (Layanan Libur)</option>
                    </select>
                </div>
            </div>

            <hr class="mb-4">

            <!-- JADWAL OTOMATIS -->
            <h4 class="subheader text-muted mb-3">Jadwal Operasional Otomatis</h4>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Jam Mulai Layanan</label>
                    <input type="time" class="form-control" name="buku_tamu_open_time" value="<?= esc($settings['buku_tamu_open_time'] ?? '07:30') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jam Selesai Layanan</label>
                    <input type="time" class="form-control" name="buku_tamu_close_time" value="<?= esc($settings['buku_tamu_close_time'] ?? '16:00') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Hari Operasional</label>
                    <div class="form-selectgroup">
                        <?php 
                        $workDays = explode(',', $settings['buku_tamu_work_days'] ?? '1,2,3,4,5,6');
                        $days = [
                            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 0 => 'Minggu'
                        ];
                        foreach ($days as $val => $label):
                        ?>
                        <label class="form-selectgroup-item">
                            <input type="checkbox" name="buku_tamu_work_days[]" value="<?= $val ?>" class="form-selectgroup-input" <?= in_array((string)$val, $workDays) ? 'checked' : '' ?>>
                            <span class="form-selectgroup-label"><?= $label ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <hr class="mb-4">

            <!-- BOT & SPAM PROTECTION -->
            <h4 class="subheader text-muted mb-3">Proteksi Bot & Anti-Spam</h4>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="buku_tamu_honeypot" value="1" <?= ($settings['buku_tamu_honeypot'] ?? '1') == '1' ? 'checked' : '' ?>>
                        <span class="form-check-label fw-bold">Aktifkan Honeypot (Bot Trapping)</span>
                    </label>
                    <p class="text-muted small ms-4">Menambahkan kolom jebakan tersembunyi yang hanya bisa dilihat oleh Bot. Jika sistem mendeteksiBot mengisi kolom ini, kiriman akan ditolak otomatis.</p>
                </div>
                <div class="col-12 border-top pt-2">
                    <label class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="buku_tamu_throttling" value="1" <?= ($settings['buku_tamu_throttling'] ?? '1') == '1' ? 'checked' : '' ?>>
                        <span class="form-check-label fw-bold">Aktifkan Rate Limiting (Throttling)</span>
                    </label>
                    <p class="text-muted small ms-4">Membatasi jumlah pengiriman data per jam per alamat IP untuk mencegah serangan spamming data fiktif.</p>
                </div>
            </div>

            <hr class="mb-4">

            <!-- CLOSED MESSAGE -->
            <div class="mb-4">
                <label class="form-label">Pesan Saat Layanan Tutup</label>
                <textarea class="form-control" name="buku_tamu_closed_message" rows="3" placeholder="Contoh: Mohon maaf, saat ini pendaftaran buku tamu sudah ditutup diluar jam operasional. Silakan datang kembali besok."><?= esc($settings['buku_tamu_closed_message'] ?? 'Mohon maaf, layanan buku tamu saat ini sedang ditutup. Silakan datang kembali pada jam kerja.') ?></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success d-flex align-items-center gap-2">
                    <i class="ti ti-device-floppy"></i> Simpan Konfigurasi Buku Tamu
                </button>
            </div>
        </form>
    </div>
</div>
