<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Reservasi - {{ $periodLabel }}</title>
    
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 20mm 15mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
            font-size: 11pt;
            line-height: 1.3;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .kop-logo {
            position: absolute;
            left: 10px;
            top: 2px;
            width: 70px;
            height: auto;
        }
        .kop-header h4 {
            font-size: 13pt;
            font-weight: normal;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop-header h3 {
            font-size: 14pt;
            font-weight: bold;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop-header h2 {
            font-size: 16pt;
            font-weight: 800;
            margin: 2px 0;
            text-transform: uppercase;
        }
        .kop-header p {
            font-size: 9pt;
            margin: 2px 0 0 0;
            font-style: italic;
        }

        /* Judul Laporan */
        .report-title-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 0 0 4px 0;
        }
        .report-period {
            font-size: 10.5pt;
            margin: 0;
        }

        /* Summary Stats */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }
        .summary-card {
            border: 1px solid #000;
            padding: 6px 10px;
            text-align: center;
        }
        .summary-card-title {
            font-size: 8.5pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        .summary-card-val {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 2px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 24px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background-color: #f2f2f2 !important;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
        }
        td {
            padding: 5px 6px;
            vertical-align: top;
        }

        /* Signatures */
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 250px;
            text-align: center;
        }

        /* Print Controls Bar */
        .no-print-bar {
            background: #1e293b;
            color: #ffffff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: sans-serif;
            font-size: 0.9rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Non-Printable Header Bar -->
    <div class="no-print-bar">
        <div>
            <strong>Pratinjau Cetak Laporan Resmi:</strong> {{ $periodLabel }}
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" style="background: #2563eb; color: #fff; border: none; padding: 6px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
                🖨️ Cetak Dokumen / Simpan PDF
            </button>
            <button onclick="window.close()" style="background: #475569; color: #fff; border: none; padding: 6px 14px; border-radius: 6px; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>

    <div style="padding: 20px;">
        
        <!-- Kop Surat Resmi -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo-perpus-bogor.png') }}" alt="Logo Kota Bogor" class="kop-logo" onerror="this.style.display='none'">
            <div class="kop-header">
                <h4>Pemerintah Kota Bogor</h4>
                <h3>Dinas Kearsipan dan Perpustakaan</h3>
                <h2>Perpustakaan & Galeri Kota Bogor</h2>
                <p>Jl. Kapten Muslihat No. 21, RT.01/RW.01, Pabaton, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16121 &bull; Website: perpus.kotabogor.go.id</p>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="report-title-box">
            <h3 class="report-title">Laporan Rekapitulasi Pelayanan Fasilitas & Videotron</h3>
            <p class="report-period"><strong>Periode:</strong> {{ $periodLabel }}</p>
        </div>

        <!-- Ringkasan Angka -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-card-title">Total Pengajuan</div>
                <div class="summary-card-val">{{ $bookings->count() }} Data</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-title">Disetujui</div>
                <div class="summary-card-val">{{ $bookings->where('status', 'disetujui')->count() }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-title">Selesai</div>
                <div class="summary-card-val">{{ $bookings->where('status', 'selesai')->count() }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-title">Total Estimasi Peserta</div>
                <div class="summary-card-val">{{ number_format($bookings->whereIn('status', ['disetujui', 'selesai'])->sum('participant_count')) }} Orang</div>
            </div>
        </div>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 90px;">Kode Booking</th>
                    <th>Nama Pemohon & Kontak</th>
                    <th>Fasilitas</th>
                    <th style="width: 90px;">Tanggal</th>
                    <th style="width: 80px;">Waktu</th>
                    <th>Keperluan / Acara</th>
                    <th style="width: 65px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $i => $b)
                    <tr>
                        <td style="text-align: center;">{{ $i + 1 }}</td>
                        <td style="font-family: monospace; font-weight: bold;">{{ $b->booking_code }}</td>
                        <td>
                            <strong>{{ $b->user->name }}</strong><br>
                            <span style="font-size: 8.5pt;">Telp: {{ $b->user->phone_number }}</span>
                        </td>
                        <td>{{ $b->room->name }} ({{ $b->room->floor }})</td>
                        <td style="text-align: center;">{{ $b->booking_date->format('d/m/Y') }}</td>
                        <td style="text-align: center;">
                            {{ $b->room->category === 'layar_iklan' ? 'Videotron' : substr($b->start_time, 0, 5) . '-' . substr($b->end_time, 0, 5) }}
                        </td>
                        <td>{{ $b->purpose }}</td>
                        <td style="text-align: center; text-transform: uppercase; font-weight: bold; font-size: 8.5pt;">
                            {{ $b->status }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px;">
                            Tidak ada data reservasi peminjaman pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tanda Tangan Pengesahan -->
        <div class="signature-section">
            <div class="signature-box">
                <div>Kota Bogor, {{ now()->translatedFormat('d F Y') }}</div>
                <div style="margin-top: 4px; font-weight: bold;">Petugas Pengelola Fasilitas,</div>
                
                <div style="height: 65px;"></div>

                <div style="font-weight: bold; text-decoration: underline;">
                    {{ Auth::user()->name }}
                </div>
                <div style="font-size: 9pt;">NIP. {{ Auth::user()->identity_number ?: '198501012010011001' }}</div>
            </div>
        </div>

    </div>

</body>
</html>

