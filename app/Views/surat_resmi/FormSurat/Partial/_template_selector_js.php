<script>
    // Data Templates
    const suratTemplates = {
        'gangguan_absen': {
            perihal: 'Pemberitahuan Gangguan',
            lampiran: '-',
            tujuan_nama: 'Admin Kepegawaian Kementerian Agama',
            tujuan_alamat: 'Kabupaten Tanggamus',
            salam_pembuka: 'Dengan hormat,',
            isi_surat: `<p>Sehubungan adanya gangguan pada aplikasi PUSAKA, maka beberapa pegawai tidak bisa melakukan presensi pada aplikasi PUSAKA sebagaimana seharusnya. Gangguan yang dimaksud terjadi pada:</p>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;" border="0">
                <tbody>
                    <tr>
                        <td style="width: 25%;">Hari/Tanggal</td>
                        <td style="width: 5%;">:</td>
                        <td style="width: 70%;">[Isi Hari, Tanggal]</td>
                    </tr>
                    <tr>
                        <td style="width: 25%;">Waktu</td>
                        <td style="width: 5%;">:</td>
                        <td style="width: 70%;">[Isi Waktu] WIB s.d. [Isi Waktu] WIB</td>
                    </tr>
                </tbody>
            </table>
            <p>Demikian surat pemberitahuan ini kami sampaikan, untuk digunakan sebagai keterangan gangguan absensi pada waktu yang dimaksud.</p>`,
            salam_penutup: 'Kepala Madrasah,',
            pengirim_jabatan: 'Kepala Madrasah',
            pengirim_nama: 'NAMA KEPALA MADRASAH, M.Pd',
            pengirim_nip: '197005272007011022'
        },
        'keterangan_aktif': {
            perihal: 'Surat Keterangan Aktif Mengajar',
            lampiran: '-',
            tujuan_nama: 'Kepada Yth. Pihak Terkait',
            tujuan_alamat: 'di Tempat',
            salam_pembuka: 'Yang bertanda tangan di bawah ini:',
            isi_surat: `<table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;" border="0">
                <tbody>
                    <tr><td style="width: 25%;">Nama</td><td style="width: 5%;">:</td><td style="width: 70%;">[Nama Kepala]</td></tr>
                    <tr><td style="width: 25%;">NIP</td><td style="width: 5%;">:</td><td style="width: 70%;">[NIP Kepala]</td></tr>
                    <tr><td style="width: 25%;">Jabatan</td><td style="width: 5%;">:</td><td style="width: 70%;">Kepala Madrasah</td></tr>
                </tbody>
            </table>
            <p>Menerangkan dengan sesungguhnya bahwa:</p>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;" border="0">
                <tbody>
                    <tr><td style="width: 25%;">Nama</td><td style="width: 5%;">:</td><td style="width: 70%;">[Nama Guru]</td></tr>
                    <tr><td style="width: 25%;">NIP</td><td style="width: 5%;">:</td><td style="width: 70%;">[NIP Guru]</td></tr>
                    <tr><td style="width: 25%;">Tugas</td><td style="width: 5%;">:</td><td style="width: 70%;">Guru Mata Pelajaran</td></tr>
                </tbody>
            </table>
            <p>Adalah benar-benar aktif mengajar di madrasah kami terhitung sejak tanggal [Tanggal] sampai dengan saat ini.</p>
            <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>`,
            salam_penutup: 'Hormat kami,',
            pengirim_jabatan: 'Kepala Madrasah',
            pengirim_nama: 'NAMA KEPALA MADRASAH, M.Pd',
            pengirim_nip: '197005272007011022'
        }
    };

    $(document).ready(function() {
        $('#template_selector').change(function() {
            var selected = $(this).val();
            if (selected && suratTemplates[selected]) {
                var tpl = suratTemplates[selected];
                
                $('input[name="perihal"]').val(tpl.perihal);
                $('input[name="lampiran"]').val(tpl.lampiran);
                $('input[name="tujuan_nama"]').val(tpl.tujuan_nama);
                $('input[name="tujuan_alamat"]').val(tpl.tujuan_alamat);
                $('input[name="salam_pembuka"]').val(tpl.salam_pembuka);
                $('input[name="salam_penutup"]').val(tpl.salam_penutup);
                $('input[name="pengirim_jabatan"]').val(tpl.pengirim_jabatan);
                $('input[name="pengirim_nama"]').val(tpl.pengirim_nama);
                $('input[name="pengirim_nip"]').val(tpl.pengirim_nip);

                // Set isi surat di TinyMCE
                if (tinymce.get('isi_surat_editor')) {
                    tinymce.get('isi_surat_editor').setContent(tpl.isi_surat);
                } else {
                    $('#isi_surat_editor').val(tpl.isi_surat);
                }
            }
        });
    });
</script>
