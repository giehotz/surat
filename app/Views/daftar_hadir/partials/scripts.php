<!-- TinyMCE Rich Text Editor CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    // Inisialisasi data dari backend
    const kepsekNama = <?= json_encode($appSettings['pejabat_kepsek_nama'] ?? 'SIPULLOH, M.Pd') ?>;
    const kepsekNip  = <?= json_encode($appSettings['pejabat_kepsek_nip'] ?? '-') ?>;
    const madrasahNama = <?= json_encode($appSettings['sekolah_nama'] ?? 'MIN 2 TANGGAMUS') ?>;

    let tinyMceInitialized = false;

    // Helper escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Format tanggal Indonesia lengkap dengan nama hari
    function formatTanggalIndoJS(dateStr) {
        if (!dateStr) return '-';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;

        const d = new Date(parts[0], parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
        const namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const namaBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const hari = namaHari[d.getDay()];
        const tgl  = parseInt(parts[2], 10);
        const bln  = namaBulan[parseInt(parts[1], 10) - 1];
        const thn  = parts[0];

        return `${hari}, ${tgl} ${bln} ${thn}`;
    }

    // Format tanggal Indonesia polos (tanpa nama hari)
    function getTanggalIndoPlain(dateStr) {
        if (!dateStr) return '-';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const namaBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return `${parseInt(parts[2], 10)} ${namaBulan[parseInt(parts[1], 10) - 1]} ${parts[0]}`;
    }

    // Fungsi untuk memperbarui tampilan tombol Tutup/Keluar Fullscreen
    function updateTombolExitFullscreen(isFullscreen) {
        const btnExit = document.getElementById('btn-exit-fullscreen');
        if (!btnExit) return;

        let active = false;
        if (typeof isFullscreen === 'boolean') {
            active = isFullscreen;
        } else {
            active = document.body.classList.contains('tox-fullscreen') ||
                     document.querySelector('.tox.tox-tinymce--fullscreen') !== null;
        }

        btnExit.style.display = active ? 'inline-flex' : 'none';
    }

    // Inisialisasi TinyMCE dengan callback saat selesai init
    function initTinyMCE() {
        if (tinymce.get('isi_notulen_editor')) {
            return;
        }
        tinymce.init({
            selector: '#isi_notulen_editor',
            height: 380,
            menubar: false,
            plugins: 'lists link table code fullscreen',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright justify | bullist numlist outdent indent | table link code | fullscreen',
            content_style: 'body { font-family: "Times New Roman", Times, serif; font-size: 11pt; line-height: 1.6; color: #000; padding: 10px; }',
            init_instance_callback: function (editor) {
                tinyMceInitialized = true;
                editor.on('input change keyup undo redo SetContent', function () {
                    editor.save();
                    renderLembarNotulen();
                });

                // Deteksi perubahan status fullscreen
                editor.on('FullscreenStateChanged', function (e) {
                    updateTombolExitFullscreen(e.state);
                });

                // Tangani tombol ESC di dalam iframe editor
                editor.on('keydown', function (e) {
                    if (e.key === 'Escape' || e.keyCode === 27) {
                        const btnExit = document.getElementById('btn-exit-fullscreen');
                        if (btnExit && btnExit.style.display !== 'none') {
                            toggleEditorFullscreen();
                        }
                    }
                });
            }
        });
    }

    // Ubah Metode Notulen (Editor vs Upload vs Manual)
    function ubahMetodeNotulen(metode) {
        const boxEditor = document.getElementById('box-editor-notulen');
        const boxUpload = document.getElementById('box-upload-notulen');
        const boxManual = document.getElementById('box-manual-notulen');

        if (boxEditor) boxEditor.style.display = (metode === 'editor') ? 'block' : 'none';
        if (boxUpload) boxUpload.style.display = (metode === 'upload') ? 'block' : 'none';
        if (boxManual) boxManual.style.display = (metode === 'manual') ? 'block' : 'none';

        if (metode === 'editor') {
            initTinyMCE();
        }
        renderLembarNotulen();
    }

    // Muat Kerangka Standar Notulen Rapat
    function muatTemplateNotulen() {
        const templateHtml = `
<p><strong>A. Susunan Acara Rapat:</strong></p>
<ol>
    <li>Pembukaan</li>
    <li>Pengarahan Kepala Madrasah</li>
    <li>Pembahasan Pokok Program & Agenda</li>
    <li>Tanya Jawab, Masukan & Saran</li>
    <li>Kesimpulan & Tindak Lanjut</li>
    <li>Penutup</li>
</ol>
<p><strong>B. Jalannya Rapat & Pembahasan:</strong></p>
<p>1. <strong>Pembukaan:</strong> Rapat dibuka secara resmi dengan membaca Basmalah bersama-sama.</p>
<p>2. <strong>Pengarahan Kepala Madrasah:</strong> Menyampaikan evaluasi capaian program madrasah, penguatan kedisiplinan guru dan pegawai, serta kesiapan administrasi pembelajaran semester.</p>
<p>3. <strong>Pembahasan Program Kerja:</strong></p>
<ul>
    <li>Peningkatan mutu pembelajaran dan kelengkapan perangkat ajar guru kelas/mapel.</li>
    <li>Peningkatan kedisiplinan siswa, kegiatan ekstrakurikuler, dan kebersihan lingkungan madrasah.</li>
    <li>Koordinasi pelayanan administrasi persuratan dan ketertiban buku tamu dinas/umum.</li>
</ul>
<p>4. <strong>Kesimpulan & Tindak Lanjut:</strong></p>
<ul>
    <li>Seluruh guru menyelesaikan perangkat pembelajaran tepat waktu.</li>
    <li>Piket madrasah memperketat pencatatan presensi kehadiran dan pelayanan tamu.</li>
</ul>
<p>5. <strong>Penutup:</strong> Rapat ditutup dengan doa bersama dan ucapan Hamdalah.</p>
        `.trim();

        const ed = tinymce.get('isi_notulen_editor');
        if (ed && ed.initialized) {
            ed.setContent(templateHtml);
            ed.save();
        } else {
            const el = document.getElementById('isi_notulen_editor');
            if (el) el.value = templateHtml;
        }
        renderLembarNotulen();
    }

    // Toggle Mode Fullscreen (Bebas Gangguan) secara aman
    function toggleEditorFullscreen() {
        const ed = tinymce.get('isi_notulen_editor');
        if (ed && ed.initialized) {
            try {
                ed.focus();
                ed.execCommand('mceFullScreen');
            } catch (err) {
                console.warn('Fallback mceFullScreen:', err);
                const btn = document.querySelector('.tox .tox-tbtn[title*="Fullscreen"], .tox .tox-tbtn[aria-label*="Fullscreen"]');
                if (btn) btn.click();
            }
        } else {
            initTinyMCE();
            setTimeout(function() {
                const editor = tinymce.get('isi_notulen_editor');
                if (editor) {
                    try {
                        editor.focus();
                        editor.execCommand('mceFullScreen');
                    } catch (e) {
                        console.warn(e);
                    }
                }
            }, 400);
        }

        // Sinkronisasi status tombol keluar fullscreen
        setTimeout(function() {
            updateTombolExitFullscreen();
        }, 150);
        setTimeout(function() {
            updateTombolExitFullscreen();
        }, 400);
    }

    // Tangani tombol ESC di tingkat window/document
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            const btnExit = document.getElementById('btn-exit-fullscreen');
            if (btnExit && btnExit.style.display !== 'none') {
                toggleEditorFullscreen();
            }
        }
    });

    // Buka Modal Naskah Notulen Tersimpan
    function lihatNaskahModal(judul, htmlContent) {
        const judulEl = document.getElementById('modal-naskah-judul');
        const kontenEl = document.getElementById('modal-naskah-konten');
        if (judulEl) judulEl.textContent = 'Notulen: ' + judul;
        if (kontenEl) kontenEl.innerHTML = htmlContent || '<em class="text-muted">Tidak ada teks notulen.</em>';
        const modalEl = document.getElementById('modal-preview-naskah');
        if (modalEl) {
            const myModal = new bootstrap.Modal(modalEl);
            myModal.show();
        }
    }

    // Konfirmasi Hapus SweetAlert2
    function konfirmasiHapus(id, judul) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Arsip Notulen?',
                text: 'Arsip notulen "' + judul + '" akan dihapus permanen beserta berkasnya.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-del-' + id);
                    if (form) form.submit();
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus arsip notulen "' + judul + '"?')) {
                const form = document.getElementById('form-del-' + id);
                if (form) form.submit();
            }
        }
    }

    // Fungsi Utama Render Lembar Notulen Rapat (Hoisted Top-Level)
    function renderLembarNotulen() {
        const container = document.getElementById('container-lembar-notulen');
        if (!container) return;
        container.innerHTML = '';

        const inputLembar = document.getElementById('input-jumlah-lembar');
        const jumlahLembar = parseInt(inputLembar ? inputLembar.value : 0, 10) || 0;
        if (jumlahLembar <= 0) return;

        const inputJudul      = document.getElementById('input-judul');
        const inputSubjudul   = document.getElementById('input-subjudul');
        const inputTanggal    = document.getElementById('input-tanggal');
        const inputWaktu      = document.getElementById('input-waktu');
        const inputTempat     = document.getElementById('input-tempat');
        const inputNotulis    = document.getElementById('input-nama-notulis');
        const inputNipNotulis = document.getElementById('input-nip-notulis');

        const tglVal = inputTanggal ? inputTanggal.value : '';
        const tglFormatted = formatTanggalIndoJS(tglVal);
        const tglPlain = getTanggalIndoPlain(tglVal);
        const namaNotulis = (inputNotulis ? inputNotulis.value.trim() : '') || '.........................................';
        const nipNotulisVal = inputNipNotulis ? inputNipNotulis.value.trim() : '';
        const nipNotulisText = nipNotulisVal ? `NIP. ${nipNotulisVal}` : 'NIP. -';

        const radioMetode = document.querySelector('input[name="metode_notulen"]:checked');
        const selectedMetode = radioMetode ? radioMetode.value : 'editor';

        let teksNotulenHtml = '';
        const ed = tinymce.get('isi_notulen_editor');
        if (ed && ed.initialized) {
            teksNotulenHtml = ed.getContent();
        } else {
            const el = document.getElementById('isi_notulen_editor');
            if (el) teksNotulenHtml = el.value;
        }

        const judulText = (inputJudul ? inputJudul.value : '') || 'DEWAN GURU DAN STAF';
        const waktuText = (inputWaktu ? inputWaktu.value : '') || '-';
        const tempatText = (inputTempat ? inputTempat.value : '') || '-';
        const agendaText = (inputSubjudul ? inputSubjudul.value : '') || 'Pembahasan Agenda Madrasah';

        for (let page = 1; page <= jumlahLembar; page++) {
            const isLastPage = (page === jumlahLembar);
            const pageEl = document.createElement('div');
            pageEl.className = 'page-a4 bg-white text-dark page-notulen';

            // Header Notulen
            let headerHtml = `
                <div class="d-flex justify-content-between align-items-center mb-3 text-muted small" style="font-size: 9pt;">
                    <span>NOTULEN RAPAT - ${madrasahNama}</span>
                    <span>Lembar ${page} dari ${jumlahLembar}</span>
                </div>
                <div class="text-center mb-4">
                    <h3 class="fw-bold uppercase mb-1" style="font-size: 13pt; text-decoration: underline;">NOTULEN RAPAT</h3>
                    <div class="fw-bold" style="font-size: 11pt;">${escapeHtml(judulText)}</div>
                </div>
            `;

            // Jika halaman pertama, sertakan tabel metadata rapat
            if (page === 1) {
                headerHtml += `
                    <table class="table-info mb-4" style="width: 100%; font-size: 11pt;">
                        <tr>
                            <td style="width: 130px; font-weight: bold; vertical-align: top;">Hari / Tanggal</td>
                            <td style="width: 15px; vertical-align: top;">:</td>
                            <td style="vertical-align: top;">${tglFormatted}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; vertical-align: top;">Waktu</td>
                            <td style="vertical-align: top;">:</td>
                            <td style="vertical-align: top;">${escapeHtml(waktuText)}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; vertical-align: top;">Tempat</td>
                            <td style="vertical-align: top;">:</td>
                            <td style="vertical-align: top;">${escapeHtml(tempatText)}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; vertical-align: top;">Agenda / Bahasan</td>
                            <td style="vertical-align: top;">:</td>
                            <td style="vertical-align: top;">${escapeHtml(agendaText)}</td>
                        </tr>
                    </table>
                `;
            }

            // Isi Body Notulen
            let bodyHtml = '';
            if (selectedMetode === 'editor' && teksNotulenHtml && teksNotulenHtml.trim() !== '') {
                bodyHtml = `
                    <div class="notulen-content-rich mb-4" style="min-height: ${isLastPage ? '380px' : '650px'};">
                        ${teksNotulenHtml}
                    </div>
                `;
            } else if (selectedMetode === 'upload') {
                bodyHtml = `
                    <div class="border border-dashed p-4 text-center my-4 text-muted rounded">
                        <i class="ti ti-file-upload fs-1 d-block mb-2 text-success"></i>
                        <div class="fw-bold">Berkas Notulen Terlampir Secara Terpisah (Upload Dokumen)</div>
                        <div class="small">Dokumen digital diarsipkan pada sistem (PDF / Word / Scan).</div>
                    </div>
                `;
            } else {
                // Mode garis titik-titik (dotted lines)
                const lineCount = isLastPage ? 16 : 24;
                let linesHtml = '<div class="mb-4">';
                for (let l = 1; l <= lineCount; l++) {
                    linesHtml += '<div class="notulen-dotted-line">&nbsp;</div>';
                }
                linesHtml += '</div>';
                bodyHtml = linesHtml;
            }

            // Tanda Tangan Pengesahan (2 Pihak) hanya di lembar TERAKHIR
            let ttdHtml = '';
            if (isLastPage) {
                ttdHtml = `
                    <div class="row justify-content-between mt-4" style="page-break-inside: avoid; font-size: 11pt;">
                        <div class="col-5 text-center">
                            <div>&nbsp;</div>
                            <div class="mb-5">Notulis Rapat,</div>
                            <div style="height: 50px;"></div>
                            <div class="fw-bold" style="text-decoration: underline;">
                                ${escapeHtml(namaNotulis)}
                            </div>
                            <div>${escapeHtml(nipNotulisText)}</div>
                        </div>
                        <div class="col-5 text-center">
                            <div>Purwodadi, <span>${tglPlain}</span></div>
                            <div class="mb-5">Kepala MIN 2 Tanggamus,</div>
                            <div style="height: 50px;"></div>
                            <div class="fw-bold" style="text-decoration: underline;">
                                ${escapeHtml(kepsekNama)}
                            </div>
                            <div>NIP. ${escapeHtml(kepsekNip)}</div>
                        </div>
                    </div>
                `;
            }

            pageEl.innerHTML = headerHtml + bodyHtml + ttdHtml;
            container.appendChild(pageEl);
        }
    }

    // Attach listener saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        const inputJudul        = document.getElementById('input-judul');
        const inputSubjudul     = document.getElementById('input-subjudul');
        const inputTanggal      = document.getElementById('input-tanggal');
        const inputWaktu        = document.getElementById('input-waktu');
        const inputTempat       = document.getElementById('input-tempat');
        const inputLembar       = document.getElementById('input-jumlah-lembar');
        const inputNotulis      = document.getElementById('input-nama-notulis');
        const inputNipNotulis   = document.getElementById('input-nip-notulis');
        const toggleKop         = document.getElementById('toggle-kop');
        const toggleTtdHadir    = document.getElementById('toggle-ttd-hadir');

        const previewJudul          = document.getElementById('preview-judul');
        const previewSubjudul       = document.getElementById('preview-subjudul');
        const previewTanggal        = document.getElementById('preview-tanggal');
        const previewTanggalTtdHadir= document.getElementById('preview-tanggal-ttd-hadir');
        const previewWaktu          = document.getElementById('preview-waktu');
        const previewTempat         = document.getElementById('preview-tempat');
        const sectionKop            = document.getElementById('section-kop');
        const sectionTtdHadir       = document.getElementById('section-ttd-hadir');

        // Live update listener form agenda
        if (inputJudul) {
            inputJudul.addEventListener('input', function() {
                if (previewJudul) previewJudul.textContent = this.value || 'DAFTAR HADIR RAPAT';
                renderLembarNotulen();
            });
        }

        if (inputSubjudul) {
            inputSubjudul.addEventListener('input', function() {
                if (previewSubjudul) {
                    previewSubjudul.textContent = this.value;
                    previewSubjudul.style.display = this.value.trim() === '' ? 'none' : 'block';
                }
                renderLembarNotulen();
            });
        }

        if (inputTanggal) {
            inputTanggal.addEventListener('change', function() {
                const formatted = formatTanggalIndoJS(this.value);
                if (previewTanggal) previewTanggal.textContent = formatted;
                if (previewTanggalTtdHadir) previewTanggalTtdHadir.textContent = formatted;
                renderLembarNotulen();
            });
        }

        if (inputWaktu) {
            inputWaktu.addEventListener('input', function() {
                if (previewWaktu) previewWaktu.textContent = this.value || '-';
                renderLembarNotulen();
            });
        }

        if (inputTempat) {
            inputTempat.addEventListener('input', function() {
                if (previewTempat) previewTempat.textContent = this.value || '-';
                renderLembarNotulen();
            });
        }

        if (toggleKop && sectionKop) {
            toggleKop.addEventListener('change', function() {
                sectionKop.style.display = this.checked ? 'block' : 'none';
            });
        }

        if (toggleTtdHadir && sectionTtdHadir) {
            toggleTtdHadir.addEventListener('change', function() {
                sectionTtdHadir.style.display = this.checked ? 'flex' : 'none';
            });
        }

        if (inputLembar) inputLembar.addEventListener('input', renderLembarNotulen);
        if (inputNotulis) inputNotulis.addEventListener('input', renderLembarNotulen);
        if (inputNipNotulis) inputNipNotulis.addEventListener('input', renderLembarNotulen);

        // Simpan konten TinyMCE ke textarea sebelum form disubmit
        const formSimpan = document.getElementById('form-simpan-notulen');
        if (formSimpan) {
            formSimpan.addEventListener('submit', function() {
                const ed = tinymce.get('isi_notulen_editor');
                if (ed) ed.save();
            });
        }

        // Inisialisasi awal editor & render
        initTinyMCE();
        renderLembarNotulen();
    });

    // Fungsi Cetak Semua (Daftar Hadir + Lembar Notulen)
    function printSemua() {
        const hadir = document.getElementById('lembar-daftar-hadir');
        const notulen = document.getElementById('container-lembar-notulen');
        if (hadir) hadir.classList.remove('d-print-none');
        if (notulen) notulen.classList.remove('d-print-none');
        window.print();
    }

    // Fungsi Cetak Hanya Daftar Hadir
    function printHanyaDaftarHadir() {
        const hadir = document.getElementById('lembar-daftar-hadir');
        const notulen = document.getElementById('container-lembar-notulen');
        if (hadir) hadir.classList.remove('d-print-none');
        if (notulen) notulen.classList.add('d-print-none');
        window.print();
        setTimeout(function() {
            if (notulen) notulen.classList.remove('d-print-none');
        }, 1000);
    }

    // Fungsi Cetak Hanya Notulen
    function printHanyaNotulen() {
        const hadir = document.getElementById('lembar-daftar-hadir');
        const notulen = document.getElementById('container-lembar-notulen');
        if (hadir) hadir.classList.add('d-print-none');
        if (notulen) notulen.classList.remove('d-print-none');
        window.print();
        setTimeout(function() {
            if (hadir) hadir.classList.remove('d-print-none');
        }, 1000);
    }
</script>
