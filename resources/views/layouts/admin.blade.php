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
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }
        .admin-nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
        }
        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }
        .admin-brand-icon {
            width: 38px;
            height: 38px;
            background: #1a73e8;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(26, 115, 232, 0.35);
        }
        .admin-brand-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        .admin-brand-badge {
            font-size: 0.68rem;
            font-weight: 700;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 2px 8px;
            border-radius: 9999px;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        /* Nav Links */
        .admin-nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 4px;
            background: #f8fafc;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
        }
        .admin-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }
        .admin-nav-link:hover {
            color: #0f172a;
            background: rgba(255, 255, 255, 0.9);
        }
        .admin-nav-link.active {
            color: #ffffff;
            background: #1a73e8;
            box-shadow: 0 2px 8px rgba(26, 115, 232, 0.35);
        }
        .admin-nav-link.active svg {
            stroke: #ffffff;
        }
        .nav-badge-count {
            background: #ef4444;
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 1px 6px;
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
            gap: 12px;
        }
        .btn-view-public {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
            padding: 6px 13px;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-view-public:hover {
            background: #f8fafc;
            color: #0f172a;
        }
        .admin-user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 4px 10px 4px 4px;
            border-radius: 9999px;
        }
        .admin-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        .admin-avatar-ph {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #1e293b;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.82rem;
        }
        .admin-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: #0f172a;
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Content Wrapper */
        .admin-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 20px 60px 20px;
        }

        /* Responsive */
        @media (max-width: 1250px) {
            .admin-brand-badge {
                display: none;
            }
            .admin-nav-link span {
                font-size: 0.78rem;
            }
            .admin-nav-link {
                padding: 5px 10px;
            }
        }
        @media (max-width: 1100px) {
            .btn-view-public span {
                display: none;
            }
            .admin-name {
                max-width: 80px;
            }
        }
        @media (max-width: 1024px) {
            .admin-nav-links {
                display: none;
            }
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
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V10"></path>
                        <path d="M19 21V10"></path>
                        <path d="M9 21V10"></path>
                        <path d="M15 21V10"></path>
                        <path d="M2 10l10-6 10 6"></path>
                    </svg>
                </div>
                <div>
                    <div class="admin-brand-title">Perpustakaan & Galeri Kota Bogor</div>
                    <span class="admin-brand-badge">PANEL PEGAWAI & ADMINISTRATOR</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <ul class="admin-nav-links">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>Data Reservasi</span>
                        @if($pendingBadgeCount > 0)
                            <span class="nav-badge-count">{{ $pendingBadgeCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.chat.index') }}" class="admin-nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Chat Pelanggan</span>
                        @if($unreadChatBadgeCount > 0)
                            <span class="nav-badge-count">{{ $unreadChatBadgeCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                        <span>Laporan Rekap</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.rooms.index') }}" class="admin-nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        </svg>
                        <span>Fasilitas</span>
                    </a>
                </li>
                @if(Auth::user()->isSuperAdmin())
                <li>
                    <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <span>Website Publik</span>
                </a>

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
                                <span style="background: #f3e8ff; color: #7e22ce; font-size: 0.65rem; font-weight: 800; padding: 1px 7px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 3px; border: 1px solid #e9d5ff;">
                                    👑 Super Admin
                                </span>
                            @else
                                <span style="background: #e0f2fe; color: #0284c7; font-size: 0.65rem; font-weight: 800; padding: 1px 7px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 3px; border: 1px solid #bae6fd;">
                                    🛡️ Admin
                                </span>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" style="display: inline-flex; align-items: center; gap: 5px; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 9999px; font-size: 0.76rem; font-weight: 700; cursor: pointer; text-decoration: none; transition: all 0.2s ease;" title="Logout dari Panel Admin">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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

    @stack('scripts')
</body>
</html>

