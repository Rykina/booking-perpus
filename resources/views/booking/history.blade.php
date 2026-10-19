@extends('layouts.app')

@section('title', 'Riwayat Pemesanan Fasilitas - Perpustakaan Dan Galeri Kota Bogor')

@section('content')
<div class="history-page-wrapper">
    <div class="container">

        <!-- Top Breadcrumb -->
        <div class="history-top-nav">
            <nav class="history-breadcrumb">
                <a href="{{ route('booking.index') }}" class="bc-home-link">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Beranda</span>
                </a>
                <span class="bc-sep">&rsaquo;</span>
                <span class="bc-current">Riwayat Pemesanan</span>
            </nav>
        </div>

        <!-- Hero Header matching Gambar 1 -->
        <div class="history-hero-card">
            <div class="history-hero-content">
                <div class="history-service-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>PELAYANAN PEMESANAN FASILITAS</span>
                </div>
                <h1 class="history-title">Riwayat Pemesanan Fasilitas & Layanan</h1>
                <p class="history-subtitle">
                    Lacak status pengajuan peminjaman ruangan dan videotron Anda di Perpustakaan dan Galeri Kota Bogor secara transparan dan terintegrasi.
                </p>
                <div class="history-features-row">
                    <div class="hfeature-item">
                        <div class="hfeature-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path></svg>
                        </div>
                        <div class="hfeature-labels">
                            <span class="hfeature-bold">Mudah</span>
                            <span class="hfeature-sub">& Cepat</span>
                        </div>
                    </div>
                    <div class="hfeature-item">
                        <div class="hfeature-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <div class="hfeature-labels">
                            <span class="hfeature-bold">Transparan</span>
                            <span class="hfeature-sub">Real-time</span>
                        </div>
                    </div>
                    <div class="hfeature-item">
                        <div class="hfeature-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <div class="hfeature-labels">
                            <span class="hfeature-bold">Terintegrasi</span>
                            <span class="hfeature-sub">dengan Sistem</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="history-hero-stats">
                <div class="stat-glass-card stat-card-blue">
                    <div class="stat-glass-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <span class="stat-glass-val">{{ $stats['all'] }}</span>
                    <span class="stat-glass-lbl">Total Booking</span>
                </div>
                <div class="stat-glass-card stat-card-teal">
                    <div class="stat-glass-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <span class="stat-glass-val">{{ $stats['menunggu'] }}</span>
                    <span class="stat-glass-lbl">Menunggu</span>
                </div>
                <div class="stat-glass-card stat-card-purple">
                    <div class="stat-glass-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                    <span class="stat-glass-val">{{ $stats['disetujui'] }}</span>
                    <span class="stat-glass-lbl">Disetujui</span>
                </div>
            </div>
        </div>

        <!-- Search Box Section -->
        <div class="history-search-card">
            <form action="{{ route('booking.history') }}" method="GET" class="history-search-form" id="historySearchForm">
                <div class="search-input-group">
                    <div class="search-input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <input type="text" name="search" id="inputHistorySearch" class="search-input-control" placeholder="Cari berdasarkan Email, No. WhatsApp/HP, NIM/Identitas, atau Kode Booking..." value="{{ $search }}" autocomplete="off">
                    @if(!empty($search))
                        <a href="{{ route('booking.history') }}" class="btn-clear-search" title="Hapus pencarian">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary btn-search-submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span>Cari Riwayat</span>
                    </button>
                </div>

                @if(!empty($statusFilter) && $statusFilter !== 'all')
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
            </form>

            <!-- Quick Search Suggestion Pills -->
            <div class="quick-sample-chips">
                <span class="sample-label">Pencarian Cepat :</span>
                <a href="{{ route('booking.history', ['search' => 'budi.pratama@gmail.com']) }}" class="sample-chip">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span>budi.pratama@gmail.com</span>
                </a>
                <a href="{{ route('booking.history', ['search' => 'siti.rahmawati@gmail.com']) }}" class="sample-chip">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>siti.rahmawati@gmail.com</span>
                </a>
                <a href="{{ route('booking.history', ['search' => '081298765432']) }}" class="sample-chip">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <span>081298765432</span>
                </a>
                <a href="{{ route('booking.history', ['all' => 1]) }}" class="sample-chip sample-chip-all">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>Tampilkan Semua Riwayat</span>
                </a>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        @if($hasSearch)
            <div class="history-filter-tabs">
                <a href="{{ route('booking.history', ['search' => $search, 'status' => 'all']) }}" class="filter-tab {{ $statusFilter === 'all' ? 'active' : '' }}">
                    Semua
                    <span class="tab-count">{{ $stats['all'] }}</span>
                </a>
                <a href="{{ route('booking.history', ['search' => $search, 'status' => 'menunggu']) }}" class="filter-tab {{ $statusFilter === 'menunggu' ? 'active' : '' }}">
                    Menunggu Verifikasi
                    <span class="tab-count count-wait">{{ $stats['menunggu'] }}</span>
                </a>
                <a href="{{ route('booking.history', ['search' => $search, 'status' => 'disetujui']) }}" class="filter-tab {{ $statusFilter === 'disetujui' ? 'active' : '' }}">
                    Disetujui
                    <span class="tab-count count-acc">{{ $stats['disetujui'] }}</span>
                </a>
                <a href="{{ route('booking.history', ['search' => $search, 'status' => 'selesai']) }}" class="filter-tab {{ $statusFilter === 'selesai' ? 'active' : '' }}">
                    Selesai
                    <span class="tab-count">{{ $stats['selesai'] }}</span>
                </a>
                <a href="{{ route('booking.history', ['search' => $search, 'status' => 'ditolak']) }}" class="filter-tab {{ $statusFilter === 'ditolak' ? 'active' : '' }}">
                    Ditolak
                    <span class="tab-count count-rej">{{ $stats['ditolak'] }}</span>
                </a>
            </div>
        @endif

        <!-- Local Storage Device History (Muncul otomatis jika ada di browser ini) -->
        <div id="localDeviceHistorySection" class="local-history-box" style="display: none;">
            <div class="local-history-header">
                <div class="local-history-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.2">
                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                        <line x1="12" y1="18" x2="12.01" y2="18"></line>
                    </svg>
                    <strong>Riwayat Pemesanan di Perangkat Ini</strong>
                    <span class="badge-device-count" id="deviceHistoryCount">0</span>
                </div>
                <button type="button" class="btn-clear-local-history" onclick="clearLocalBookingHistory()">
                    Bersihkan Riwayat Lokal
                </button>
            </div>
            <p class="local-history-desc">Berikut daftar reservasi yang sebelumnya Anda ajukan dari peramban/perangkat ini:</p>
            <div class="local-history-grid" id="localHistoryCardsContainer">
                <!-- Injected by JS -->
            </div>
        </div>

        <!-- Main Bookings Results -->
        @if($hasSearch)
            @if($bookings->count() > 0)
                <div class="history-results-header">
                    <span class="results-count-text">
                        Menampilkan <strong>{{ $bookings->total() }}</strong> reservasi ditemukan
                        @if(!empty($search)) untuk kata kunci <em>"{{ $search }}"</em> @endif
                    </span>
                </div>

                <div class="history-cards-grid">
                    @foreach($bookings as $item)
                        <div class="history-card status-border-{{ $item->status }}">
                            <div class="hcard-header">
                                <div class="hcard-room-info">
                                    <div class="hcard-room-icon">
                                        @if($item->room->category === 'layar_iklan')
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                                <line x1="12" y1="17" x2="12" y2="21"></line>
                                            </svg>
                                        @else
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="hcard-room-title">{{ $item->room->name }}</h3>
                                        <span class="hcard-room-meta">{{ $item->room->floor }} • {{ $item->room->category === 'layar_iklan' ? 'Layar Iklan Digital' : 'Ruangan Pertemuan' }}</span>
                                    </div>
                                </div>
                                <div class="hcard-status-badge status-{{ $item->status }}">
                                    @if($item->status === 'disetujui')
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Disetujui
                                    @elseif($item->status === 'menunggu')
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        Menunggu Verifikasi
                                    @elseif($item->status === 'ditolak')
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Ditolak
                                    @else
                                        {{ ucfirst($item->status) }}
                                    @endif
                                </div>
                            </div>

                            <div class="hcard-body">
                                <div class="hcard-code-bar">
                                    <div class="hcard-code-text">
                                        <span class="hcard-code-lbl">KODE BOOKING:</span>
                                        <strong>{{ $item->booking_code }}</strong>
                                    </div>
                                    <button type="button" class="btn-copy-code-sm" onclick="copyBookingCode('{{ $item->booking_code }}')" title="Salin kode booking">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                        <span>Salin</span>
                                    </button>
                                </div>

                                <div class="hcard-grid-meta">
                                    <div class="hcard-meta-item">
                                        <span class="meta-item-lbl">{{ $item->room->category === 'layar_iklan' ? 'Tanggal Pesanan' : 'Tanggal Pelaksanaan' }}</span>
                                        <span class="meta-item-val">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            {{ $item->booking_date_formatted }}
                                            @if($item->end_date && $item->end_date != $item->booking_date)
                                                s/d {{ $item->end_date_formatted }}
                                            @endif
                                        </span>
                                    </div>

                                    <div class="hcard-meta-item">
                                        <span class="meta-item-lbl">Waktu & Durasi</span>
                                        <span class="meta-item-val">
                                            @if($item->room->category === 'layar_iklan')
                                                <span class="ticket-custom-badge">📺 Penayangan Harian</span>
                                            @elseif($item->is_full_day)
                                                <span class="ticket-fullday-badge">⭐ 1 Hari Penuh</span> ({{ substr($item->start_time, 0, 5) }} – {{ substr($item->end_time, 0, 5) }} WIB)
                                            @else
                                                <span class="ticket-custom-badge">⏱️ Jam Pilihan</span> {{ substr($item->start_time, 0, 5) }} – {{ substr($item->end_time, 0, 5) }} WIB
                                            @endif
                                        </span>
                                    </div>

                                    <div class="hcard-meta-item">
                                        <span class="meta-item-lbl">Pemohon / Penanggung Jawab</span>
                                        <span class="meta-item-val">
                                            <strong>{{ $item->user->name }}</strong> ({{ $item->user->email }})
                                        </span>
                                    </div>

                                    @if($item->room->category !== 'layar_iklan')
                                        <div class="hcard-meta-item">
                                            <span class="meta-item-lbl">Estimasi Peserta</span>
                                            <span class="meta-item-val">{{ $item->participant_count }} Orang</span>
                                        </div>
                                    @elseif($item->participant_count > 1)
                                        <div class="hcard-meta-item">
                                            <span class="meta-item-lbl">Target Audiens</span>
                                            <span class="meta-item-val">👁️ {{ number_format($item->participant_count) }} Penonton / Hari</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="hcard-purpose-box">
                                    <span class="purpose-lbl">Agenda / Keperluan:</span>
                                    <p class="purpose-text">{{ $item->purpose }}</p>
                                </div>

                                @if($item->admin_notes)
                                    <div class="hcard-admin-note">
                                        <span class="admin-note-lbl">Catatan Verifikasi Petugas:</span>
                                        <p class="admin-note-text">{{ $item->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="hcard-footer">
                                <span class="hcard-time-created">Diajukan: {{ $item->created_at->format('d M Y, H:i') }} WIB</span>
                                <div class="hcard-actions">
                                    <a href="{{ route('booking.print', ['code' => $item->booking_code]) }}" target="_blank" class="btn btn-outline btn-sm btn-pill" title="Cetak Surat Bukti Resmi">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        <span>Cetak Surat</span>
                                    </a>
                                    <a href="{{ route('booking.check', ['code' => $item->booking_code]) }}" class="btn btn-outline btn-sm btn-pill">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        <span>Detail Tiket</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                    <div class="history-pagination-wrap">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @else
                <!-- Hasil Pencarian Tidak Ditemukan -->
                <div class="history-empty-state">
                    <div class="empty-icon-circle">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="11" y1="8" x2="11" y2="14"></line>
                        </svg>
                    </div>
                    <h3 class="empty-title">Tidak Ditemukan Riwayat Pemesanan</h3>
                    <p class="empty-desc">
                        Tidak ada reservasi yang cocok dengan kata kunci <em>"{{ $search }}"</em>.
                        Pastikan Anda memasukkan Email, Nomor HP/WhatsApp, atau Kode Booking yang sama dengan yang diisi pada formulir pemesanan.
                    </p>
                    <div class="empty-actions">
                        <a href="{{ route('booking.history', ['all' => 1]) }}" class="btn btn-outline btn-pill">
                            Tampilkan Semua Data
                        </a>
                        <a href="{{ route('booking.index') }}#fasilitas" class="btn btn-primary btn-pill">
                            Ajukan Booking Baru
                        </a>
                    </div>
                </div>
            @endif
        @else
            <!-- Belum Melakukan Pencarian: Panduan & Riwayat Terbaru -->
            <div class="history-welcome-section">
                <div class="welcome-guide-card">
                    <div class="guide-circle-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <div class="guide-content">
                        <h4>Cara Melacak Riwayat Pemesanan Anda</h4>
                        <p>
                            Masukkan <strong>Alamat Email</strong>, <strong>Nomor WhatsApp</strong>, <strong>NIM / NIP Identitas</strong>, atau <strong>Kode Booking</strong> pada kotak pencarian di atas untuk melihat seluruh riwayat permohonan fasilitas Anda beserta status verifikasinya.
                        </p>
                    </div>
                    <div class="guide-illus">
                        <svg width="120" height="70" viewBox="0 0 160 90" fill="none">
                            <rect x="20" y="10" width="85" height="70" rx="8" fill="#ffffff" stroke="#bfdbfe" stroke-width="2"/>
                            <line x1="32" y1="28" x2="80" y2="28" stroke="#93c5fd" stroke-width="3" stroke-linecap="round"/>
                            <line x1="32" y1="42" x2="70" y2="42" stroke="#cbd5e1" stroke-width="2.5" stroke-linecap="round"/>
                            <line x1="32" y1="54" x2="60" y2="54" stroke="#e2e8f0" stroke-width="2.5" stroke-linecap="round"/>
                            <circle cx="105" cy="50" r="24" fill="#eff6ff" stroke="#2563eb" stroke-width="3"/>
                            <line x1="122" y1="67" x2="145" y2="85" stroke="#1d4ed8" stroke-width="5" stroke-linecap="round"/>
                            <path d="M98 48l5 5 10-10" stroke="#2563eb" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                @if($recentBookings->count() > 0)
                    <div class="recent-activities-section">
                        <div class="recent-section-header">
                            <div class="recent-title-group">
                                <div class="recent-title-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.2">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                    </svg>
                                </div>
                                <h3 class="recent-title">Aktivitas Pemesanan Terkini di Perpustakaan</h3>
                            </div>
                            <a href="{{ route('booking.history', ['all' => 1]) }}" class="recent-view-all">
                                <span>Lihat Semua Riwayat</span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                        <div class="recent-grid-row">
                            @foreach($recentBookings->take(4) as $rb)
                                @php
                                    $cardThumb = $rb->room->card_image;
                                    if ($rb->room->id == 1 && $loop->iteration == 2 && isset($rb->room->gallery_images[1])) {
                                        $cardThumb = $rb->room->gallery_images[1];
                                    }
                                    $isVideotron = $rb->room->category === 'layar_iklan' || str_contains(strtolower($rb->room->name), 'videotron');
                                @endphp
                                <div class="recent-hcard">
                                    <div class="recent-hcard-thumb">
                                        <img src="{{ $cardThumb }}" alt="{{ $rb->room->name }}" loading="lazy">
                                    </div>
                                    <div class="recent-hcard-body">
                                        <div class="recent-hcard-top">
                                            <h4 class="recent-hcard-title">
                                                @if($isVideotron)
                                                    <span>Layar Iklan Digital</span>
                                                    <span class="recent-hcard-subcat">(Videotron & Digital Signage)</span>
                                                @else
                                                    <span>{{ $rb->room->name }}</span>
                                                @endif
                                            </h4>
                                            <span class="hcard-status-badge status-{{ $rb->status }}">
                                                @if($rb->status === 'disetujui')
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    <span>Disetujui</span>
                                                @elseif($rb->status === 'menunggu')
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                    <span>Menunggu</span>
                                                @else
                                                    <span>{{ ucfirst($rb->status) }}</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="recent-hcard-code">Kode: <strong>{{ substr($rb->booking_code, 0, 12) }}***</strong></div>
                                        <div class="recent-hcard-meta">
                                            <span class="meta-item">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                <span>{{ $rb->booking_date->format('d M Y') }}</span>
                                            </span>
                                            <span class="meta-item">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                <span>{{ substr($rb->start_time, 0, 5) }} - {{ substr($rb->end_time, 0, 5) }} WIB</span>
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('booking.check', ['code' => $rb->booking_code]) }}" class="recent-hcard-arrow" title="Lihat Detail Tiket">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Muat riwayat pemesanan lokal dari browser localStorage
    loadLocalBookingHistory();
});

