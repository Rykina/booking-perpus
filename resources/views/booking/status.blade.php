@extends('layouts.app')

@section('title', 'Status Reservasi - ' . ($booking ? $booking->booking_code : 'Pencarian'))

@section('content')
<style>
    /* Print Specific Styles for Official Letter Output */
    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-family: 'Times New Roman', Times, serif !important;
            font-size: 11pt !important;
            line-height: 1.35 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Sembunyikan elemen website saat dicetak */
        nav, .navbar, header, footer,
        .btn-theme-floating, #floatingThemeToggleBtn,
        #customerChatLauncher, #customerChatBox,
        .status-web-view, .no-print,
        .main-wrapper > header, .main-wrapper > footer {
            display: none !important;
        }

        /* Tampilkan dokumen resmi dengan Kop Surat */
        #printableOfficialLetter {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }
    }

    @media screen {
        #printableOfficialLetter {
            display: none !important;
        }
    }
</style>

<!-- Tampilan Layar Web Biasa (Tidak ikut tercetak) -->
<div class="status-web-view container" style="padding: 60px 24px; min-height: 70vh; display: flex; align-items: center; justify-content: center;">
    <div style="max-width: 580px; width: 100%;">
        
        <div style="margin-bottom: 24px; text-align: center;">
            <a href="{{ route('booking.index') }}" class="btn btn-outline btn-sm" style="margin-bottom: 16px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Beranda
            </a>
            <h2 style="font-size: 1.8rem; font-weight: 800;">Status Reservasi Fasilitas</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">Informasi detail status permohonan peminjaman di Perpustakaan Dan Galeri Kota Bogor.</p>
        </div>

        @if(isset($booking))
            <div class="ticket-card" style="box-shadow: var(--shadow-lg);">
                <div class="ticket-header">
                    <div>
                        <span class="ticket-label" style="font-size: 0.75rem; text-transform: uppercase;">Kode Reservasi</span>
                        <div class="ticket-code" style="font-size: 1.4rem; text-align: left; padding: 0;">{{ $booking->booking_code }}</div>
                    </div>
                    <div class="status-pill status-{{ $booking->status }}">
                        {{ $booking->status }}
                    </div>
                </div>

                <div class="ticket-details-grid">
                    <div class="ticket-item">
                        <span class="ticket-label">Fasilitas</span>
                        <span class="ticket-value">{{ $booking->room->name }}</span>
                    </div>
                    <div class="ticket-item">
                        <span class="ticket-label">Kategori</span>
                        <span class="ticket-value">{{ $booking->room->category === 'layar_iklan' ? 'Layar Iklan Digital' : 'Ruangan Pertemuan' }}</span>
                    </div>
                    <div class="ticket-item">
                        <span class="ticket-label">Lokasi</span>
                        <span class="ticket-value">{{ $booking->room->floor }}</span>
                    </div>
                    <div class="ticket-item">
                        <span class="ticket-label">Pemohon</span>
                        <span class="ticket-value">{{ $booking->user->name }}</span>
                    </div>
                    <div class="ticket-item">
                        <span class="ticket-label">NIM / Identitas</span>
                        <span class="ticket-value">{{ $booking->user->identity_number }}</span>
                    </div>
                    <div class="ticket-item">
                        <span class="ticket-label">{{ $booking->room->category === 'layar_iklan' ? 'Tanggal Pesanan' : 'Tanggal Pelaksanaan' }}</span>
                        <span class="ticket-value">{{ $booking->booking_date_formatted }}{{ $booking->end_date && $booking->end_date != $booking->booking_date ? ' s/d ' . $booking->end_date_formatted : '' }}</span>
                    </div>
                    @if($booking->room->category !== 'layar_iklan')
                        <div class="ticket-item">
                            <span class="ticket-label">Waktu</span>
                            <span class="ticket-value">{{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }} WIB</span>
                        </div>
                        <div class="ticket-item">
                            <span class="ticket-label">Estimasi Peserta</span>
                            <span class="ticket-value">{{ $booking->participant_count }} Orang</span>
                        </div>
                    @else
                        @if($booking->participant_count > 1)
                            <div class="ticket-item">
                                <span class="ticket-label">Target Audiens</span>
                                <span class="ticket-value">{{ number_format($booking->participant_count) }} Penonton / Hari</span>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="ticket-purpose-section" style="margin-top: 18px; padding-top: 16px; border-top: 1px dashed #e2e8f0;">
                    <span class="ticket-label" style="display: block; margin-bottom: 4px;">Keperluan / Agenda:</span>
                    <p class="ticket-purpose-text" style="font-size: 0.95rem; line-height: 1.6; margin: 0;">{{ $booking->purpose }}</p>
                </div>

                @if($booking->admin_notes)
                    <div class="ticket-admin-notes" style="margin-top: 16px; padding: 12px 16px; border-radius: var(--radius-sm);">
                        <span class="ticket-admin-notes-lbl" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 2px;">Catatan Verifikasi Petugas:</span>
                        <p class="ticket-admin-notes-text" style="font-size: 0.9rem; margin: 0; line-height: 1.5;">{{ $booking->admin_notes }}</p>
                    </div>
                @endif
            </div>

            <div style="display: flex; gap: 12px; margin-top: 16px;">
                <button type="button" class="btn btn-outline btn-pill btn-block" onclick="copyBookingCode('{{ $booking->booking_code }}')">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 4px; vertical-align: middle;">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    Salin Kode
                </button>
                <a href="{{ route('booking.print', ['code' => $booking->booking_code]) }}" target="_blank" class="btn btn-primary btn-pill btn-block" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    <span>Cetak Bukti Resmi</span>
                </a>
            </div>
            <div style="text-align: center; margin-top: 16px;">
                <a href="{{ route('booking.history', ['search' => $booking->user->email]) }}" style="font-size: 0.85rem; font-weight: 700; color: #2563eb; text-decoration: none;">
                    &larr; Lihat Semua Riwayat Pemesanan {{ $booking->user->name }}
                </a>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (typeof saveBookingToLocalHistory === 'function') {
                        saveBookingToLocalHistory('{{ $booking->booking_code }}');
                    }
                });
            </script>
        @else
            <div style="background: #ffffff; border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 32px; text-align: center; box-shadow: var(--shadow-md);">
                <p style="color: var(--accent-rose); font-weight: 700; margin-bottom: 12px;">Kode booking tidak ditemukan atau belum dimasukkan.</p>
                <a href="{{ route('booking.index') }}" class="btn btn-primary btn-pill">
                    Kembali ke Halaman Pencarian
                </a>
            </div>
        @endif

    </div>
