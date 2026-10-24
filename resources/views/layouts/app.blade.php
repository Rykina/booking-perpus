<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Dan Galeri Kota Bogor - Peminjaman Fasilitas & Videotron')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Instant Dark Mode Detection (Zero-FOUC) -->
    <script>
        (function() {
            var theme = localStorage.getItem('booking_perpus_theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                document.documentElement.classList.add('dark-mode');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                document.documentElement.classList.remove('dark-mode');
            }
        })();
    </script>

    <!-- Vanilla CSS Design System with Cache-Buster -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">

    <!-- Definitive Navbar Alignment: Centered, Non-Overlapping & Responsive -->
    <style>
        /* ==========================================================================
       PERFECT NAVBAR: 100% DEAD-CENTER & ZERO OVERLAP (CSS Grid Symmetrical)
       ========================================================================== */
        html {
            scroll-behavior: smooth;
        }

        #fasilitas,
        #ketersediaan,
        #alur,
        #jadwal,
        #panduan {
            scroll-margin-top: 85px;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
            padding: 8px 0;
            width: 100%;
        }

        .navbar .container {
            max-width: 100% !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
            margin: 0 auto !important;
            box-sizing: border-box !important;
        }

        .navbar .nav-inner {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 12px !important;
            width: 100% !important;
            box-sizing: border-box !important;
            position: relative !important;
        }

        /* Saat Belum Login: Menu Navigasi diletakkan Presisi 100% DEAD-CENTER di tengah layar */
        .navbar .nav-inner.is-guest .nav-center {
            position: absolute !important;
            left: 50% !important;
            top: 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
            z-index: 10 !important;
        }

        /* Saat Sudah Login: Menu Navigasi bergeser rapi agar serasi berdampingan dengan profil */
        .navbar .nav-inner.is-auth .nav-center {
            position: static !important;
            transform: none !important;
            margin: 0 auto !important;
            flex: 0 0 auto !important;
        }

        .brand {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            text-decoration: none !important;
            flex: 0 0 auto !important;
            flex-shrink: 0 !important;
        }

        .nav-center {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex: 0 0 auto !important;
            margin: 0 auto !important;
            min-width: 0 !important;
        }

        .nav-links {
            display: inline-flex !important;
            align-items: center !important;
            gap: 3px !important;
            list-style: none !important;
            background: #f8fafc !important;
            padding: 3px 6px !important;
            border-radius: 9999px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.02) !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
            white-space: nowrap !important;
        }

        .nav-link {
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            color: #64748b !important;
            padding: 5px 11px !important;
            border-radius: 9999px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
        }

        .nav-link:hover {
            color: #0f172a !important;
            background: rgba(255, 255, 255, 0.9) !important;
        }

        .nav-link.active {
            color: #ffffff !important;
            background: #1a73e8 !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 10px rgba(26, 115, 232, 0.35) !important;
        }

        .nav-link.active svg {
            stroke: #ffffff !important;
        }

        .nav-actions {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 6px !important;
            white-space: nowrap !important;
            flex: 0 0 auto !important;
            flex-shrink: 0 !important;
        }

        .btn-search-pill {
            background: #ffffff !important;
            color: #334155 !important;
            border: 1px solid #e2e8f0 !important;
            padding: 5px 12px !important;
            border-radius: 9999px !important;
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            cursor: pointer !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
        }

        .btn-search-pill:hover {
            background: #f8fafc !important;
            color: #0f172a !important;
        }

        .btn-history-pill {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #334155 !important;
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            padding: 5px 12px !important;
            border-radius: 9999px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            cursor: pointer !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
        }

        .btn-history-pill:hover {
            background: #f8fafc !important;
            color: #0f172a !important;
        }

        .btn-history-pill.active {
            background: #1a73e8 !important;
            border-color: #1a73e8 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 10px rgba(26, 115, 232, 0.35) !important;
        }

        .btn-history-pill.active svg {
            stroke: #ffffff !important;
        }

        .btn-header-cta {
            background: #1a73e8 !important;
            border: 1px solid #1a73e8 !important;
            color: #ffffff !important;
            padding: 6px 14px !important;
            font-size: 0.8125rem !important;
            font-weight: 700 !important;
            border-radius: 9999px !important;
            box-shadow: 0 2px 10px rgba(26, 115, 232, 0.28) !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            cursor: pointer !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
        }

        .btn-header-cta:hover {
            background: #1557b0 !important;
            border-color: #1557b0 !important;
        }

        .btn-login-google-pill {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #1e293b !important;
            padding: 5px 12px !important;
            border-radius: 9999px !important;
            font-size: 0.8125rem !important;
            font-weight: 700 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
        }

        .btn-login-google-pill:hover {
            background: #f8fafc !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }

        .user-menu-pill {
            display: inline-flex !important;
            align-items: center !important;
            gap: 7px !important;
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            padding: 3px 10px 3px 4px !important;
            border-radius: 9999px !important;
        }

        .user-avatar-sm {
            width: 26px !important;
            height: 26px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
        }

        .user-avatar-placeholder {
            background: #1a73e8 !important;
            color: #ffffff !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
        }

        .user-name-label {
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            white-space: nowrap !important;
            max-width: 90px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .btn-admin-pill {
            background: #1e293b !important;
            color: #ffffff !important;
            padding: 3px 8px !important;
            border-radius: 9999px !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
        }

        .btn-admin-pill:hover {
            background: #0f172a !important;
        }

        .btn-logout-pill {
            background: #fee2e2 !important;
            color: #dc2626 !important;
            border: 1px solid #fecaca !important;
            padding: 3px 8px !important;
            border-radius: 9999px !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            transition: all 0.2s ease !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
        }

        .btn-logout-pill:hover {
            background: #fca5a5 !important;
            color: #991b1b !important;
        }

        .btn-logout-link {
            background: transparent !important;
            border: none !important;
            padding: 2px !important;
            color: #94a3b8 !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            transition: color 0.2s ease !important;
        }

        .btn-logout-link:hover {
            color: #ef4444 !important;
        }

        /* Utility Action Buttons: Clean & Compact Icon Pills (Zero Collision) */
        .btn-text-nav {
            display: none !important;
        }

        .btn-logout-text {
            display: none !important;
        }

        .btn-search-pill,
        .btn-history-pill,
        .btn-notification-pill {
            padding: 5px 9px !important;
            height: 32px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }

        .btn-logout-pill {
            padding: 3px 6px !important;
            height: 24px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }

        .user-name-label {
            max-width: 80px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        /* Show full text labels only on very large displays (>= 1650px) */
        @media (min-width: 1650px) {
            .btn-text-nav {
                display: inline !important;
            }

            .btn-logout-text {
                display: inline !important;
            }

            .btn-search-pill,
            .btn-history-pill,
            .btn-notification-pill {
                padding: 5px 12px !important;
            }

            .user-name-label {
                max-width: 130px !important;
            }
        }

        @media (max-width: 1200px) {
            .brand-subtitle {
                display: none !important;
            }

            .nav-link svg {
                display: none !important;
            }

            .nav-link {
                padding: 4px 8px !important;
                font-size: 0.77rem !important;
            }

            .navbar .nav-inner {
                gap: 8px !important;
            }

            .user-name-label {
                max-width: 60px !important;
            }
        }

        @media (max-width: 1024px) {
            .navbar .nav-inner {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
            }

            .navbar .nav-inner.is-guest .nav-center,
            .navbar .nav-inner.is-auth .nav-center,
            .nav-center {
                display: none !important;
            }

            .btn-search-pill,
            .btn-history-pill {
                display: none !important;
            }

            .nav-toggle {
                display: flex !important;
            }
        }
    </style>

    <link rel="icon" type="image/png" href="{{ asset('images/logo-perpus-bogor.png') }}">
</head>

<body>

    <!-- Header Navigation -->
    <header class="navbar" id="mainNavbar">
        <div class="container nav-inner {{ Auth::check() ? 'is-auth' : 'is-guest' }}">
            <a href="{{ route('booking.index') }}" class="brand" id="navBrandLink">
                <div class="brand-logo-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1a73e8"
                        stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V10"></path>
                        <path d="M19 21V10"></path>
                        <path d="M9 21V10"></path>
                        <path d="M15 21V10"></path>
                        <path d="M2 10l10-6 10 6"></path>
                    </svg>
                </div>
                <div class="brand-text">
                    <span class="brand-title">Perpustakaan & Galeri</span>
                    <span class="brand-subtitle">KOTA BOGOR</span>
                </div>
            </a>

            <nav class="nav-center">
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('booking.index') }}"
                            class="nav-link {{ request()->routeIs('booking.index') ? 'active' : '' }}"
                            id="navLinkBeranda">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.index') }}#fasilitas" class="nav-link" id="navLinkFasilitas">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                            </svg>
                            <span>Fasilitas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.index') }}#jadwal" class="nav-link" id="navLinkJadwal"
                            onclick="if(typeof toggleScheduleExplorer === 'function'){ toggleScheduleExplorer(true); }">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>Jadwal & Slot</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.index') }}#panduan" class="nav-link" id="navLinkAlur">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <span>Panduan</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="nav-actions">
                @if (!Auth::check() || !Auth::user()->isAdmin())
                    <a href="{{ route('booking.history') }}"
                        class="btn-history-pill {{ request()->routeIs('booking.history') ? 'active' : '' }}"
                        id="btnHeaderRiwayat" title="Lihat Riwayat Pemesanan Anda">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span class="btn-text-nav">Riwayat</span>
                        <span class="history-count-badge" id="headerHistoryBadge" style="display: none;">0</span>
                    </a>
                    <!-- Button Notifikasi Pelanggan (Di Samping Tombol Riwayat) -->
                    <button type="button" class="btn-notification-pill" id="btnHeaderNotifikasi"
                        onclick="toggleNotificationModal(event)" title="Notifikasi Status Persetujuan Ruangan">
                        <div class="notif-bell-icon-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span class="notif-pulse-dot" id="headerNotifPulseDot" style="display: none;"></span>
                        </div>
                        <span class="btn-text-nav">Notifikasi</span>
                        <span class="history-count-badge notif-count-badge" id="headerNotifBadge" style="display: none;">0</span>
                    </button>
                    <button type="button" class="btn-search-pill" id="btnHeaderCekStatus"
                        onclick="openStatusModal()" title="Cek Status Pengajuan Booking">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span class="btn-text-nav">Status</span>
                    </button>
                @endif
                <button type="button" class="btn btn-primary btn-pill btn-header-cta" id="btnHeaderAjukan"
                    onclick="openBookingModal()" title="Ajukan Booking Ruangan">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Booking</span>
                </button>


                @auth
                    @php
                        $rawName = Auth::user()->name;
                        $nameNoDigits = preg_replace('/^\d+\s+/', '', $rawName);
                        $nameParts = explode(' ', trim($nameNoDigits));
                        $shortName = $nameParts[0] ?: $rawName;
                        if (mb_strlen($shortName) > 9) {
                            $shortName = mb_substr($shortName, 0, 9) . '..';
                        }
                    @endphp
                    <div class="user-menu-pill" id="headerUserMenuPill">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="user-avatar-sm"
                                referrerpolicy="no-referrer">
                        @else
                            <div class="user-avatar-sm user-avatar-placeholder">
                                {{ strtoupper(substr($shortName, 0, 1)) }}
                            </div>
                        @endif
                        <span class="user-name-label" title="{{ Auth::user()->name }}">{{ $shortName }}</span>
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn-admin-pill"
                                title="Buka Panel Pegawai & Admin">
                                Admin
                            </a>
                        @else
                            <a href="{{ route('pelanggan.dashboard') }}" class="btn-admin-pill"
                                style="background: #1a73e8 !important;" title="Buka Portal Pelanggan Saya">
                                Portal
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST"
                            style="display: inline; margin: 0; flex-shrink: 0;">
                            @csrf
                            <button type="submit" class="btn-logout-pill" title="Keluar / Logout dari Akun">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span class="btn-logout-text">Keluar</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('pelanggan.login') }}" class="btn-login-google-pill" id="btnHeaderLoginGoogle"
                        title="Masuk ke Portal Pelanggan">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Login</span>
                    </a>
                @endauth

                <button type="button" class="nav-toggle" id="navToggleBtn" aria-label="Menu Navigasi Mobile"
                    onclick="toggleMobileNav()">
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div class="mobile-nav-backdrop" id="mobileNavBackdrop" onclick="closeMobileNav()"></div>
        <div class="mobile-nav-drawer" id="mobileNavDrawer">
            <div class="mobile-nav-header">
                <div class="brand">
                    <div class="brand-logo-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1a73e8"
                            stroke-width="2.2">
                            <path d="M3 21h18"></path>
                            <path d="M5 21V10"></path>
                            <path d="M19 21V10"></path>
                            <path d="M9 21V10"></path>
                            <path d="M15 21V10"></path>
                            <path d="M2 10l10-6 10 6"></path>
                        </svg>
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">Perpustakaan & Galeri</span>
                        <span class="brand-subtitle">KOTA BOGOR</span>
                    </div>
                </div>
                <button type="button" class="btn-close-drawer" onclick="closeMobileNav()" aria-label="Tutup Menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="mobile-nav-body">
                <ul class="mobile-nav-links">
                    <li><a href="{{ route('booking.index') }}"
                            class="mobile-nav-link {{ request()->routeIs('booking.index') ? 'active' : '' }}"
                            onclick="closeMobileNav()">Beranda</a></li>
                    <li><a href="{{ route('booking.index') }}#fasilitas" class="mobile-nav-link"
                            onclick="closeMobileNav()">Katalog Fasilitas</a></li>
                    <li><a href="{{ route('booking.index') }}#jadwal" class="mobile-nav-link"
                            onclick="closeMobileNav()">Cek Jadwal & Slot</a></li>
                    <li><a href="{{ route('booking.index') }}#panduan" class="mobile-nav-link"
                            onclick="closeMobileNav()">Panduan Peminjaman</a></li>
                    <li>
                        <a href="{{ route('booking.history') }}"
                            class="mobile-nav-link {{ request()->routeIs('booking.history') ? 'active' : '' }}"
                            onclick="closeMobileNav()">
                            <span>Riwayat Booking</span>
                            <span class="history-count-badge" id="mobileHistoryBadge" style="display: none;">0</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="mobile-nav-link"
                            onclick="closeMobileNav(); openNotificationModal();">
                            <span style="display: flex; align-items: center; gap: 8px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                                <span>Notifikasi Ruangan</span>
                            </span>
                            <span class="history-count-badge notif-count-badge" id="mobileNotifBadge" style="display: none;">0</span>
                        </a>
                    </li>
                </ul>
                <div class="mobile-nav-actions">
                    @auth
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #f8fafc; border-radius: 12px; margin-bottom: 12px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                @if (Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                                        style="width: 32px; height: 32px; border-radius: 50%;"
                                        referrerpolicy="no-referrer">
                                @else
                                    <div
                                        style="width: 32px; height: 32px; border-radius: 50%; background: #1a73e8; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 700; font-size: 0.84rem; color: #0f172a;">
                                        {{ Auth::user()->name }}</div>
                                    <div style="font-size: 0.72rem; color: #64748b;">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-outline"
                                    style="padding: 4px 10px; font-size: 0.75rem; border-radius: 8px;">Logout</button>
                            </form>
                        </div>
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-block"
                                style="margin-bottom: 8px; text-decoration: none; text-align: center; font-weight: 700;">
                                <span>Panel Pegawai & Admin</span>
                            </a>
                        @else
                            <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-outline btn-block"
                                style="margin-bottom: 8px; text-decoration: none; text-align: center; font-weight: 700; background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;">
                                <span>Portal Pelanggan Saya</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('pelanggan.login') }}" class="btn btn-outline btn-block"
                            style="margin-bottom: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; font-weight: 700;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                                style="color: #2563eb;">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Login Pelanggan</span>
                        </a>
                        <a href="{{ route('admin.login') }}"
                            style="display: block; text-align: center; font-size: 0.78rem; color: #64748b; margin-bottom: 12px; text-decoration: none;">
                            Pegawai Dinas? <strong>Masuk di sini &rarr;</strong>
                        </a>
                    @endauth

                    <button type="button" class="btn btn-outline btn-block"
                        onclick="closeMobileNav(); openStatusModal();">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span>Cek Status Booking</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-block"
                        onclick="closeMobileNav(); openBookingModal();">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Ajukan Booking Sekarang</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Bar Matching Civic Mockup -->
    <footer class="footer-civic">
        <div class="container footer-civic-inner">
            <div class="footer-civic-brand">
                <div class="footer-logo-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#60a5fa"
                        stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V10"></path>
                        <path d="M19 21V10"></path>
                        <path d="M9 21V10"></path>
                        <path d="M15 21V10"></path>
                        <path d="M2 10l10-6 10 6"></path>
                    </svg>
                </div>
                <div class="footer-brand-text">
                    <span class="footer-brand-title">Perpustakaan & Galeri</span>
                    <span class="footer-brand-city">KOTA BOGOR</span>
                </div>
            </div>

            <div class="footer-civic-location">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8"
                    stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>Jl. Kapten Muslihat No. 21, Kota Bogor</span>
            </div>

            <div class="footer-civic-right">
                <div class="footer-socials">
                    <a href="https://www.instagram.com/perpusbogor/" target="_blank" rel="noopener"
                        class="footer-social-btn" aria-label="Instagram">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                    <a href="https://www.youtube.com/@perpusbogor" target="_blank" rel="noopener"
                        class="footer-social-btn" aria-label="YouTube">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z">
                            </path>
                            <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                        </svg>
                    </a>

                    <a href="https://www.tiktok.com/@perpusbogor" target="_blank" rel="noopener noreferrer"
                        class="footer-social-btn" aria-label="TikTok">

                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.589 6.686a4.793 4.793 0 0 1-3.77-4.245V2h-3.903v13.672a2.896 2.896 0 0 1-2.9 2.825 2.9 2.9 0 1 1 0-5.8c.298 0 .59.046.87.134V8.847a6.8 6.8 0 0 0-.87-.058 6.805 6.805 0 1 0 6.805 6.805V9.04a8.64 8.64 0 0 0 5.05 1.62V6.76a4.824 4.824 0 0 1-1.282-.074Z" />
                        </svg>

                    </a>

                </div>
                <div class="footer-copyright-text">
                    &copy; 2024 Perpustakaan & Galeri Kota Bogor. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast Notification Container -->
    <div id="toastNotice" class="toast-notice"></div>

    <!-- Floating Customer Chat Widget -->
    @include('partials.customer-chat')

    <!-- Floating Dark Mode Toggle (Bottom-Left) -->
    <button type="button" id="floatingThemeToggleBtn" class="btn-theme-floating" onclick="toggleThemeMode()"
        aria-label="Ganti Mode Gelap / Terang" title="Ganti Mode Gelap / Terang">
        <span class="theme-icon-light" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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
        </span>
        <span class="theme-icon-dark" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </span>
    </button>

    <!-- Modals (Booking, Cek Status, Ticket) -->
    @include('partials.booking-modals')

    <!-- Jendela Notifikasi Pelanggan (Modal Window) -->
    @include('partials.customer-notification-modal')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
    <script>
        function toggleThemeMode() {
            var html = document.documentElement;
            var currentTheme = html.getAttribute('data-theme') || 'light';
            var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            if (newTheme === 'dark') {
                html.classList.add('dark-mode');
            } else {
                html.classList.remove('dark-mode');
            }
            try {
                localStorage.setItem('booking_perpus_theme', newTheme);
            } catch (e) {}
        }
    </script>
    @stack('scripts')
</body>

</html>
