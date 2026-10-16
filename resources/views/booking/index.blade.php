@extends('layouts.app')

@section('title', 'Perpustakaan Dan Galeri Kota Bogor - Peminjaman Ruangan & Videotron')

@section('content')

<!-- Hero Section (Matching Exact User Mockup) -->
<section class="hero">
    <div class="container">
        <div class="hero-layout">
            
            <!-- Left Column: Content -->
            <div class="hero-content">
                <div class="hero-badge">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span>Layanan Reservasi Resmi Perpustakaan Dan Galeri Kota Bogor</span>
                </div>

                <h1 class="hero-title">
                    Reservasi Ruangan &<br>
                    <span class="hero-title-highlight">Videotron</span><br>
                    Perpustakaan Dan Galeri<br>
                    Kota Bogor
                </h1>

                <p class="hero-description">
                    Ajukan izin peminjaman Auditorium, Ruang Multimedia, Ruang Rapat eksekutif, hingga slot penayangan poster/video promosi pada Videotron Lobi Perpustakaan Dan Galeri Kota Bogor secara terpadu dan transparan.
                </p>

                <div class="hero-actions">
                    <button type="button" class="btn btn-primary btn-pill hero-btn-cta" id="btnHeroBooking" onclick="openBookingModal()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>Mulai Reservasi Sekarang</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                    
                    <a href="#ketersediaan" class="btn btn-outline btn-pill hero-btn-sub" id="btnHeroJadwal" onclick="if(typeof toggleScheduleExplorer === 'function'){ toggleScheduleExplorer(true); }">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>Cek Jam & Hari Kosong</span>
                    </a>

                    <a href="#fasilitas" class="btn btn-outline btn-pill hero-btn-sub" id="btnHeroKatalog">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span>Lihat Katalog</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Perpustakaan Kota Bogor Building Photo with Floating Location Badge -->
            <div class="hero-visual">
                <img src="{{ asset('images/gedung-perpus-bogor-real.jpg') }}" alt="Gedung Perpustakaan dan Galeri Kota Bogor" class="hero-visual-img" loading="lazy">
                
                <div class="hero-location-pill">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span class="hero-location-text">Jl. Kapten Muslihat No. 21 Kota Bogor</span>
                    <a href="https://maps.google.com/?q=Perpustakaan+dan+Galeri+Kota+Bogor" target="_blank" rel="noopener" class="hero-location-arrow-btn" title="Lihat di Google Maps" aria-label="Buka Google Maps">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Value Proposition Ribbon (4 Items in Clean Rounded White Bar) -->
<section class="container value-ribbon-section">
    <div class="value-ribbon-card">
        <!-- Item 1: Fasilitas Lengkap -->
        <div class="value-item">
            <div class="value-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18"></path>
                    <path d="M5 21V10"></path>
                    <path d="M19 21V10"></path>
                    <path d="M9 21V10"></path>
                    <path d="M15 21V10"></path>
                    <path d="M2 10l10-6 10 6"></path>
                </svg>
            </div>
            <div class="value-info">
                <h4 class="value-title">Fasilitas Lengkap</h4>
                <p class="value-desc">Ruang representatif & modern</p>
            </div>
        </div>

        <div class="value-divider"></div>

        <!-- Item 2: Proses Mudah & Transparan -->
        <div class="value-item">
            <div class="value-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <polyline points="9 12 11 14 15 10"></polyline>
                </svg>
            </div>
            <div class="value-info">
                <h4 class="value-title">Proses Mudah & Transparan</h4>
                <p class="value-desc">Bisa dicek real-time</p>
            </div>
        </div>

        <div class="value-divider"></div>

        <!-- Item 3: Jadwal Fleksibel -->
        <div class="value-item">
            <div class="value-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="value-info">
                <h4 class="value-title">Jadwal Fleksibel</h4>
                <p class="value-desc">Sesuaikan dengan kebutuhan Anda</p>
            </div>
        </div>

        <div class="value-divider"></div>

        <!-- Item 4: Untuk Umum & Instansi -->
        <div class="value-item">
            <div class="value-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="value-info">
                <h4 class="value-title">Untuk Umum & Instansi</h4>
                <p class="value-desc">Sekolah, komunitas, perusahaan, dll.</p>
            </div>
        </div>
    </div>
</section>

