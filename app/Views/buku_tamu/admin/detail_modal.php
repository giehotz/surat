<!-- Modal Detail Kunjungan Tamu -->
<div class="modal modal-blur fade" id="modalDetailTamu" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kunjungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Sisi Kiri: Info Tamu & Gambar -->
                    <div class="col-md-5 border-end">
                        <div class="mb-3 text-center gambar-tamu bg-light p-2 rounded">
                            <!-- Foto dan TTD dimasukkan via JS -->
                        </div>
                        <h3 class="mb-1 nama-tamu fw-bold"></h3>
                        <div class="text-muted mb-2"><i class="ti ti-map-pin me-1"></i> <span class="asal-tamu"></span></div>
                        <div class="text-muted"><i class="ti ti-phone me-1"></i> <span class="telepon-tamu"></span></div>
                    </div>
                    
                    <!-- Sisi Kanan: Form Aksi -->
                    <div class="col-md-7 ps-md-4">
                        <form id="form-update-kunjungan" method="post">
                            <?= csrf_field() ?>
                            
                            <h4 class="mb-3">Update Status Layanan</h4>
                            <div class="mb-4">
                                <select class="form-select" name="status_kunjungan">
                                    <option value="menunggu">Menunggu</option>
                                    <option value="diterima">Diterima / Sedang Dilayani</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="batal">Batal / Ditolak</option>
                                </select>
                            </div>

                            <h4 class="mb-3">Catatan / Tindak Lanjut</h4>
                            <div class="mb-3">
                                <textarea class="form-control" name="tindak_lanjut" rows="4" placeholder="Tuliskan catatan hasil pertemuan atau instruksi selanjutnya..." required></textarea>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