</div>

@if(isset($booking))
<!-- Dokumen Resmi yang Dicetak (Hanya tampil saat Ctrl+P atau Print) -->
<div id="printableOfficialLetter">
    <div style="padding: 10px 0;">
        
        <!-- Kop Surat Resmi -->
        <div style="display: flex; align-items: center; justify-content: center; position: relative; text-align: center; border-bottom: 3px double #000000; padding-bottom: 12px; margin-bottom: 18px;">
            <img src="{{ asset('images/logo-perpus-bogor.png') }}" alt="Logo Kota Bogor" style="position: absolute; left: 0; top: 0; width: 72px; height: auto;" onerror="this.style.display='none'">
            <div style="padding: 0 35px;">
                <h4 style="font-size: 13pt; font-weight: normal; margin: 0; text-transform: uppercase; letter-spacing: 1.5px;">Pemerintah Kota Bogor</h4>
                <h3 style="font-size: 14pt; font-weight: bold; margin: 2px 0; text-transform: uppercase; letter-spacing: 1.5px;">Dinas Kearsipan dan Perpustakaan</h3>
                <h2 style="font-size: 16pt; font-weight: 900; margin: 2px 0; text-transform: uppercase; letter-spacing: 1px;">Perpustakaan & Galeri Kota Bogor</h2>
                <p style="font-size: 9pt; margin: 3px 0 0 0; font-style: italic; line-height: 1.25;">Jl. Kapten Muslihat No. 21, RT.01/RW.01, Pabaton, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16121<br>Telepon: (0251) 8321075 &bull; Pos-el: perpustakaan@kotabogor.go.id &bull; Laman: perpus.kotabogor.go.id</p>
            </div>
        </div>

        <!-- Judul Surat Bukti & Status Stamp -->
        <div style="text-align: center; margin-bottom: 18px; position: relative;">
            <h1 style="font-size: 14pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0;">SURAT BUKTI RESERVASI FASILITAS</h1>
            <p style="font-size: 10.5pt; margin: 0; color: #111827;">Nomor Registrasi: <strong>{{ $booking->booking_code }}</strong></p>

            <div style="position: absolute; right: 0; top: -5px; border: 2px solid {{ $booking->status === 'disetujui' ? '#15803d' : ($booking->status === 'menunggu' ? '#b45309' : '#b91c1c') }}; border-radius: 6px; padding: 4px 12px; color: {{ $booking->status === 'disetujui' ? '#15803d' : ($booking->status === 'menunggu' ? '#b45309' : '#b91c1c') }}; text-align: center; font-family: sans-serif; line-height: 1.1;">
                <span style="font-size: 7.5pt; font-weight: bold; text-transform: uppercase; display: block;">Status Reservasi</span>
                <span style="font-size: 11pt; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em;">{{ strtoupper($booking->status) }}</span>
            </div>
        </div>

        <!-- Pengantar Surat -->
        <p style="font-size: 10.5pt; margin-bottom: 14px; text-align: justify; text-indent: 28px;">
            Berdasarkan permohonan peminjaman fasilitas yang telah didaftarkan dan diverifikasi melalui Sistem Informasi Pelayanan Fasilitas dan Galeri Kota Bogor, dengan ini diterbitkan Surat Bukti Reservasi resmi kepada:
        </p>

        <!-- I. Identitas Pemohon -->
        <div style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 12px 0 6px 0; background: #f1f5f9; padding: 4px 8px; border-left: 4px solid #000000; letter-spacing: 0.04em;">I. IDENTITAS PEMOHON / PENANGGUNG JAWAB</div>
        <table style="width: 100%; border-collapse: collapse; font-size: 10.5pt; margin-bottom: 10px;">
            <tr>
                <td style="width: 28%; padding: 4px 6px;">Nama Lengkap</td>
                <td style="width: 3%; text-align: center; padding: 4px 6px;">:</td>
                <td style="width: 69%; font-weight: bold; padding: 4px 6px;">{{ $booking->user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 6px;">NIM / NIP / No. Identitas</td>
                <td style="text-align: center; padding: 4px 6px;">:</td>
                <td style="font-weight: bold; padding: 4px 6px;">{{ $booking->user->identity_number ?: '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 6px;">Alamat Email</td>
                <td style="text-align: center; padding: 4px 6px;">:</td>
                <td style="font-weight: bold; padding: 4px 6px;">{{ $booking->user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 6px;">Nomor WhatsApp / HP</td>
                <td style="text-align: center; padding: 4px 6px;">:</td>
                <td style="font-weight: bold; padding: 4px 6px;">{{ $booking->user->phone_number ?: '-' }}</td>
            </tr>
        </table>

        <!-- II. Rincian Fasilitas & Jadwal -->
        <div style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 12px 0 6px 0; background: #f1f5f9; padding: 4px 8px; border-left: 4px solid #000000; letter-spacing: 0.04em;">II. RINCIAN FASILITAS & JADWAL PELAKSANAAN</div>
        <table style="width: 100%; border-collapse: collapse; font-size: 10.5pt; margin-bottom: 10px;">
            <tr>
                <td style="width: 28%; padding: 4px 6px;">Fasilitas / Ruangan</td>
                <td style="width: 3%; text-align: center; padding: 4px 6px;">:</td>
                <td style="width: 69%; font-weight: bold; padding: 4px 6px;">{{ $booking->room->name }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 6px;">Kategori Layanan</td>
                <td style="text-align: center; padding: 4px 6px;">:</td>
                <td style="font-weight: bold; padding: 4px 6px;">{{ $booking->room->category === 'layar_iklan' ? 'Layar Iklan Digital (Videotron)' : 'Ruangan Pertemuan / Fasilitas Gedung' }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 6px;">Lokasi Gedung</td>
                <td style="text-align: center; padding: 4px 6px;">:</td>
                <td style="font-weight: bold; padding: 4px 6px;">{{ $booking->room->floor }}, Gedung Perpustakaan & Galeri Kota Bogor</td>
            </tr>
            <tr>
                <td style="padding: 4px 6px;">{{ $booking->room->category === 'layar_iklan' ? 'Tanggal Penayangan' : 'Tanggal Pelaksanaan' }}</td>
                <td style="text-align: center; padding: 4px 6px;">:</td>
                <td style="font-weight: bold; padding: 4px 6px;">
                    {{ $booking->booking_date_formatted }}
                    @if($booking->end_date && $booking->end_date != $booking->booking_date)
                        s/d {{ $booking->end_date_formatted }}
                    @endif
                </td>
            </tr>
            @if($booking->room->category !== 'layar_iklan')
                <tr>
                    <td style="padding: 4px 6px;">Waktu & Durasi</td>
                    <td style="text-align: center; padding: 4px 6px;">:</td>
                    <td style="font-weight: bold; padding: 4px 6px;">
                        {{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }} WIB
                        ({{ $booking->is_full_day ? '1 Hari Penuh' : 'Jam Pilihan' }})
                    </td>
                </tr>
                <tr>
                    <td style="padding: 4px 6px;">Estimasi Peserta</td>
                    <td style="text-align: center; padding: 4px 6px;">:</td>
                    <td style="font-weight: bold; padding: 4px 6px;">{{ $booking->participant_count }} Orang (Kapasitas: {{ $booking->room->capacity }} Orang)</td>
                </tr>
            @else
                @if($booking->participant_count > 1)
                    <tr>
                        <td style="padding: 4px 6px;">Target Audiens</td>
                        <td style="text-align: center; padding: 4px 6px;">:</td>
                        <td style="font-weight: bold; padding: 4px 6px;">{{ number_format($booking->participant_count) }} Penonton / Hari</td>
                    </tr>
                @endif
            @endif
            <tr>
                <td style="padding: 4px 6px;">Keperluan / Agenda</td>
                <td style="text-align: center; padding: 4px 6px;">:</td>
                <td style="font-weight: bold; padding: 4px 6px;">{{ $booking->purpose }}</td>
            </tr>
        </table>

        @if($booking->admin_notes)
            <div style="background: #f8fafc; border: 1px dashed #64748b; padding: 8px 12px; margin: 10px 0; border-radius: 4px; font-size: 9.5pt;">
                <strong style="color: #1e3a8a;">Catatan Khusus Petugas Verifikasi:</strong>
                <div>{{ $booking->admin_notes }}</div>
            </div>
        @endif

        <!-- III. Ketentuan & Tata Tertib -->
        <div style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 12px 0 6px 0; background: #f1f5f9; padding: 4px 8px; border-left: 4px solid #000000; letter-spacing: 0.04em;">III. KETENTUAN DAN TATA TERTIB PEMINJAMAN</div>
        <div style="font-size: 9pt; line-height: 1.35;">
            <ol style="margin: 4px 0; padding-left: 20px;">
                <li style="margin-bottom: 3px; text-align: justify;">Surat bukti ini merupakan dokumen resmi verifikasi reservasi fasilitas dan <strong>wajib ditunjukkan</strong> kepada petugas penerima tamu / pengelola fasilitas di lantai 1 saat kedatangan pada hari pelaksanaan.</li>
                <li style="margin-bottom: 3px; text-align: justify;">Pemohon dan panitia pelaksana wajib hadir minimal <strong>15 menit sebelum</strong> jadwal peminjaman dimulai untuk koordinasi teknis kelengkapan ruangan.</li>
                <li style="margin-bottom: 3px; text-align: justify;">Pemohon bertanggung jawab penuh atas keamanan, kebersihan, ketertiban umum, serta keutuhan seluruh sarana dan prasarana yang digunakan selama kegiatan berlangsung.</li>
                <li style="margin-bottom: 3px; text-align: justify;">Dilarang keras merokok, membawa senjata tajam, minuman keras/zat terlarang, atau melakukan aktivitas yang bertentangan dengan norma hukum di lingkungan perpustakaan.</li>
                <li style="margin-bottom: 3px; text-align: justify;">Perubahan atau pembatalan jadwal wajib dilaporkan kepada petugas pengelola selambat-lambatnya 1 (satu) hari kerja sebelum tanggal pelaksanaan.</li>
            </ol>
        </div>

        <!-- IV. Tanda Tangan Pengesahan -->
        <div style="margin-top: 24px; display: flex; justify-content: space-between; page-break-inside: avoid;">
            <div style="width: 240px; text-align: center; font-size: 10pt;">
                <div>Pemohon / Penanggung Jawab,</div>
                <div style="height: 60px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $booking->user->name }}</div>
                <div style="font-size: 8.5pt; color: #374151; margin-top: 2px;">NIM/Identitas: {{ $booking->user->identity_number ?: '-' }}</div>
            </div>

            <div style="width: 240px; text-align: center; font-size: 10pt;">
                <div>Kota Bogor, {{ now()->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold; margin-top: 2px;">Petugas Pengelola Fasilitas,</div>
                <div style="height: 60px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">Seksi Layanan & Kerjasama</div>
                <div style="font-size: 8.5pt; color: #374151; margin-top: 2px;">Dinas Kearsipan & Perpustakaan Kota Bogor</div>
            </div>
        </div>

        <!-- Footer Dokumen -->
        <div style="margin-top: 25px; padding-top: 10px; border-top: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: space-between; font-size: 8pt; color: #4b5563;">
            <div>
                <em>Dokumen ini diterbitkan secara otomatis oleh Sistem Informasi Pelayanan Fasilitas & Videotron Perpustakaan Kota Bogor.</em>
            </div>
            <div style="font-family: monospace; font-size: 9pt; font-weight: bold; color: #000000;">
                KODE: {{ $booking->booking_code }}
            </div>
        </div>

    </div>
</div>
@endif

@endsection