<!-- Facilities Catalog Section Matching Screenshot -->
<section class="container facilities-mockup-section" id="fasilitas">
    <div class="facilities-mockup-header">
        <div class="facilities-header-left">
            <div class="section-tag-group">
                <span class="vertical-accent-line"></span>
                <span class="section-tag-uppercase">RUANGAN & FASILITAS</span>
            </div>
            <h2 class="facilities-heading">Pilihan Ruangan Kami</h2>
        </div>
        <div class="facilities-header-right">
            <a href="#ketersediaan" class="link-see-all-facilities" onclick="if(typeof toggleScheduleExplorer === 'function'){ toggleScheduleExplorer(true); }">
                <span>Lihat Semua Fasilitas</span>
                <span class="arrow-symbol">→</span>
            </a>
        </div>
    </div>

    <!-- 4 Room Cards Grid (Auditorium, Multimedia, Rapat, Videotron Lobi) -->
    <div class="room-cards-mockup-grid">
        @foreach($rooms as $room)
            @php
                $isVideotron = $room->category === 'layar_iklan' || str_contains(strtolower($room->name), 'videotron');
                $galleryPhotos = $room->gallery_images;
                $galleryCount = count($galleryPhotos);
            @endphp
            <div class="room-card-box {{ $isVideotron ? 'room-card-videotron' : 'room-card-meeting' }}" id="roomCard-{{ $room->id }}" data-room-id="{{ $room->id }}" data-photo-index="0" data-photos='@json($galleryPhotos)'>
                
                <!-- Card Top: Image + Category Badge + Carousel Arrow Button -->
                <div class="room-card-image-wrap">
                    <img src="{{ $room->card_image }}" alt="{{ $room->display_name }}" class="room-card-img" id="cardImg-{{ $room->id }}" loading="lazy">
                    
                    <span class="room-card-badge {{ $isVideotron ? 'badge-purple-theme' : 'badge-blue-theme' }}">
                        {{ $room->badge_label }}
                    </span>

                    <button type="button" class="room-card-next-arrow" onclick="cycleCardPhoto(event, {{ $room->id }})" title="Lihat foto berikutnya" aria-label="Foto Berikutnya">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>

                <!-- Card Body -->
                <div class="room-card-content">
                    <div class="room-card-title-row">
                        <div class="room-title-icon-wrap {{ $isVideotron ? 'icon-purple-tint' : 'icon-blue-tint' }}">
                            @if($isVideotron)
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                            @elseif(str_contains(strtolower($room->name), 'multimedia'))
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                            @elseif(str_contains(strtolower($room->name), 'rapat'))
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            @else
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 21h18"></path>
                                    <path d="M5 21V10"></path>
                                    <path d="M19 21V10"></path>
                                    <path d="M9 21V10"></path>
                                    <path d="M15 21V10"></path>
                                    <path d="M2 10l10-6 10 6"></path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="room-card-name">{{ $room->display_name }}</h3>
                    </div>

                    <p class="room-card-description">
                        {{ \Illuminate\Support\Str::limit($room->description, 88) }}
                    </p>

                    <!-- Meta Specs Chips Row -->
                    <div class="room-spec-chips-row">
                        <!-- Chip 1: Kapasitas -->
                        <div class="room-spec-chip">
                            <div class="spec-chip-icon">
                                @if($isVideotron)
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                    </svg>
                                @else
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                    </svg>
                                @endif
                            </div>
                            <div class="spec-chip-text">
                                <span class="spec-chip-label">Kapasitas</span>
                                <span class="spec-chip-value">{{ $room->capacity_text }}</span>
                            </div>
                        </div>

                        <!-- Chip 2: Fasilitas Lengkap / Resolusi -->
                        <div class="room-spec-chip">
                            <div class="spec-chip-icon">
                                @if($isVideotron)
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                @elseif(str_contains(strtolower($room->name), 'rapat'))
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                                        <polyline points="17 2 12 7 7 2"></polyline>
                                    </svg>
                                @elseif(str_contains(strtolower($room->name), 'multimedia'))
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                    </svg>
                                @else
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                    </svg>
                                @endif
                            </div>
                            <div class="spec-chip-text">
                                <span class="spec-chip-label">{{ $isVideotron ? 'Resolusi' : 'Fasilitas Lengkap' }}</span>
                                <span class="spec-chip-value">{{ $room->key_facility }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card CTA Button -->
                    <div class="room-card-cta-wrap">
                        <button type="button" class="btn btn-pill room-detail-btn {{ $isVideotron ? 'btn-purple-pill' : 'btn-blue-pill' }}" onclick="openGalleryModal({{ $room->id }}, '{{ $room->display_name }}', '{{ $room->category }}')">
                            <span>Lihat Detail</span>
                            <span class="arrow-right">→</span>
                        </button>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Dual Bottom Cards (Cek Hari & Jam Kosong + Tata Tertib Peminjaman) -->
<section class="container bottom-dual-cards-section">
    <div class="bottom-dual-grid">
        
        <!-- Left Card: Cek Hari & Jam Kosong Fasilitas -->
        <div class="bottom-card schedule-summary-card">
            <div class="card-left-icon">
                <div class="big-cal-icon-wrap">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="3" ry="3"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <circle cx="12" cy="15.5" r="2.5"></circle>
                        <polyline points="12 14.5 12 15.5 13 15.5"></polyline>
                    </svg>
                </div>
            </div>
            
            <div class="card-mid-content">
                <h3 class="card-main-title">Cek Hari & Jam Kosong Fasilitas</h3>
                <p class="card-subtitle-desc">Pilih fasilitas dan tanggal untuk melihat secara langsung hari dan jam mana yang masih kosong (tersedia) atau sudah penuh (terisi) sesuai jam operasional resmi.</p>
                
                <div class="card-time-chips">
                    <span class="time-chip sun-chip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <span><strong>Senin - Kamis</strong> 08:00 - 15:00 WIB</span>
                    </span>
                    <span class="time-chip moon-chip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        <span><strong>Sabtu - Minggu</strong> 08:00 - 20:00 WIB</span>
                    </span>
                </div>
            </div>

            <div class="card-right-action">
                <button type="button" class="btn btn-primary btn-pill btn-card-action" onclick="toggleScheduleExplorer(true)">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Lihat Jadwal</span>
                </button>
            </div>
        </div>

        <!-- Right Card: Tata Tertib Peminjaman Fasilitas -->
        <div class="bottom-card rules-summary-card">
            <div class="rules-summary-top">
                <div class="rules-header-text">
                    <div class="rules-title-row">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        <h3 class="rules-main-title">Tata Tertib Peminjaman Fasilitas</h3>
                    </div>
                    <p class="rules-dept-sub">Perpustakaan Dan Galeri Kota Bogor — Dinas Arsip dan Perpustakaan Kota Bogor</p>
                </div>
                <div class="rules-top-cta">
                    <button type="button" class="btn btn-primary btn-pill btn-sm btn-rules-booking" onclick="openBookingModal()">
                        <span>+ Ajukan Booking Sekarang</span>
                        <span class="arrow-right">→</span>
                    </button>
                </div>
            </div>

            <!-- 4 Policy Mini Tiles -->
            <div class="rules-tiles-row">
                <div class="rule-mini-tile tile-blue" onclick="openRulesDetailModal('operasional')" title="Klik untuk rincian tata tertib">
                    <div class="tile-icon icon-blue">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <span class="tile-label">Jam Operasional Fasilitas</span>
                </div>

                <div class="rule-mini-tile tile-purple" onclick="openRulesDetailModal('videotron')" title="Klik untuk rincian tata tertib">
                    <div class="tile-icon icon-purple">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    </div>
                    <span class="tile-label">Penayangan Videotron Lobi</span>
                </div>

                <div class="rule-mini-tile tile-green" onclick="openRulesDetailModal('pengajuan')" title="Klik untuk rincian tata tertib">
                    <div class="tile-icon icon-green">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <span class="tile-label">Waktu Pengajuan Minimal</span>
                </div>

                <div class="rule-mini-tile tile-orange" onclick="openRulesDetailModal('kebersihan')" title="Klik untuk rincian tata tertib">
                    <div class="tile-icon icon-orange">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <polyline points="9 12 11 14 15 10"></polyline>
                        </svg>
                    </div>
                    <span class="tile-label">Kebersihan & Ketertiban Ruangan</span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ========================================================================= -->
<!-- SCHEDULE & AVAILABILITY EXPLORER (Cek Hari & Jam Kosong) -->
<!-- ========================================================================= -->
<section class="container schedule-section" id="ketersediaan">
    <span id="jadwal" style="display: block; position: relative; top: -85px; visibility: hidden;"></span>
    <div class="section-header" style="margin-bottom: 24px;">
        <div>
            <div class="section-tag-pill">
                <span class="tag-dot"></span>
                <span>Jadwal & Ketersediaan Real-Time</span>
            </div>
            <h2 class="section-title">Cek Hari & Jam Kosong Fasilitas</h2>
            <p class="section-desc">Pilih fasilitas dan tanggal untuk melihat secara langsung hari dan jam mana yang masih kosong (tersedia) atau sudah penuh (terisi) sesuai jam operasional resmi.</p>
        </div>

        <div class="schedule-quick-info">
            <span class="schedule-info-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Senin–Kamis: 08:00–15:00 WIB</span>
            </span>
            <span class="schedule-info-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Sabtu–Minggu: 08:00–20:00 WIB</span>
            </span>
        </div>
    </div>

    <!-- Explorer Main Card -->
    <div class="schedule-explorer-card">
        
        <!-- Room Selector Tabs -->
        <div class="schedule-nav-tabs" id="scheduleRoomTabs">
            @foreach($rooms as $idx => $r)
                <button type="button" 
                        class="schedule-room-tab {{ $idx === 0 ? 'active' : '' }}" 
                        data-room-id="{{ $r->id }}" 
                        data-room-category="{{ $r->category }}"
                        onclick="selectScheduleRoom({{ $r->id }})">
                    @if($r->category === 'layar_iklan')
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    @else
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    @endif
                    <span>{{ $r->name }}</span>
                </button>
            @endforeach
        </div>

        <!-- Date & Week Selection Bar -->
        <div class="schedule-control-bar">
            <div>
                <div class="schedule-calendar-label">
                    <div class="calendar-icon-circle">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <span>Pilih Hari & Tanggal</span>
                </div>
                <div class="schedule-calendar-sub">Pilih salah satu kartu hari di bawah atau gunakan tombol panah untuk melihat rincian jam ketersediaan.</div>
            </div>

            <div class="schedule-date-picker-wrap">
                <span class="schedule-date-label">Pilih Tanggal:</span>
                <div class="schedule-date-nav-group">
                    <button type="button" class="btn-day-nav" onclick="navigateScheduleDay(-1)" title="Hari Sebelumnya" aria-label="Hari Sebelumnya">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <div class="schedule-input-wrapper">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <input type="date" id="scheduleCustomDate" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" onchange="onScheduleDateChange(this.value)">
                    </div>
                    <button type="button" class="btn-day-nav" onclick="navigateScheduleDay(1)" title="Hari Berikutnya" aria-label="Hari Berikutnya">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
                <button type="button" class="btn btn-outline btn-pill btn-today" onclick="setScheduleToday()" title="Kembali ke Hari Ini">
                    <span class="today-dot"></span>
                    <span>Hari Ini</span>
                </button>
            </div>
        </div>

        <!-- 7-Day Week Strip Container (Hari mana yang kosong / penuh) -->
        <div class="week-strip-container">
            <div class="week-strip-grid" id="weekStripGrid">
                <!-- Populated dynamically via JS -->
                <div style="grid-column: 1 / -1; padding: 24px; text-align: center; color: #94a3b8;">
                    Memuat status ketersediaan hari...
                </div>
            </div>
        </div>

        <!-- Detail Hourly Slots Panel (Jam di hari tersebut mana yang kosong / penuh) -->
        <div class="schedule-detail-panel" id="scheduleDetailPanel">
            <!-- Header for Selected Day -->
            <div class="detail-panel-header">
                <div class="detail-room-info">
                    <div class="detail-room-chip" id="detailRoomBadge">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Auditorium</span>
                    </div>
                    <div class="detail-date-display" id="detailDateDisplay">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>{{ date('d F Y') }}</span>
                    </div>
                    <div class="detail-hours-badge" id="detailHoursBadge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>08:00 – 15:00 WIB</span>
                    </div>
                </div>

                <div class="detail-summary-pills" id="detailSummaryPills">
                    <span class="sum-pill sum-pill-kosong" id="pillKosong">
                        <span class="dot-status dot-green pulse"></span>
                        <strong id="valCountKosong">0</strong>
                        <span class="sum-label">Jam Kosong</span>
                    </span>
                    <span class="sum-pill sum-pill-terisi" id="pillTerisi">
                        <span class="dot-status dot-red"></span>
                        <strong id="valCountTerisi">0</strong>
                        <span class="sum-label">Jam Penuh</span>
                    </span>
                    <span class="sum-pill sum-pill-menunggu" id="pillMenunggu">
                        <span class="dot-status dot-yellow"></span>
                        <strong id="valCountMenunggu">0</strong>
                        <span class="sum-label">Menunggu</span>
                    </span>

                    <button type="button" class="btn btn-fullday-badge" id="btnQuickFullDay" onclick="bookFullDay(currentScheduleRoomId, currentScheduleDate)" title="Pesan fasilitas ini satu hari penuh">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <span>Pesan 1 Hari Penuh</span>
                    </button>
                </div>
            </div>

            <!-- Time Slots Grid -->
            <div class="slot-cards-grid" id="slotCardsGrid">
                <!-- Populated dynamically via JS -->
            </div>

            <!-- Panel Legend & Action Footer -->
            <div class="schedule-legend-bar">
                <div class="legend-items">
                    <div class="legend-item legend-item-kosong">
                        <span class="legend-dot dot-kosong"></span>
                        <span><strong>Kosong</strong> (Siap Dipesan)</span>
                    </div>
                    <div class="legend-item legend-item-terisi">
                        <span class="legend-dot dot-terisi"></span>
                        <span><strong>Penuh</strong> (Sudah Dipesan)</span>
                    </div>
                    <div class="legend-item legend-item-menunggu">
                        <span class="legend-dot dot-menunggu"></span>
                        <span><strong>Menunggu</strong> (Dalam Verifikasi)</span>
                    </div>
                    <div class="legend-item legend-item-libur">
                        <span class="legend-dot dot-libur"></span>
                        <span><strong>Libur</strong> (Jumat / Tutup)</span>
                    </div>
                </div>

                <div class="schedule-action-cta">
                    <button type="button" class="btn btn-outline btn-pill btn-fullday-secondary" id="btnScheduleFullDayAction" onclick="bookFullDay(currentScheduleRoomId, currentScheduleDate)" title="Booking satu hari penuh">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <span>Pesan 1 Hari Penuh</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-pill btn-schedule-cta" id="btnScheduleBookAction" onclick="openBookingModal(currentScheduleRoomId)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Pilih Jam Sendiri & Pesan</span>
                    </button>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Workflow Steps Section -->
<section class="steps-section" id="alur">
    <span id="panduan" style="display: block; position: relative; top: -85px; visibility: hidden;"></span>
    <div class="container">
        <div class="section-header" style="text-align: center; justify-content: center; margin-bottom: 48px;">
            <div style="max-width: 680px; margin: 0 auto;">
                <div class="section-tag-pill">
                    <span class="tag-dot"></span>
                    <span>Alur Pengajuan</span>
                </div>
                <h2 class="section-title">4 Langkah Mudah Reservasi</h2>
                <p class="section-desc">Panduan singkat proses pengajuan peminjaman fasilitas dari pengisian formulir hingga verifikasi persetujuan.</p>
            </div>
        </div>

        <div class="steps-grid">
            <!-- Step 1 -->
            <div class="step-card step-card-1">
                <div class="step-card-header">
                    <div class="step-icon-wrap step-icon-1">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <span class="step-pill step-pill-1">Langkah 01</span>
                </div>
                <div class="step-body">
                    <h4 class="step-title">Pilih Fasilitas</h4>
                    <p class="step-text">Tentukan fasilitas yang Anda butuhkan (Auditorium, Ruang Multimedia, Ruang Rapat, atau Videotron Lobi).</p>
                </div>
                <div class="step-connector-arrow">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step-card step-card-2">
                <div class="step-card-header">
                    <div class="step-icon-wrap step-icon-2">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </div>
                    <span class="step-pill step-pill-2">Langkah 02</span>
                </div>
                <div class="step-body">
                    <h4 class="step-title">Isi Formulir</h4>
                    <p class="step-text">Lengkapi data pemohon (NIM/NIP, WhatsApp), jadwal peminjaman, serta keperluan acara atau unggah materi poster iklan.</p>
                </div>
                <div class="step-connector-arrow">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step-card step-card-3">
                <div class="step-card-header">
                    <div class="step-icon-wrap step-icon-3">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <line x1="6" y1="8" x2="10" y2="8"></line>
                            <line x1="6" y1="12" x2="18" y2="12"></line>
                            <line x1="6" y1="16" x2="14" y2="16"></line>
                        </svg>
                    </div>
                    <span class="step-pill step-pill-3">Langkah 03</span>
                </div>
                <div class="step-body">
                    <h4 class="step-title">Dapatkan Kode Booking</h4>
                    <p class="step-text">Sistem otomatis menerbitkan kode unik reservasi (cth: <code class="booking-code-chip">BKG-2024-001</code>) sebagai bukti pengajuan online Anda.</p>
                </div>
                <div class="step-connector-arrow">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="step-card step-card-4">
                <div class="step-card-header">
                    <div class="step-icon-wrap step-icon-4">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <polyline points="9 12 11 14 15 10"></polyline>
                        </svg>
                    </div>
                    <span class="step-pill step-pill-4">Langkah 04</span>
                </div>
                <div class="step-body">
                    <h4 class="step-title">Verifikasi & Tayang</h4>
                    <p class="step-text">Petugas perpustakaan memeriksa permohonan. Anda dapat memantau status persetujuan kapan pun melalui menu Cek Status.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- MODAL: DETAIL TATA TERTIB & KETENTUAN -->
<!-- ========================================================================= -->
<div class="modal-backdrop" id="rulesDetailModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 580px;">
        <div class="modal-header">
            <div class="modal-title-group">
                <h3 class="modal-title" id="rulesModalTitle">Tata Tertib Peminjaman Fasilitas</h3>
                <p class="modal-subtitle">Perpustakaan Dan Galeri Kota Bogor — Dinas Arsip dan Perpustakaan</p>
            </div>
            <button type="button" class="modal-close" onclick="closeRulesDetailModal()" aria-label="Tutup">&times;</button>
        </div>
        <div class="modal-body" style="padding: 24px 28px;">
            <div id="rulesModalContent" class="rules-modal-detail-box">
                <!-- Dynamically populated or switchable -->
            </div>
            <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn btn-outline btn-pill" onclick="closeRulesDetailModal()">Tutup</button>
                <button type="button" class="btn btn-primary btn-pill" onclick="closeRulesDetailModal(); openBookingModal();">
                    <span>Ajukan Booking Sekarang</span>
                    <span>→</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: GALERI FOTO RUANGAN -->
<!-- ========================================================================= -->
<div class="modal-backdrop" id="galleryModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 780px;">
        <div class="modal-header">
            <div class="modal-title-group">
                <h3 id="galleryModalTitle">Foto Ruangan</h3>
                <p id="galleryModalSubtitle">Dokumentasi interior fasilitas</p>
            </div>
            <button type="button" class="modal-close" onclick="closeGalleryModal()" aria-label="Tutup">&times;</button>
        </div>

        <div class="modal-body" style="padding: 0 0 24px;">
            <!-- Main large photo -->
            <div class="gallery-main" id="galleryMain">
                <img src="" alt="Foto Ruangan" id="galleryMainImg" style="width:100%; max-height:440px; object-fit:cover; display:block;">
                <div id="galleryNoPhoto">
                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:10px; opacity:0.6;">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span>Foto dokumentasi belum tersedia</span>
                </div>
                <!-- Navigation arrows -->
                <button type="button" id="galleryPrev" class="gallery-arrow gallery-arrow-left" onclick="galleryNav(-1)">&#8249;</button>
                
                <button type="button" id="galleryNext" class="gallery-arrow gallery-arrow-right" onclick="galleryNav(1)">&#8250;</button>
                <div class="gallery-counter" id="galleryCounter"></div>
            </div>

            <!-- Thumbnail strip -->
            <div id="galleryThumbs" style="display:flex; gap:8px; padding:12px 20px; overflow-x:auto; scrollbar-width:thin;"></div>

            <!-- Book button inside gallery -->
            <div style="padding: 0 20px; display:flex; justify-content:flex-end;">
                <button type="button" class="btn btn-primary btn-pill" id="galleryBookBtn" onclick="">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    Pesan Ruangan Ini
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