function loadLocalBookingHistory() {
    const container = document.getElementById('localHistoryCardsContainer');
    const wrap = document.getElementById('localDeviceHistorySection');
    const countBadge = document.getElementById('deviceHistoryCount');
    if (!container || !wrap) return;

    try {
        const raw = localStorage.getItem('library_booking_history');
        if (!raw) return;
        const codes = JSON.parse(raw);
        if (!Array.isArray(codes) || codes.length === 0) return;

        // Fetch detail terbaru dari API
        fetch(`/api/booking-history?codes=${encodeURIComponent(codes.join(','))}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.bookings && data.bookings.length > 0) {
                wrap.style.display = 'block';
                if (countBadge) countBadge.textContent = data.bookings.length;

                container.innerHTML = '';
                data.bookings.forEach(b => {
                    const card = document.createElement('div');
                    card.className = `local-item-card status-border-${b.status}`;
                    card.innerHTML = `
                        <div class="local-card-top">
                            <span class="local-card-room">${b.room_name}</span>
                            <span class="hcard-status-badge status-${b.status}">${b.status.toUpperCase()}</span>
                        </div>
                        <div class="local-card-code">
                            <span>Kode: <strong>${b.booking_code}</strong></span>
                            <button type="button" class="btn-copy-code-sm" onclick="copyBookingCode('${b.booking_code}')">Salin</button>
                        </div>
                        <div class="local-card-date">
                            📅 ${b.booking_date_formatted} (${b.start_time} - ${b.end_time} WIB)
                        </div>
                        <div class="local-card-actions">
                            <a href="/cek-booking?code=${encodeURIComponent(b.booking_code)}" class="btn-local-check">Lihat E-Ticket &rarr;</a>
                        </div>
                    `;
                    container.appendChild(card);
                });
            }
        })
        .catch(() => {});
    } catch (e) {}
}

function clearLocalBookingHistory() {
    if (confirm('Bersihkan riwayat pemesanan yang tersimpan di peramban ini?')) {
        localStorage.removeItem('library_booking_history');
        const wrap = document.getElementById('localDeviceHistorySection');
        if (wrap) wrap.style.display = 'none';
        if (typeof showToast === 'function') {
            showToast('Riwayat pemesanan di perangkat ini berhasil dibersihkan.');
        }
    }
}
</script>
@endsection
