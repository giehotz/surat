<!-- Modal Detail Kunjungan Tamu -->
<div class="modal modal-blur fade" id="modalDetailTamu" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="ti ti-id me-2 text-primary"></i> Detail Informasi Kunjungan Tamu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Sisi Kiri: Profil & Bukti Kehadiran (Foto & TTD) -->
                    <div class="col-lg-4 border-end">
                        <div class="text-center mb-3">
                            <div class="gambar-tamu bg-light p-2 rounded border mb-2 d-flex flex-column align-items-center justify-content-center" style="min-height: 180px;">
                                <!-- Foto dan TTD dimasukkan via JS -->
                            </div>
                            <div class="badge-jenis-tamu mb-2"></div>
                        </div>

                        <h3 class="mb-1 nama-tamu fw-bold text-dark"></h3>
                        <div class="info-nip-jabatan small text-muted mb-2"></div>

                        <div class="list-group list-group-flush mb-3 small">
                            <div class="list-group-item px-0 py-1.5 d-flex align-items-start">
                                <i class="ti ti-building me-2 mt-0.5 text-muted"></i>
                                <div>
                                    <span class="text-muted d-block small">Instansi / Alamat:</span>
                                    <span class="asal-tamu fw-medium text-dark"></span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-1.5 d-flex align-items-start">
                                <i class="ti ti-phone me-2 mt-0.5 text-muted"></i>
                                <div>
                                    <span class="text-muted d-block small">No. WhatsApp / HP:</span>
                                    <span class="telepon-tamu fw-medium text-dark"></span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-1.5 d-flex align-items-start">
                                <i class="ti ti-calendar-time me-2 mt-0.5 text-muted"></i>
                                <div>
                                    <span class="text-muted d-block small">Waktu Kedatangan:</span>
                                    <span class="waktu-tamu fw-medium text-dark"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Direct Chat WhatsApp -->
                        <div class="box-wa-link mt-3">
                            <a href="#" target="_blank" class="btn btn-outline-success w-100 btn-wa-direct">
                                <i class="ti ti-brand-whatsapp me-2 fs-3"></i> Hubungi Tamu via WhatsApp
                            </a>
                        </div>
                    </div>
                    
                    <!-- Sisi Kanan: Detail Kunjungan & Form Tindak Lanjut -->
                    <div class="col-lg-8 ps-lg-4">
                        <!-- Kartu Rincian Kunjungan -->
                        <div class="card bg-light-lt border mb-4">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Pegawai / Guru yang Dituju</label>
                                        <div class="fw-semibold text-primary pegawai-dituju">
                                            <i class="ti ti-user-check me-1"></i> <span>-</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Siswa Terkait (Wali)</label>
                                        <div class="fw-semibold text-dark siswa-dituju">
                                            <i class="ti ti-school me-1"></i> <span>-</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Tujuan Kunjungan</label>
                                        <div class="p-2 bg-surface rounded border tujuan-kunjungan text-dark small">
                                            -
                                        </div>
                                    </div>
                                    <div class="col-12 area-pesan-kesan d-none">
                                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Pesan & Kesan Tamu</label>
                                        <div class="p-2 bg-surface rounded border text-muted fst-italic pesan-kesan small">
                                            -
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Dokumen Pendukung / Surat Tugas</label>
                                        <div class="area-dokumen">
                                            <!-- Link Dokumen dimasukkan via JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Update Status & Tindak Lanjut -->
                        <div class="card border-primary-subtle">
                            <div class="card-header bg-primary-lt py-2 px-3">
                                <h4 class="card-title text-primary mb-0">
                                    <i class="ti ti-edit me-1.5"></i> Update Status Layanan & Catatan Tindak Lanjut
                                </h4>
                            </div>
                            <div class="card-body p-3">
                                <form id="form-update-kunjungan" method="post">
                                    <?= csrf_field() ?>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label required fw-bold small">Status Kunjungan</label>
                                            <select class="form-select" name="status_kunjungan" required>
                                                <option value="menunggu">🟡 Menunggu</option>
                                                <option value="diterima">🔵 Diterima / Sedang Dilayani</option>
                                                <option value="selesai">🟢 Selesai</option>
                                                <option value="batal">🔴 Batal / Ditolak</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold small">Catatan Hasil Pertemuan / Tindak Lanjut</label>
                                            <textarea class="form-control" name="tindak_lanjut" rows="3" placeholder="Tuliskan ringkasan hasil pertemuan, instruksi, atau catatan petugas resepsionis..."></textarea>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btn-save-kunjungan">
                                                <i class="ti ti-device-floppy me-1.5"></i> Simpan Pembaruan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary ms-auto" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
