<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Pegawai & Admin - Perpustakaan Dan Galeri Kota Bogor')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
    
    <!-- Vanilla CSS Design System -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        /* Admin Navbar */
        .admin-navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .admin-nav-container {
            max-width: 100%;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            height: 60px;
        }
        .admin-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            flex-shrink: 0;
        }
        .admin-brand-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #1a73e8, #1557b0);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(26, 115, 232, 0.28);
        }
        .admin-brand-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .admin-brand-title {
            font-size: 0.88rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
            letter-spacing: -0.2px;
            white-space: nowrap;
        }
        .admin-brand-badge {
            font-size: 0.60rem;
            font-weight: 700;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 1px 6px;
            border-radius: 9999px;
            display: inline-block;
            letter-spacing: 0.4px;
            align-self: flex-start;
            border: 1px solid #dbeafe;
        }

        /* Nav Links */
        .admin-nav-links {
            display: flex;
            align-items: center;
            gap: 3px;
            list-style: none;
            margin: 0;
            padding: 3px;
            background: #f8fafc;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        .admin-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 9999px;
            font-size: 0.77rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: all 0.15s ease;
            position: relative;
            white-space: nowrap !important;
        }
        .admin-nav-link:hover {
            color: #0f172a;
            background: rgba(255, 255, 255, 0.95);
        }
        .admin-nav-link.active {
            color: #ffffff;
            background: #1a73e8;
            box-shadow: 0 2px 6px rgba(26, 115, 232, 0.28);
        }
        .admin-nav-link.active svg {
            stroke: #ffffff;
        }
        .nav-badge-count {
            background: #ef4444;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0 5px;
            min-width: 16px;
            height: 16px;
            line-height: 16px;
            text-align: center;
            border-radius: 9999px;
            margin-left: 2px;
        }
        .admin-nav-link.active .nav-badge-count {
            background: #ffffff;
            color: #1a73e8;
        }

        /* Right Actions */
        .admin-nav-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .nav-divider {
            width: 1px;
            height: 20px;
            background: #e2e8f0;
            margin: 0 2px;
        }
        .btn-view-public {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
            padding: 5px 11px;
            border-radius: 9999px;
            font-size: 0.76rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap !important;
        }
        .btn-view-public:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }
        .admin-user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 3px 6px 3px 4px;
            border-radius: 9999px;
        }
        .admin-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
        }
        .admin-avatar-ph {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #1e293b;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.78rem;
        }
        .admin-name {
            font-size: 0.78rem;
            font-weight: 700;
            color: #0f172a;
            max-width: 110px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }
        .btn-admin-logout {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap !important;
        }
        .btn-admin-logout:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        /* Content Wrapper */
        .admin-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 20px 60px 20px;
        }

        /* Responsive */
        @media (max-width: 1280px) {
            .admin-brand-badge {
                display: none;
            }
            .admin-nav-link {
                padding: 4px 9px;
                font-size: 0.75rem;
            }
        }
        @media (max-width: 1120px) {
            .btn-view-public span {
                display: none;
            }
            .admin-name {
                max-width: 70px;
            }
            .btn-admin-logout span {
                display: none;
            }
        }
        /* Dark Mode Admin Specifics */
        [data-theme="dark"] .admin-navbar {
            background: rgba(15, 23, 42, 0.96) !important;
            border-bottom: 1px solid #334155 !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;
        }
        [data-theme="dark"] .admin-brand-title {
            color: #f8fafc !important;
        }
        [data-theme="dark"] .admin-brand-badge {
            background: rgba(59, 130, 246, 0.2) !important;
            color: #93c5fd !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
        }
        [data-theme="dark"] .admin-nav-links {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] .admin-nav-link {
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .admin-nav-link:hover {
            background: #334155 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .admin-nav-link.active {
            background: #1a73e8 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-view-public {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .btn-view-public:hover {
            background: #334155 !important;
            color: #ffffff !important;
            border-color: #475569 !important;
        }
        [data-theme="dark"] .admin-user-profile {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] .admin-name {
            color: #f8fafc !important;
        }
        [data-theme="dark"] .nav-divider {
            background: #334155 !important;
        }
    </style>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo-perpus-bogor.png') }}">
</head>
<body>

    @php
        $pendingBadgeCount = \App\Models\Booking::where('status', 'menunggu')->count();
        $unreadChatBadgeCount = \App\Models\ChatMessage::where('is_read', false)
            ->whereHas('conversation', function ($q) {
                $q->whereColumn('chat_messages.sender_id', 'chat_conversations.user_id');
            })->count();
    @endphp

    <!-- Top Admin Header -->
    <header class="admin-navbar">
        <div class="admin-nav-container">
            
            <!-- Brand -->
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <div class="admin-brand-icon">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V10"></path>
                        <path d="M19 21V10"></path>
                        <path d="M9 21V10"></path>
                        <path d="M15 21V10"></path>
                        <path d="M2 10l10-6 10 6"></path>
                    </svg>
                </div>
                <div class="admin-brand-text">
                    <div class="admin-brand-title">Perpustakaan & Galeri Kota Bogor</div>
                    <span class="admin-brand-badge">PANEL ADMIN</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <ul class="admin-nav-links">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>Reservasi</span>
                        @if($pendingBadgeCount > 0)
                            <span class="nav-badge-count">{{ $pendingBadgeCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.chat.index') }}" class="admin-nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Chat</span>
                        @if($unreadChatBadgeCount > 0)
                            <span class="nav-badge-count">{{ $unreadChatBadgeCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                        <span>Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.rooms.index') }}" class="admin-nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        </svg>
                        <span>Fasilitas</span>
                    </a>
                </li>
                @if(Auth::user()->isSuperAdmin())
                <li>
                    <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Kelola Admin</span>
                    </a>
                </li>
                @endif
            </ul>

            <!-- Right Profile & Actions -->
            <div class="admin-nav-right">
                <a href="{{ route('booking.index') }}" class="btn-view-public" title="Lihat Website Publik">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <span>Website Publik</span>
                </a>


                <div class="nav-divider"></div>

                <div class="admin-user-profile">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="admin-avatar" referrerpolicy="no-referrer">
                    @else
                        <div class="admin-avatar-ph">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="admin-name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</div>
                        <div style="margin-top: 1px;">
                            @if(Auth::user()->isSuperAdmin())
                                <span style="background: #f3e8ff; color: #7e22ce; font-size: 0.62rem; font-weight: 800; padding: 1px 6px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 2px; border: 1px solid #e9d5ff;">
                                    👑 Super Admin
                                </span>
                            @else
                                <span style="background: #e0f2fe; color: #0284c7; font-size: 0.62rem; font-weight: 800; padding: 1px 6px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 2px; border: 1px solid #bae6fd;">
                                    🛡️ Admin
                                </span>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" class="btn-admin-logout" title="Logout dari Panel Admin">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </header>

    <!-- Main Content Area -->
    <main class="admin-content">
        @yield('content')
    </main>

    <!-- Floating Dark Mode Toggle (Bottom-Left) -->
    <button type="button" id="adminFloatingThemeToggleBtn" class="btn-theme-floating" onclick="toggleThemeMode()" aria-label="Ganti Mode Gelap / Terang" title="Ganti Mode Gelap / Terang">
        <span class="theme-icon-light" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </span>
    </button>

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

