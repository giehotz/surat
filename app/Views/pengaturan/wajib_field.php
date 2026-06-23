<div class="tab-pane <?= ($active_tab ?? '') == 'wajib-field' ? 'active show' : '' ?>" id="tab-wajib-field">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="ti ti-list-check me-2 text-primary"></i>Pengaturan Wajib Isi Field Surat Keluar
        </h3>
    </div>
    <div class="card-body">
        
        <!-- Pindah Informasi ke Atas: UX yang baik memberi tahu aturan sebelum user bertindak -->
        <div class="alert alert-info bg-info-lt mb-4" role="alert">
            <div class="d-flex">
                <div>
                    <i class="ti ti-info-circle icon alert-icon text-info me-3 mt-1" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h4 class="alert-title mb-1">Panduan Pengaturan</h4>
                    <div class="text-secondary">
                        <ul class="mb-0 ps-3">
                            <li>Pilih field mana saja yang <strong>wajib diisi</strong> oleh pengguna saat membuat atau mengedit Surat Keluar.</li>
                            <li>Field dasar seperti "Tanggal Surat", "Tujuan Surat", dan "Perihal" sangat disarankan untuk diaktifkan.</li>
                            <li>Field "File Konsep Surat" akan otomatis divalidasi sesuai dengan tipe penyimpanan yang Anda pilih di pengaturan lain.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="<?= base_url('pengaturan/update-wajib-field') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-4">
                <h4 class="subheader text-muted mb-3">Daftar Field Surat Keluar</h4>
                
                <?php
                // Menambahkan mapping icon untuk visual cue yang lebih baik
                $available_fields = [
                    'nomor_surat' => ['label' => 'Nomor Surat', 'icon' => 'ti-123'],
                    'tanggal_surat' => ['label' => 'Tanggal Surat', 'icon' => 'ti-calendar-event'],
                    'tanggal_kirim' => ['label' => 'Tanggal Kirim', 'icon' => 'ti-send'],
                    'tujuan' => ['label' => 'Tujuan Surat', 'icon' => 'ti-building'],
                    'perihal' => ['label' => 'Perihal', 'icon' => 'ti-file-description'],
                    'file_konsep' => ['label' => 'File Konsep Surat', 'icon' => 'ti-file-upload']
                ];
                
                // If no required fields are set, initialize as empty array
                if (!isset($wajib_fields) || !is_array($wajib_fields)) {
                    $wajib_fields = [];
                }
                ?>
                
                <div class="row g-3">
                    <?php foreach($available_fields as $field => $data): ?>
                        <?php 
                            // Check if field exists in required fields
                            $is_required = false;
                            foreach ($wajib_fields as $wajib) {
                                if (isset($wajib['field_name']) && $wajib['field_name'] === $field && isset($wajib['is_required']) && intval($wajib['is_required']) === 1) {
                                    $is_required = true;
                                    break;
                                }
                            }
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <!-- Menggunakan Label sebagai Container Card agar seluruh area bisa diklik -->
                            <label class="form-check form-switch card card-body p-3 mb-0 h-100 cursor-pointer shadow-sm hover-shadow-md transition-all" for="switch_<?= $field ?>" style="cursor: pointer;">
                                <div class="d-flex align-items-center w-100">
                                    <div class="bg-light-subtle rounded p-2 me-3">
                                        <i class="ti <?= $data['icon'] ?> text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-check-label fw-bold d-block" style="font-size: 1rem;"><?= $data['label'] ?></div>
                                        <div class="text-muted small mt-1">Status: <span class="<?= $is_required ? 'text-success' : 'text-secondary' ?>"><?= $is_required ? 'Wajib' : 'Opsional' ?></span></div>
                                    </div>
                                    <div class="ms-3">
                                        <input class="form-check-input m-0" type="checkbox" role="switch" id="switch_<?= $field ?>" name="wajib_field[<?= $field ?>]" value="1" style="transform: scale(1.2);" <?= $is_required ? 'checked' : '' ?>>
                                        <input type="hidden" name="all_fields[]" value="<?= $field ?>">
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ACTION BUTTON -->
            <div class="hr-text mt-4 mb-4">Pastikan pengaturan sudah sesuai</div>
            
            <div class="mt-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="ti ti-device-floppy"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>