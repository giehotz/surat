<script>
    // Template data dimuat dari database via PHP
    var suratTemplates = <?= json_encode($templates, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    $(document).ready(function() {
        // Allow re-selecting the same template
        $('#template_selector').mousedown(function() {
            $(this).data('prev', $(this).val());
        });

        $('#template_selector').change(function() {
            var selected = $(this).val();
            if (!selected) return;

            var tpl = suratTemplates.find(function(t) { return t.slug === selected; });
            if (!tpl) return;

            var self = this;
            Swal.fire({
                title: 'Gunakan Template?',
                text: 'Data yang sudah diisi pada form akan tertimpa oleh template "' + tpl.nama + '".',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, gunakan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#206bc4',
            }).then(function(result) {
                if (result.isConfirmed) {
                    $('input[name="perihal"]').val(tpl.perihal || '');
                    $('input[name="lampiran"]').val(tpl.lampiran || '-');
                    $('input[name="tujuan_nama"]').val(tpl.tujuan_nama || '');
                    $('input[name="tujuan_alamat"]').val(tpl.tujuan_alamat || '');
                    $('input[name="salam_pembuka"]').val(tpl.salam_pembuka || 'Dengan hormat,');
                    $('input[name="salam_penutup"]').val(tpl.salam_penutup || 'Hormat kami,');
                    $('input[name="pengirim_jabatan"]').val(tpl.pengirim_jabatan || '');
                    $('input[name="pengirim_nama"]').val(tpl.pengirim_nama || '');
                    $('input[name="pengirim_nip"]').val(tpl.pengirim_nip || '');
                    if (tinymce.get('isi_surat_editor')) {
                        tinymce.get('isi_surat_editor').setContent(tpl.isi_surat || '');
                    } else {
                        $('#isi_surat_editor').val(tpl.isi_surat || '');
                    }
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Template "' + tpl.nama + '" diterapkan.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    $(self).val($(self).data('prev') || '');
                }
            });
        });
    });
</script>
