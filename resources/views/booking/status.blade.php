@extends('layouts.app')

@section('title', 'Status Reservasi - ' . ($booking ? $booking->booking_code : 'Pencarian'))

@section('content')
<div class="container" style="padding: 60px 24px; min-height: 70vh; display: flex; align-items: center; justify-content: center;">
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
                        <span style="font-size: 0.75rem; color: var(--text-subtle); text-transform: uppercase;">Kode Reservasi</span>
                        <div class="ticket-code" style="font-size: 1.4rem; text-align: left; padding: 0;">{{ $booking->booking_code }}</div>
                    </div>
                    <div class="status-pill status-{{ $booking->status }}">
                        {{ $booking->status }}
                    </div>
                </div>

                <div class="ticket-details-grid">
                    <div class="ticket-item">
                        <span>Fasilitas</span>
                        <span>{{ $booking->room->name }}</span>
                    </div>
                    <div class="ticket-item">
                        <span>Kategori</span>
                        <span>{{ $booking->room->category === 'layar_iklan' ? 'Layar Iklan Digital' : 'Ruangan Pertemuan' }}</span>
                    </div>
                    <div class="ticket-item">
                        <span>Lokasi</span>
                        <span>{{ $booking->room->floor }}</span>
                    </div>
                    <div class="ticket-item">
                        <span>Pemohon</span>
                        <span>{{ $booking->user->name }}</span>
                    </div>
                    <div class="ticket-item">
                        <span>NIM / Identitas</span>
                        <span>{{ $booking->user->identity_number }}</span>
                    </div>
                    <div class="ticket-item">
                        <span>{{ $booking->room->category === 'layar_iklan' ? 'Tanggal Pesanan' : 'Tanggal Pelaksanaan' }}</span>
                        <span>{{ $booking->booking_date_formatted }}{{ $booking->end_date && $booking->end_date != $booking->booking_date ? ' s/d ' . $booking->end_date_formatted : '' }}</span>
                    </div>
                    @if($booking->room->category !== 'layar_iklan')
                        <div class="ticket-item">
                            <span>Waktu</span>
                            <span>{{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }} WIB</span>
                        </div>
                        <div class="ticket-item">
                            <span>Estimasi Peserta</span>
                            <span>{{ $booking->participant_count }} Orang</span>
                        </div>
                    @else
                        @if($booking->participant_count > 1)
                            <div class="ticket-item">
                                <span>Target Audiens</span>
                                <span>{{ number_format($booking->participant_count) }} Penonton / Hari</span>
                            </div>
                        @endif
                    @endif
                </div>

                <div style="margin-top: 18px; padding-top: 16px; border-top: 1px dashed #e2e8f0;">
                    <span style="font-size: 0.75rem; color: var(--text-subtle); text-transform: uppercase; display: block; margin-bottom: 4px; font-weight: 600;">Keperluan / Agenda:</span>
                    <p style="font-size: 0.92rem; color: #334155; line-height: 1.6;">{{ $booking->purpose }}</p>
                </div>

                @if($booking->admin_notes)
                    <div style="margin-top: 16px; background: #eff6ff; border-left: 3px solid var(--primary); padding: 12px 16px; border-radius: var(--radius-sm);">
                        <span style="font-size: 0.72rem; color: #1e40af; font-weight: 700; text-transform: uppercase;">Catatan Verifikasi Petugas:</span>
                        <p style="font-size: 0.88rem; color: #1e3a8a; margin-top: 2px;">{{ $booking->admin_notes }}</p>
                    </div>
                @endif
            </div>

            <div style="display: flex; gap: 12px; margin-top: 16px;">
                <button type="button" class="btn btn-outline btn-pill btn-block" onclick="copyBookingCode('{{ $booking->booking_code }}')">
                    Salin Kode
                </button>
                <button type="button" class="btn btn-primary btn-pill btn-block" onclick="window.print()">
                    Cetak Bukti
                </button>
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
@endsection
