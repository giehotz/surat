<div class="row mb-3">
    <div class="col-12">
        <div class="card bg-info-lt border-info">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-info mb-0">
                            <i class="ti ti-template me-1"></i> Template Cepat
                        </label>
                        <small class="text-muted">Pilih template untuk isi otomatis</small>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="template_selector">
                            <option value="">-- Pilih Template Surat --</option>
                            <?php if (!empty($templates)): ?>
                                <?php foreach ($templates as $t): ?>
                                    <option value="<?= esc($t['slug']) ?>"><?= esc($t['nama']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="<?= base_url('surat-resmi/template') ?>" class="btn btn-outline-info btn-sm" target="_blank">
                            <i class="ti ti-settings me-1"></i> Kelola Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
