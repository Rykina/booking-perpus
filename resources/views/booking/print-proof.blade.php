<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Bukti Reservasi - {{ $booking->booking_code }}</title>
    
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
            background: #f8fafc;
            margin: 0;
            padding: 0;
            font-size: 11pt;
            line-height: 1.35;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Non-Printable Header Bar */
        .no-print-bar {
            background: #1e293b;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .no-print-bar .doc-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .no-print-bar .doc-code {
            background: #3b82f6;
            color: #ffffff;
            padding: 3px 10px;
            border-radius: 6px;
            font-weight: 700;
            font-family: monospace;
            font-size: 0.95rem;
        }

        .no-print-bar .actions {
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s ease;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        .btn-close {
            background: #475569;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.88rem;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .btn-close:hover {
            background: #334155;
        }

        /* Paper Document Sheet */
        .sheet-container {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: #ffffff;
            padding: 18mm 20mm 20mm 20mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        /* Kop Surat Resmi */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            text-align: center;
            border-bottom: 3px double #000000;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .kop-logo {
            position: absolute;
            left: 5px;
            top: 2px;
            width: 76px;
            height: auto;
        }

        .kop-header {
            padding: 0 40px;
        }

        .kop-header h4 {
            font-size: 13pt;
            font-weight: normal;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .kop-header h3 {
            font-size: 14pt;
            font-weight: bold;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .kop-header h2 {
            font-size: 16pt;
            font-weight: 900;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .kop-header p {
            font-size: 9pt;
            margin: 3px 0 0 0;
            font-style: italic;
            line-height: 1.25;
        }

        /* Document Title & Number */
        .doc-title-block {
            text-align: center;
            margin-bottom: 18px;
            position: relative;
        }

        .doc-title {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }

        .doc-number {
            font-size: 10.5pt;
            margin: 0;
            color: #111827;
        }

        /* Official Status Stamp / Badge */
        .status-stamp-box {
            position: absolute;
            right: 0;
            top: -5px;
            border: 2.5px solid #15803d;
            border-radius: 8px;
            padding: 4px 14px;
            color: #15803d;
            text-align: center;
            transform: rotate(-3deg);
            font-family: sans-serif;
            line-height: 1.1;
        }

        .status-stamp-box.status-menunggu {
            border-color: #b45309;
            color: #b45309;
        }

        .status-stamp-box.status-ditolak {
            border-color: #b91c1c;
            color: #b91c1c;
        }

        .status-stamp-box .stamp-label {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
        }

        .status-stamp-box .stamp-text {
            font-size: 11pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* Opening Sentence */
        .doc-intro {
            font-size: 10.5pt;
            margin-bottom: 14px;
            text-align: justify;
            text-indent: 28px;
        }

        /* Section Block */
        .section-heading {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 14px 0 6px 0;
            background: #f1f5f9;
            padding: 4px 8px;
            border-left: 4px solid #000000;
            letter-spacing: 0.04em;
        }

        /* Formal Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
            margin-bottom: 10px;
        }

        .data-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .data-table td.col-lbl {
            width: 28%;
            color: #1f2937;
        }

        .data-table td.col-sep {
            width: 3%;
            text-align: center;
        }

        .data-table td.col-val {
            width: 69%;
            font-weight: bold;
            color: #000000;
        }

        /* Verification Notes */
        .admin-note-box {
            background: #f8fafc;
            border: 1px dashed #64748b;
            padding: 8px 12px;
            margin: 10px 0;
            border-radius: 4px;
            font-size: 9.5pt;
        }

        .admin-note-box strong {
            display: block;
            margin-bottom: 2px;
            color: #1e3a8a;
        }

        /* Terms & Conditions */
        .terms-block {
            margin-top: 14px;
            font-size: 9pt;
            line-height: 1.35;
        }

        .terms-block ol {
            margin: 4px 0;
            padding-left: 20px;
        }

        .terms-block li {
            margin-bottom: 3px;
            text-align: justify;
        }

        /* Signatures Section */
        .signature-section {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 240px;
            text-align: center;
            font-size: 10pt;
        }

        .sig-space {
            height: 60px;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .sig-meta {
            font-size: 8.5pt;
            color: #374151;
            margin-top: 2px;
        }

        /* Document Footer & QR Code */
        .doc-footer-meta {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 8pt;
            color: #4b5563;
        }

        .doc-footer-code {
            font-family: monospace;
            font-size: 9pt;
            font-weight: bold;
            color: #000000;
        }

        /* Media Print Rules */
        @media print {
            body {
                background: #ffffff !important;
            }

            .no-print-bar {
                display: none !important;
            }

            .sheet-container {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Kontrol Layar (Tidak ikut tercetak) -->
    <div class="no-print-bar">
        <div class="doc-info">
            <span>Pratinjau Surat Bukti Reservasi:</span>
            <span class="doc-code">{{ $booking->booking_code }}</span>
            <span style="opacity: 0.8;">({{ $booking->room->name }})</span>
        </div>
        <div class="actions">
            <button type="button" class="btn-print" onclick="window.print()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Dokumen Resmi / Simpan PDF</span>
            </button>
            <button type="button" class="btn-close" onclick="window.close()">Tutup</button>
        </div>
    </div>

    <!-- Lembar Surat Resmi -->
    <div class="sheet-container">
        
        <!-- Kop Surat Resmi -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo-perpus-bogor.png') }}" alt="Logo Kota Bogor" class="kop-logo" onerror="this.style.display='none'">
            <div class="kop-header">
                <h4>Pemerintah Kota Bogor</h4>
                <h3>Dinas Kearsipan dan Perpustakaan</h3>
                <h2>Perpustakaan & Galeri Kota Bogor</h2>
                <p>Jl. Kapten Muslihat No. 21, RT.01/RW.01, Pabaton, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16121<br>Telepon: (0251) 8321075 &bull; Pos-el: perpustakaan@kotabogor.go.id &bull; Laman: perpus.kotabogor.go.id</p>
            </div>
        </div>

        <!-- Judul Surat Bukti & Status Stamp -->
        <div class="doc-title-block">
            <h1 class="doc-title">SURAT BUKTI RESERVASI FASILITAS</h1>
            <p class="doc-number">Nomor Registrasi: <strong>{{ $booking->booking_code }}</strong></p>

            <div class="status-stamp-box status-{{ $booking->status }}">
                <span class="stamp-label">Status Reservasi</span>
                <span class="stamp-text">{{ strtoupper($booking->status) }}</span>
            </div>
        </div>

        <!-- Pengantar Surat -->
        <p class="doc-intro">
            Berdasarkan permohonan peminjaman fasilitas yang telah didaftarkan dan diverifikasi melalui Sistem Informasi Pelayanan Fasilitas dan Galeri Kota Bogor, dengan ini diterbitkan Surat Bukti Reservasi resmi kepada:
        </p>

        <!-- I. Data Pemohon -->
        <div class="section-heading">I. IDENTITAS PEMOHON / PENANGGUNG JAWAB</div>
        <table class="data-table">
            <tr>
                <td class="col-lbl">Nama Lengkap</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $booking->user->name }}</td>
            </tr>
            <tr>
                <td class="col-lbl">NIM / NIP / No. Identitas</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $booking->user->identity_number ?: '-' }}</td>
            </tr>
            <tr>
                <td class="col-lbl">Alamat Email</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $booking->user->email }}</td>
            </tr>
            <tr>
                <td class="col-lbl">Nomor WhatsApp / HP</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $booking->user->phone_number ?: '-' }}</td>
            </tr>
        </table>

        <!-- II. Rincian Fasilitas & Jadwal -->
        <div class="section-heading">II. RINCIAN FASILITAS & JADWAL PELAKSANAAN</div>
        <table class="data-table">
            <tr>
                <td class="col-lbl">Fasilitas / Ruangan</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $booking->room->name }}</td>
            </tr>
            <tr>
                <td class="col-lbl">Kategori Layanan</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $booking->room->category === 'layar_iklan' ? 'Layar Iklan Digital (Videotron)' : 'Ruangan Pertemuan / Fasilitas Gedung' }}</td>
            </tr>
            <tr>
                <td class="col-lbl">Lokasi Gedung</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $booking->room->floor }}, Gedung Perpustakaan & Galeri Kota Bogor</td>
            </tr>
            <tr>
                <td class="col-lbl">{{ $booking->room->category === 'layar_iklan' ? 'Tanggal Penayangan' : 'Tanggal Pelaksanaan' }}</td>
                <td class="col-sep">:</td>
                <td class="col-val">
                    {{ $booking->booking_date_formatted }}
                    @if($booking->end_date && $booking->end_date != $booking->booking_date)
                        s/d {{ $booking->end_date_formatted }}
                    @endif
                </td>
            </tr>
            @if($booking->room->category !== 'layar_iklan')
                <tr>
                    <td class="col-lbl">Waktu & Durasi</td>
                    <td class="col-sep">:</td>
                    <td class="col-val">
                        {{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }} WIB
                        ({{ $booking->is_full_day ? '1 Hari Penuh' : 'Jam Pilihan' }})
                    </td>
                </tr>
                <tr>
                    <td class="col-lbl">Estimasi Peserta</td>
                    <td class="col-sep">:</td>
                    <td class="col-val">{{ $booking->participant_count }} Orang (Kapasitas: {{ $booking->room->capacity }} Orang)</td>
                </tr>
            @else
                @if($booking->participant_count > 1)
                    <tr>
                        <td class="col-lbl">Target Audiens</td>
                        <td class="col-sep">:</td>
                        <td class="col-val">{{ number_format($booking->participant_count) }} Penonton / Hari</td>
                    </tr>
                @endif
            @endif
            <tr>
                <td class="col-lbl">Keperluan / Agenda</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $booking->purpose }}</td>
            </tr>
        </table>

        @if($booking->admin_notes)
            <div class="admin-note-box">
                <strong>Catatan Khusus Petugas Verifikasi:</strong>
                {{ $booking->admin_notes }}
            </div>
        @endif

        <!-- III. Ketentuan & Tata Tertib -->
        <div class="section-heading">III. KETENTUAN DAN TATA TERTIB PEMINJAMAN</div>
        <div class="terms-block">
            <ol>
                <li>Surat bukti ini merupakan dokumen resmi verifikasi reservasi fasilitas dan <strong>wajib ditunjukkan</strong> kepada petugas penerima tamu / pengelola fasilitas di lantai 1 saat kedatangan pada hari pelaksanaan.</li>
                <li>Pemohon dan panitia pelaksana wajib hadir minimal <strong>15 menit sebelum</strong> jadwal peminjaman dimulai untuk koordinasi teknis kelengkapan ruangan (mikrofon, proyektor, pencahayaan, dan tata letak).</li>
                <li>Pemohon bertanggung jawab penuh atas keamanan, kebersihan, ketertiban umum, serta keutuhan seluruh sarana dan prasarana yang digunakan selama kegiatan berlangsung.</li>
                <li>Dilarang keras merokok, membawa senjata tajam, minuman keras/zat terlarang, atau melakukan aktivitas yang bertentangan dengan norma dan peraturan daerah di lingkungan Perpustakaan & Galeri Kota Bogor.</li>
                <li>Perubahan atau pembatalan jadwal wajib dilaporkan kepada petugas pengelola selambat-lambatnya 1 (satu) hari kerja sebelum tanggal pelaksanaan.</li>
            </ol>
        </div>

        <!-- IV. Tanda Tangan Pengesahan -->
        <div class="signature-section">
            <div class="sig-box">
                <div>Pemohon / Penanggung Jawab,</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $booking->user->name }}</div>
                <div class="sig-meta">NIM/Identitas: {{ $booking->user->identity_number ?: '-' }}</div>
            </div>

            <div class="sig-box">
                <div>Kota Bogor, {{ now()->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold; margin-top: 2px;">Petugas Pengelola Fasilitas,</div>
                <div class="sig-space"></div>
                <div class="sig-name">Seksi Layanan & Kerjasama</div>
                <div class="sig-meta">Dinas Kearsipan & Perpustakaan Kota Bogor</div>
            </div>
        </div>

        <!-- Footer Catatan Sistem -->
        <div class="doc-footer-meta">
            <div>
                <em>Dokumen ini diterbitkan secara otomatis oleh Sistem Informasi Pelayanan Fasilitas & Videotron Perpustakaan Kota Bogor.</em>
            </div>
            <div class="doc-footer-code">
                KODE: {{ $booking->booking_code }}
            </div>
        </div>

    </div>

</body>
</html>
