@extends('layouts.admin')

@section('title', 'Dashboard Pegawai & Administrator - Perpustakaan Kota Bogor')

@section('content')
<div>
    
    <!-- Welcome Banner -->
    <div style="background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); border-radius: 20px; padding: 26px 30px; color: #ffffff; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(26, 115, 232, 0.25); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <span style="font-size: 0.78rem; font-weight: 700; background: rgba(255, 255, 255, 0.2); padding: 3px 10px; border-radius: 9999px; display: inline-block; margin-bottom: 8px;">
                SISTEM INFORMASI PELAYANAN FASILITAS & CHAT
            </span>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                <h1 style="font-size: 1.6rem; font-weight: 800; margin: 0; color: #ffffff;">Selamat Datang, {{ Auth::user()->name }}</h1>
                @if(Auth::user()->isSuperAdmin())
                    <span style="background: rgba(243, 232, 255, 0.95); color: #7e22ce; font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">👑 Super Admin</span>
                @else
                    <span style="background: rgba(224, 242, 254, 0.95); color: #0369a1; font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">🛡️ Admin</span>
                @endif
            </div>
            <p style="margin: 0; font-size: 0.88rem; color: #e0e7ff;">
                Pantau verifikasi peminjaman fasilitas, respon pesan pelanggan, dan cetak laporan rekapitulasi operasional secara efisien.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('admin.users.index') }}" class="btn" style="background: #7c3aed; color: #ffffff; font-weight: 700; font-size: 0.84rem; padding: 10px 18px; border-radius: 9999px; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.15); display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Kelola Anggota Admin</span>
                </a>
            @endif
            <a href="{{ route('admin.bookings.index', ['status' => 'menunggu']) }}" class="btn" style="background: #ffffff; color: #1a73e8; font-weight: 700; font-size: 0.84rem; padding: 10px 18px; border-radius: 9999px; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                Verifikasi Pengajuan ({{ $pendingBookings }})
            </a>
            <a href="{{ route('admin.chat.index') }}" class="btn" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; font-weight: 700; font-size: 0.84rem; padding: 10px 18px; border-radius: 9999px; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.3);">
                Buka Chat Pelanggan
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
                @csrf
                <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.9); color: #ffffff; font-weight: 700; font-size: 0.84rem; padding: 10px 18px; border-radius: 9999px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
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

    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 18px; border-radius: 14px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 18px; border-radius: 14px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- KPI Metric Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        
        <!-- Card 1: Menunggu Verifikasi -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.03); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #f59e0b;"></div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Menunggu Verifikasi</span>
                <span style="width: 32px; height: 32px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </span>
            </div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">{{ $pendingBookings }}</div>
            <div style="font-size: 0.76rem; color: #94a3b8; margin-top: 4px;">Butuh konfirmasi petugas</div>
        </div>

        <!-- Card 2: Disetujui -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.03); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #10b981;"></div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Disetujui / Aktif</span>
                <span style="width: 32px; height: 32px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </span>
            </div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">{{ $approvedBookings }}</div>
            <div style="font-size: 0.76rem; color: #94a3b8; margin-top: 4px;">Jadwal pemakaian resmi</div>
        </div>

        <!-- Card 3: Total Reservasi -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.03); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #1a73e8;"></div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Pengajuan</span>
                <span style="width: 32px; height: 32px; border-radius: 8px; background: #eff6ff; color: #1d4ed8; display: flex; align-items: center; justify-content: center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </span>
            </div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">{{ $totalBookings }}</div>
            <div style="font-size: 0.76rem; color: #94a3b8; margin-top: 4px;">Ruangan & Videotron</div>
        </div>

        <!-- Card 4: Chat Masuk -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.03); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #8b5cf6;"></div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Percakapan Chat</span>
                <span style="width: 32px; height: 32px; border-radius: 8px; background: #f3e8ff; color: #7c3aed; display: flex; align-items: center; justify-content: center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </span>
            </div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">
                {{ $totalConversations }}
                @if($unreadMessagesCount > 0)
                    <span style="font-size: 0.74rem; background: #ef4444; color: #ffffff; padding: 2px 7px; border-radius: 9999px; vertical-align: middle;">
                        {{ $unreadMessagesCount }} baru
                    </span>
                @endif
            </div>
            <div style="font-size: 0.76rem; color: #94a3b8; margin-top: 4px;">Konsultasi pelanggan</div>
        </div>

    </div>

    <!-- Main Workspace Split: Left (Pending verification table) & Right (Today's Schedule & Chats) -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start;">
        
        <!-- Left: Pengajuan yang Butuh Verifikasi Cepat -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
                <div>
                    <h2 style="font-size: 1.15rem; font-weight: 800; margin: 0 0 4px 0; color: #0f172a;">
                        Pengajuan Butuh Verifikasi
                    </h2>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0;">Pengajuan peminjaman fasilitas terbaru yang belum diverifikasi.</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" style="font-size: 0.82rem; font-weight: 700; color: #1a73e8; text-decoration: none;">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($recentPendingBookings->isEmpty())
                <div style="text-align: center; padding: 36px 16px; color: #94a3b8;">
                    <div style="font-size: 2rem; margin-bottom: 8px;">✨</div>
                    <div style="font-weight: 700; color: #334155; margin-bottom: 4px;">Semua Bersih!</div>
                    <p style="font-size: 0.84rem; margin: 0;">Tidak ada pengajuan reservasi yang menunggu verifikasi saat ini.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.74rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th style="padding: 10px 8px;">Kode & Pemohon</th>
                                <th style="padding: 10px 8px;">Fasilitas</th>
                                <th style="padding: 10px 8px;">Tanggal & Jam</th>
                                <th style="padding: 10px 8px; text-align: right;">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPendingBookings as $b)
                                <tr style="border-bottom: 1px solid #f8fafc;">
                                    <td style="padding: 12px 8px;">
                                        <div style="font-weight: 700; color: #1a73e8;">{{ $b->booking_code }}</div>
                                        <div style="font-weight: 600; color: #0f172a;">{{ $b->user->name }}</div>
                                        <div style="font-size: 0.74rem; color: #64748b;">{{ $b->user->phone_number }}</div>
                                    </td>
                                    <td style="padding: 12px 8px;">
                                        <div style="font-weight: 600; color: #1e293b;">{{ $b->room->name }}</div>
                                        <span style="font-size: 0.72rem; color: #64748b;">{{ $b->room->floor }}</span>
                                    </td>
                                    <td style="padding: 12px 8px;">
                                        <div style="font-weight: 600; color: #0f172a;">{{ $b->booking_date_formatted }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB</div>
                                    </td>
                                    <td style="padding: 12px 8px; text-align: right; white-space: nowrap;">
                                        <form action="{{ route('admin.bookings.status', $b->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="btn" style="background: #10b981; color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; border: none; cursor: pointer; margin-right: 4px;">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.bookings.status', $b->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menolak pengajuan ini?');">
                                            @csrf
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="btn" style="background: #ef4444; color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; border: none; cursor: pointer;">
                                                Tolak
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Right Column: Hari Ini & Chat Terbaru -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            <!-- Jadwal Pemakaian Hari Ini -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <h3 style="font-size: 1rem; font-weight: 800; margin: 0; color: #0f172a;">Jadwal Hari Ini</h3>
                    <span style="font-size: 0.72rem; color: #10b981; font-weight: 700;">{{ now()->translatedFormat('d M Y') }}</span>
                </div>

                @if($todayBookings->isEmpty())
                    <p style="font-size: 0.82rem; color: #94a3b8; margin: 0; text-align: center; padding: 20px 0;">
                        Tidak ada agenda peminjaman fasilitas untuk hari ini.
                    </p>
                @else
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($todayBookings as $tb)
                            <div style="padding: 10px 12px; background: #f8fafc; border-radius: 12px; border-left: 3px solid #1a73e8;">
                                <div style="font-weight: 700; font-size: 0.82rem; color: #0f172a;">{{ $tb->room->name }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">
                                    {{ substr($tb->start_time, 0, 5) }} - {{ substr($tb->end_time, 0, 5) }} &bull; {{ $tb->user->name }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Percakapan Chat Terbaru -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <h3 style="font-size: 1rem; font-weight: 800; margin: 0; color: #0f172a;">Pesan Pelanggan</h3>
                    <a href="{{ route('admin.chat.index') }}" style="font-size: 0.78rem; font-weight: 700; color: #1a73e8; text-decoration: none;">
                        Buka Inbox &rarr;
                    </a>
                </div>

                @if($recentChats->isEmpty())
                    <p style="font-size: 0.82rem; color: #94a3b8; margin: 0; text-align: center; padding: 20px 0;">
                        Belum ada riwayat pesan pelanggan.
                    </p>
                @else
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($recentChats as $chat)
                            @php $uCount = $chat->unreadCountForAdmin(); @endphp
                            <a href="{{ route('admin.chat.index', ['conversation_id' => $chat->id]) }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 10px; text-decoration: none; color: inherit; background: {{ $uCount > 0 ? '#eff6ff' : '#f8fafc' }}; transition: background 0.15s ease;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #1a73e8; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; flex-shrink: 0;">
                                    {{ strtoupper(substr($chat->user ? $chat->user->name : 'P', 0, 1)) }}
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; font-size: 0.8rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $chat->user ? $chat->user->name : 'Pelanggan' }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $chat->latestMessage ? $chat->latestMessage->message : 'Belum ada pesan' }}
                                    </div>
                                </div>
                                @if($uCount > 0)
                                    <span style="background: #ef4444; color: #fff; font-size: 0.65rem; font-weight: 800; padding: 2px 6px; border-radius: 9999px;">
                                        {{ $uCount }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection

